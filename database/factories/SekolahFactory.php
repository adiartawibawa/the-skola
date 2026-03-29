<?php

// database/factories/SekolahFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Core\Models\Sekolah;
use Src\Core\Shared\Enums\AkreditasiEnum;
use Src\Core\Shared\Enums\JenjangSekolahEnum;
use Src\Core\Shared\Enums\KabupatenKotaEnum;
use Src\Core\Shared\Enums\StatusSekolahEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanBulelengEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanDenpasarEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanKarangasemEnum;

/**
 * Factory: SekolahFactory
 *
 * Digunakan di:
 * - Feature tests untuk membuat tenant sample
 * - Unit tests untuk menguji isolasi data
 * - Pest PHP: Sekolah::factory()->create()
 *
 * Contoh penggunaan di test:
 *   $sekolah = Sekolah::factory()->sma()->negeri()->denpasar()->create();
 *   $sekolah = Sekolah::factory()->count(9)->create(); // satu per kabupaten
 */
class SekolahFactory extends Factory
{
    protected $model = Sekolah::class;

    public function definition(): array
    {
        // 1. Pilih Kabupaten secara random dari Enum
        $kabupaten = $this->faker->randomElement(KabupatenKotaEnum::cases());

        // 2. Pilih Desa/Kelurahan yang sesuai dengan Kabupaten tersebut
        $desaEnum = match ($kabupaten->value) {
            '5171' => DesaKelurahanDenpasarEnum::class,
            '5108' => DesaKelurahanBulelengEnum::class,
            '5107' => DesaKelurahanKarangasemEnum::class,
            default => DesaKelurahanDenpasarEnum::class,
        };

        $desaMatch = $this->faker->randomElement($desaEnum::cases());
        $kodeDesa = $desaMatch->value; // 10 digit (misal: 5171011001)
        $kodeKecamatan = substr($kodeDesa, 0, 6); // Ambil 6 digit awal untuk kecamatan

        return [
            'npsn' => $this->faker->unique()->numerify('5010####'),
            'nss' => $this->faker->optional()->numerify('##############'),
            'nama_sekolah' => $this->generateNamaSekolah($kabupaten->name),
            'jenjang' => $this->faker->randomElement(JenjangSekolahEnum::cases())->value,
            'status_sekolah' => $this->faker->randomElement(StatusSekolahEnum::cases())->value,
            'akreditasi' => $this->faker->randomElement(AkreditasiEnum::cases())->value,

            // Integrasi Wilayah Enum
            'kabupaten_kota' => $kabupaten->value,
            'kecamatan' => $kodeKecamatan,
            'desa_kelurahan' => $kodeDesa,

            'alamat_lengkap' => $this->faker->streetAddress(),
            'kode_pos' => $this->faker->numerify('8####'),
            'telepon' => '0361-'.$this->faker->numerify('######'),
            'email' => $this->faker->optional()->safeEmail(),
            'website' => $this->faker->optional()->url(),
            'logo_path' => null,
            'settings' => null,
            'is_aktif' => true,
            'tanggal_bergabung' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }

    private function generateNamaSekolah(string $lokasi): string
    {
        return 'Negeri '.$this->faker->numberBetween(1, 10).' '.str($lokasi)->title();
    }

    // ── Named States untuk test yang lebih ekspresif ────────────────────

    public function sma(): static
    {
        return $this->state(['jenjang' => JenjangSekolahEnum::SMA->value]);
    }

    public function smk(): static
    {
        return $this->state(['jenjang' => JenjangSekolahEnum::SMK->value]);
    }

    public function negeri(): static
    {
        return $this->state(['status_sekolah' => StatusSekolahEnum::NEGERI->value]);
    }

    public function swasta(): static
    {
        return $this->state(['status_sekolah' => StatusSekolahEnum::SWASTA->value]);
    }

    // ── Named States untuk testing ──────────────────────────────────────

    public function denpasar(): static
    {
        return $this->state(function (array $attributes) {
            $desa = $this->faker->randomElement(DesaKelurahanDenpasarEnum::cases());

            return [
                'kabupaten_kota' => '5171',
                'kecamatan' => substr($desa->value, 0, 6),
                'desa_kelurahan' => $desa->value,
            ];
        });
    }

    public function buleleng(): static
    {
        return $this->state(function (array $attributes) {
            $desa = $this->faker->randomElement(DesaKelurahanBulelengEnum::cases());

            return [
                'kabupaten_kota' => '5108',
                'kecamatan' => substr($desa->value, 0, 6),
                'desa_kelurahan' => $desa->value,
            ];
        });
    }

    public function nonaktif(): static
    {
        return $this->state(['is_aktif' => false]);
    }

    public function akreditasiA(): static
    {
        return $this->state(['akreditasi' => AkreditasiEnum::A->value]);
    }
}
