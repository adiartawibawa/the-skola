<?php

namespace Src\Core\Shared\Enums;

/**
 * Enum sembilan kabupaten/kota di Provinsi Bali.
 *
 * Sumber: Peraturan Mendagri tentang kode & data wilayah administrasi.
 * Kode wilayah mengikuti standar Kemendagri (kode BPS).
 *
 * Digunakan untuk:
 * - Validasi input data sekolah
 * - Filter laporan per wilayah oleh Super-Admin
 * - Penentuan zona waktu (seluruh Bali = WIB+1 / WITA)
 */
enum KabupatenKotaEnum: string
{
    case JEMBRANA = '5101';
    case TABANAN = '5102';
    case BADUNG = '5103';
    case GIANYAR = '5104';
    case KLUNGKUNG = '5105';
    case BANGLI = '5106';
    case KARANGASEM = '5107';
    case BULELENG = '5108';
    case DENPASAR = '5171';

    /**
     * Nama lengkap untuk ditampilkan di UI.
     * Nama resmi mengikuti Permendagri — "Kabupaten X" atau "Kota X".
     */
    public function label(): string
    {
        return match ($this) {
            self::JEMBRANA => 'Kabupaten Jembrana',
            self::TABANAN => 'Kabupaten Tabanan',
            self::BADUNG => 'Kabupaten Badung',
            self::GIANYAR => 'Kabupaten Gianyar',
            self::KLUNGKUNG => 'Kabupaten Klungkung',
            self::BANGLI => 'Kabupaten Bangli',
            self::KARANGASEM => 'Kabupaten Karangasem',
            self::BULELENG => 'Kabupaten Buleleng',
            self::DENPASAR => 'Kota Denpasar',
        };
    }

    /**
     * Untuk Filament Select — key adalah kode BPS, value adalah nama.
     * Contoh return: ['5171' => 'Kota Denpasar', '5103' => 'Kabupaten Badung', ...]
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }

    /**
     * Resolve dari city_id laravolt ke enum.
     * Digunakan saat membaca data dari database.
     * Contoh: KabupatenKotaEnum::fromCityId('5171') → self::DENPASAR
     */
    public static function fromCityId(string $cityId): self
    {
        return self::from(substr($cityId, 0, 4));
    }
}
