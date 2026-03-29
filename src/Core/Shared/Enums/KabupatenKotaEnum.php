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
    case JEMBRANA = 'Kabupaten Jembrana';
    case TABANAN = 'Kabupaten Tabanan';
    case BADUNG = 'Kabupaten Badung';
    case GIANYAR = 'Kabupaten Gianyar';
    case KLUNGKUNG = 'Kabupaten Klungkung';
    case BANGLI = 'Kabupaten Bangli';
    case KARANGASEM = 'Kabupaten Karangasem';
    case BULELENG = 'Kabupaten Buleleng';
    case DENPASAR = 'Kota Denpasar';

    /** Kode BPS Kemendagri untuk integrasi data pemerintah */
    public function kodeBps(): string
    {
        return match ($this) {
            self::JEMBRANA => '5101',
            self::TABANAN => '5102',
            self::BADUNG => '5103',
            self::GIANYAR => '5104',
            self::KLUNGKUNG => '5105',
            self::BANGLI => '5106',
            self::KARANGASEM => '5107',
            self::BULELENG => '5108',
            self::DENPASAR => '5171',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->value])
            ->toArray();
    }
}
