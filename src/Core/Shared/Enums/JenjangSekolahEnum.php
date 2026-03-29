<?php

namespace Src\Core\Shared\Enums;

/**
 * Enum jenjang pendidikan sesuai klasifikasi Kemendikbud RI.
 *
 * Digunakan di:
 * - Migration sekolahs (kolom jenjang)
 * - Model Sekolah (casting otomatis)
 * - Filament Resource (filter & form select)
 * - Spatie Permission seeder (prefix role per jenjang)
 *
 * Catatan Bali:
 * SMA dan SMK mendominasi di Denpasar dan Badung (kawasan pariwisata).
 * SLB tersebar di setiap kabupaten, dikelola Dinas Pendidikan Provinsi.
 */
enum JenjangSekolahEnum: string
{
    case KB = 'KB';
    case TK = 'TK';
    case SD = 'SD';
    case SMP = 'SMP';
    case SMA = 'SMA';
    case SMK = 'SMK';
    case SLB = 'SLB';

    /** Label human-readable untuk Filament UI */
    public function label(): string
    {
        return match ($this) {
            self::KB => 'Kelompok Belajar',
            self::TK => 'Taman Kanak Kanak',
            self::SD => 'Sekolah Dasar',
            self::SMP => 'Sekolah Menengah Pertama',
            self::SMA => 'Sekolah Menengah Atas',
            self::SMK => 'Sekolah Menengah Kejuruan',
            self::SLB => 'Sekolah Luar Biasa',
        };
    }

    /** Konversi ke array untuk Filament select options */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
