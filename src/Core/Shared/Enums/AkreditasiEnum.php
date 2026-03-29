<?php

namespace Src\Core\Shared\Enums;

/**
 * Enum status akreditasi sekolah dari BAN-S/M (Badan Akreditasi Nasional).
 *
 * Nilai akreditasi diperbarui setiap 5 tahun oleh asesor BAN-S/M.
 * Super-Admin dapat memfilter sekolah berdasarkan akreditasi
 * untuk keperluan laporan ke Dinas Pendidikan Provinsi Bali.
 */
enum AkreditasiEnum: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case BELUM = 'Belum';
    case TT = 'TT';

    public function label(): string
    {
        return match ($this) {
            self::A => 'Amat Baik (A)',
            self::B => 'Baik (B)',
            self::C => 'Cukup (C)',
            self::BELUM => 'Belum Terakreditasi',
            self::TT => 'Tidak Terakreditasi',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
