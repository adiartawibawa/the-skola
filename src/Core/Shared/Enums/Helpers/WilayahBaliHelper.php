<?php

// src/Core/Shared/Helpers/WilayahBaliHelper.php

namespace Src\Core\Shared\Helpers;

use Src\Core\Shared\Enums\KabupatenKotaEnum;
use Src\Core\Shared\Enums\KecamatanEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanBadungEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanBangliEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanBulelengEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanDenpasarEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanGianyarEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanJembranaEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanKarangasemEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanKlungkungEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanTabananEnum;

/**
 * WilayahBaliHelper — Aggregator Seluruh Enum Wilayah Bali
 *
 * Class ini adalah single entry point untuk semua kebutuhan
 * data wilayah di sistem SIAKAD. Filament dan controller
 * cukup menggunakan class ini, tidak perlu import banyak enum.
 *
 * Kegunaan utama:
 * 1. Cascade dropdown: pilih kabupaten → daftar kecamatan berubah
 *    → daftar desa berubah. Diimplementasikan via Filament Select
 *    dengan reactive() dan afterStateUpdated().
 *
 * 2. Validasi form: memastikan kombinasi kabupaten + kecamatan + desa
 *    konsisten sebelum disimpan ke database.
 *
 * 3. Resolve label dari kode: menampilkan nama wilayah dari kode BPS
 *    yang tersimpan di database.
 *
 * Contoh penggunaan di Filament Resource:
 *
 *   Select::make('kabupaten_kota')
 *       ->options(KabupatenKotaEnum::options())
 *       ->reactive(),
 *
 *   Select::make('kecamatan')
 *       ->options(fn($get) => WilayahBaliHelper::kecamatanOptions(
 *           KabupatenKotaEnum::from($get('kabupaten_kota'))
 *       ))
 *       ->reactive(),
 *
 *   Select::make('desa_kelurahan')
 *       ->options(fn($get) => WilayahBaliHelper::desaOptions(
 *           KecamatanEnum::from($get('kecamatan'))
 *       )),
 */
class WilayahBaliHelper
{
    /**
     * Map: kode BPS kabupaten → enum class desa
     */
    private static array $desaEnumMap = [
        '5101' => DesaKelurahanJembranaEnum::class,
        '5102' => DesaKelurahanTabananEnum::class,
        '5103' => DesaKelurahanBadungEnum::class,
        '5104' => DesaKelurahanGianyarEnum::class,
        '5105' => DesaKelurahanKlungkungEnum::class,
        '5106' => DesaKelurahanBangliEnum::class,
        '5107' => DesaKelurahanKarangasemEnum::class,
        '5108' => DesaKelurahanBulelengEnum::class,
        '5171' => DesaKelurahanDenpasarEnum::class,
    ];

    /**
     * Daftar kecamatan untuk dropdown berdasarkan kabupaten.
     * Return: ['5103010' => 'Kuta Selatan', ...]
     */
    public static function kecamatanOptions(KabupatenKotaEnum $kabupaten): array
    {
        return KecamatanEnum::byKabupaten($kabupaten);
    }

    /**
     * Daftar desa/kelurahan untuk dropdown berdasarkan kecamatan.
     * Return: ['5103020001' => 'Kuta', ...]
     */
    public static function desaOptions(KecamatanEnum $kecamatan): array
    {
        $kodeKab = substr($kecamatan->value, 0, 4);
        $enumClass = self::$desaEnumMap[$kodeKab] ?? null;

        if ($enumClass === null) {
            return [];
        }

        return $enumClass::byKecamatan($kecamatan);
    }

    /**
     * Resolve nama label dari kode BPS 10 digit.
     * Contoh: '5103020001' → 'Kuta'
     */
    public static function labelDesa(string $kodeBps): string
    {
        $kodeKab = substr($kodeBps, 0, 4);
        $enumClass = self::$desaEnumMap[$kodeKab] ?? null;

        if ($enumClass === null) {
            return $kodeBps;
        }

        try {
            return $enumClass::from($kodeBps)->label();
        } catch (\ValueError) {
            return $kodeBps; // fallback ke kode jika tidak ditemukan
        }
    }

    /**
     * Validasi: apakah kombinasi kabupaten + kecamatan + desa konsisten?
     * Digunakan di FormRequest atau Filament custom validation.
     */
    public static function validateKombinasi(
        string $kabupaten,
        string $kecamatan,
        string $desa
    ): bool {
        // Kecamatan harus berada di kabupaten yang dipilih
        if (! str_starts_with($kecamatan, substr($kabupaten, 0, 4))) {
            return false;
        }

        // Desa harus berada di kecamatan yang dipilih
        if (! str_starts_with($desa, $kecamatan)) {
            return false;
        }

        return true;
    }

    /**
     * Semua data wilayah dalam satu array flat — untuk export/laporan.
     * Return: array of ['kabupaten', 'kecamatan', 'desa', 'kode_desa']
     */
    public static function allWilayah(): array
    {
        $result = [];

        foreach (KabupatenKotaEnum::cases() as $kab) {
            foreach (KecamatanEnum::byKabupaten($kab) as $kodeKec => $namaKec) {
                $kec = KecamatanEnum::from($kodeKec);
                foreach (self::desaOptions($kec) as $kodeDesa => $namaDesa) {
                    $result[] = [
                        'kabupaten' => $kab->value,
                        'kecamatan' => $namaKec,
                        'desa' => $namaDesa,
                        'kode_desa' => $kodeDesa,
                    ];
                }
            }
        }

        return $result;
    }
}
