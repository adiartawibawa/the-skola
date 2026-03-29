<?php

namespace Src\Core\Shared\Enums\Wilayah;

use Src\Core\Shared\Enums\KecamatanEnum;

/**
 * Trait untuk semua DesaKelurahan enum.
 *
 * Karena PHP tidak mendukung trait pada enum secara langsung untuk
 * backed enum, kita gunakan pola interface + method duplikasi ringan.
 * Cara paling bersih di PHP 8.2: copy-paste 3 method ini ke tiap enum.
 * Alternatif: gunakan WilayahBaliHelper sebagai proxy (lihat bagian 4).
 */
trait HasWilayahMethods
{
    /**
     * Nama resmi desa/kelurahan.
     * Konvensi penamaan case: PEMECUTAN_KAJA → "Pemecutan Kaja"
     */
    public function label(): string
    {
        return collect(explode('_', $this->name))
            ->map(fn ($w) => ucfirst(strtolower($w)))
            ->implode(' ');
    }

    /**
     * Resolve ke KecamatanEnum dari 7 digit pertama kode desa.
     */
    public function kecamatan(): KecamatanEnum
    {
        return KecamatanEnum::from(substr($this->value, 0, 7));
    }

    /**
     * Daftar desa dalam kecamatan tertentu untuk cascade dropdown.
     */
    public static function byKecamatan(KecamatanEnum $kecamatan): array
    {
        return collect(static::cases())
            ->filter(fn ($d) => str_starts_with($d->value, $kecamatan->value))
            ->mapWithKeys(fn ($d) => [$d->value => $d->label()])->toArray();
    }
}
