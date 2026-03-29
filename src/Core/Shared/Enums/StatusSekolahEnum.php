<?php

namespace Src\Core\Shared\Enums;

/**
 * Status kepemilikan/penyelenggaraan sekolah.
 * Berpengaruh pada alur validasi data dan hak akses laporan ke dinas.
 */
enum StatusSekolahEnum: string
{
    case NEGERI = 'negeri';
    case SWASTA = 'swasta';

    public function label(): string
    {
        return match ($this) {
            self::NEGERI => 'Negeri',
            self::SWASTA => 'Swasta',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
