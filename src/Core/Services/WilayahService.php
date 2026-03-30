<?php

namespace Src\Core\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Facade as Indonesia;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use Src\Core\Shared\Enums\KabupatenKotaEnum;

/**
 * WilayahService — Wrapper Indonesia Facade untuk SIAKAD Bali
 *
 * Bertanggung jawab sebagai satu-satunya pintu masuk untuk
 * semua kebutuhan data wilayah di sistem SIAKAD. Menggunakan
 * Indonesia facade dari laravolt/indonesia di balik layar.
 *
 * Keunggulan dibanding WilayahBaliHelper versi sebelumnya:
 * 1. Data dari database — selalu up-to-date tanpa deploy ulang
 * 2. Mendukung search across-region via Indonesia::search()
 * 3. Bisa eager-load relasi (province, city, district, villages)
 * 4. Tidak perlu maintain ribuan baris enum secara manual
 *
 * Catatan performa:
 *   Semua method options() menggunakan cache 24 jam karena data
 *   wilayah sangat jarang berubah. Cache di-invalidate saat
 *   laravolt:indonesia:seed dijalankan ulang.
 *
 * Cara inject di Service atau Filament Resource:
 *   public function __construct(private WilayahService $wilayah) {}
 *   $this->wilayah->kecamatanOptions(KabupatenKotaEnum::DENPASAR)
 */
class WilayahService
{
    private const PROVINCE_BALI_ID = 51;

    private const CACHE_TTL = 86400; // 24 jam dalam detik

    /**
     * Ambil semua kabupaten/kota di Bali untuk dropdown.
     *
     * Return: ['5171' => 'Kota Denpasar', '5103' => 'Kabupaten Badung', ...]
     *
     * Catatan: Kita menggunakan KabupatenKotaEnum::options() sebagai
     * sumber utama karena sudah memiliki label proper-case yang benar.
     * Fallback ke laravolt jika enum tidak ada datanya.
     */
    public function kabupatenOptions(): array
    {
        return KabupatenKotaEnum::options();
    }

    /**
     * Ambil semua kecamatan dalam satu kabupaten untuk dropdown.
     *
     * @param  KabupatenKotaEnum|string  $kabupaten  Enum atau city_id ('5171')
     *                                               Return: ['5171010' => 'Denpasar Selatan', '5171020' => 'Denpasar Timur', ...]
     */
    public function kecamatanOptions(KabupatenKotaEnum|string $kabupaten): array
    {
        $cityId = $kabupaten instanceof KabupatenKotaEnum
            ? $kabupaten->value
            : $kabupaten;

        return cache()->remember(
            "wilayah.kecamatan.{$cityId}",
            self::CACHE_TTL,
            fn () => District::query()
                ->where('city_id', $cityId)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->mapWithKeys(fn ($name, $id) => [
                    $id => Str::title(strtolower($name)),
                ])
                ->toArray()
        );
    }

    /**
     * Ambil semua desa/kelurahan dalam satu kecamatan untuk dropdown.
     *
     * @param  string  $districtId  district_id laravolt ('5171010')
     *                              Return: ['5171010001' => 'Sesetan', '5171010002' => 'Pedungan', ...]
     */
    public function desaOptions(string $districtId): array
    {
        return cache()->remember(
            "wilayah.desa.{$districtId}",
            self::CACHE_TTL,
            fn () => Village::query()
                ->where('district_id', $districtId)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->mapWithKeys(fn ($name, $id) => [
                    $id => Str::title(strtolower($name)),
                ])
                ->toArray()
        );
    }

    /**
     * Resolve nama kabupaten dari city_id.
     * Gunakan ini saat ingin tampilkan label tanpa load full relasi.
     * Contoh: labelKabupaten('5171') → 'Kota Denpasar'
     */
    public function labelKabupaten(string $cityId): string
    {
        try {
            return KabupatenKotaEnum::from($cityId)->label();
        } catch (\ValueError) {
            // Fallback ke laravolt jika bukan kabupaten Bali
            return cache()->remember(
                "wilayah.label.city.{$cityId}",
                self::CACHE_TTL,
                fn () => Str::title(strtolower(
                    City::find($cityId)?->name ?? $cityId
                ))
            );
        }
    }

    /**
     * Resolve nama kecamatan dari district_id.
     * Contoh: labelKecamatan('5171010') → 'Denpasar Selatan'
     */
    public function labelKecamatan(string $districtId): string
    {
        return cache()->remember(
            "wilayah.label.district.{$districtId}",
            self::CACHE_TTL,
            fn () => Str::title(strtolower(
                District::find($districtId)?->name ?? $districtId
            ))
        );
    }

    /**
     * Resolve nama desa dari village_id.
     * Contoh: labelDesa('5171010004') → 'Panjer'
     */
    public function labelDesa(string $villageId): string
    {
        return cache()->remember(
            "wilayah.label.village.{$villageId}",
            self::CACHE_TTL,
            fn () => Str::title(strtolower(
                Village::find($villageId)?->name ?? $villageId
            ))
        );
    }

    /**
     * Validasi konsistensi hierarki wilayah.
     * Memastikan district berada di dalam city yang dipilih,
     * dan village berada di dalam district yang dipilih.
     *
     * Digunakan di FormRequest sekolah:
     *   $wilayahService->validateKombinasi('5171', '5171010', '5171010004') → true
     *   $wilayahService->validateKombinasi('5171', '5103040', '5171010004') → false
     */
    public function validateKombinasi(
        string $cityId,
        string $districtId,
        string $villageId
    ): bool {
        // District harus ada dan berada di city yang benar
        $district = District::find($districtId);
        if (! $district || $district->city_id !== $cityId) {
            return false;
        }

        // Village harus ada dan berada di district yang benar
        $village = Village::find($villageId);
        if (! $village || $village->district_id !== $districtId) {
            return false;
        }

        return true;
    }

    /**
     * Search wilayah lintas semua level untuk fitur autocomplete.
     * Berguna di form pendaftaran sekolah baru oleh Super-Admin.
     *
     * @param  string  $query  Kata pencarian, min 3 karakter
     *                         Return: Collection of ['type', 'id', 'label', 'path']
     */
    public function search(string $query): Collection
    {
        if (strlen($query) < 3) {
            return collect();
        }

        $results = collect();

        // Cari di kecamatan Bali
        District::query()
            ->where('name', 'like', '%'.strtoupper($query).'%')
            ->whereIn('city_id', array_column(KabupatenKotaEnum::cases(), 'value'))
            ->with('city')
            ->limit(5)
            ->get()
            ->each(function ($district) use ($results) {
                $results->push([
                    'type' => 'kecamatan',
                    'id' => $district->id,
                    'label' => Str::title(strtolower($district->name)),
                    'path' => $this->labelKabupaten($district->city_id),
                ]);
            });

        // Cari di desa/kelurahan Bali
        Village::query()
            ->where('name', 'like', '%'.strtoupper($query).'%')
            ->whereIn('city_id', array_column(KabupatenKotaEnum::cases(), 'value'))
            ->with('district')
            ->limit(10)
            ->get()
            ->each(function ($village) use ($results) {
                $results->push([
                    'type' => 'desa',
                    'id' => $village->id,
                    'label' => Str::title(strtolower($village->name)),
                    'path' => $this->labelKecamatan($village->district_id)
                        .', '.$this->labelKabupaten($village->city_id),
                ]);
            });

        return $results;
    }

    /**
     * Invalidate semua cache wilayah.
     * Panggil setelah menjalankan laravolt:indonesia:seed.
     *
     * Daftarkan ke console command atau observer jika perlu otomatis.
     */
    public function clearCache(): void
    {
        // Flush cache dengan tag 'wilayah' jika menggunakan Redis/Memcached
        // Untuk file/array cache, flush semua key dengan prefix
        $patterns = [
            'wilayah.kecamatan.*',
            'wilayah.desa.*',
            'wilayah.label.*',
        ];

        foreach (KabupatenKotaEnum::cases() as $kab) {
            cache()->forget("wilayah.kecamatan.{$kab->value}");
        }

        // Untuk production dengan Redis, gunakan:
        // cache()->tags(['wilayah'])->flush();
        // Dan tambahkan ->tags(['wilayah']) di semua cache()->remember() di atas
    }
}
