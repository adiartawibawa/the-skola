<?php

namespace Src\Core\Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * TenantScope — Global Scope Isolasi Data
 *
 * Mekanisme kerja:
 *   1. Dipanggil Eloquent secara otomatis setiap kali query builder
 *      dibuat dari model yang menggunakan scope ini.
 *   2. Membaca 'current.tenant.id' dari IoC Container.
 *   3. Jika nilai null (Super-Admin), scope tidak diterapkan.
 *   4. Jika nilai ada (user sekolah), menambahkan klausa
 *      WHERE {table}.sekolah_id = '{uuid}' ke query.
 *
 * Thread-safety:
 *   app('current.tenant.id') di-bind per-request oleh
 *   ResolveTenantMiddleware. Di lingkungan worker (Octane, FrankenPHP),
 *   pastikan binding ini di-reset di event request.terminated.
 *
 * Contoh SQL yang dihasilkan:
 *   Untuk user sekolah:
 *     SELECT * FROM `siswas` WHERE `siswas`.`sekolah_id` = 'uuid-xxx'
 *   Untuk Super-Admin (null):
 *     SELECT * FROM `siswas`  ← tidak ada filter sekolah_id
 *
 * Kolom qualifier (table.column) digunakan untuk menghindari
 * ambiguity saat query menggunakan JOIN ke tabel lain.
 */
class TenantScope implements Scope
{
    /**
     * Nama scope — digunakan sebagai key saat withoutGlobalScope().
     * Konstanta ini memastikan tidak ada typo saat remove scope.
     */
    public const NAME = 'tenant';

    public function apply(Builder $builder, Model $model): void
    {
        // Ambil tenant ID yang sudah di-bind oleh ResolveTenantMiddleware.
        // Gunakan app()->bound() untuk deteksi — hindari KeyNotFoundException
        // yang muncul jika binding belum ada sama sekali (context: console).
        if (! app()->bound('current.tenant.id')) {
            // Di console / job tanpa tenant context — tidak apply scope.
            // Ini disengaja: job background bisa akses lintas tenant.
            // Jika job harus scoped, inject manual sebelum query.
            return;
        }

        $sekolahId = app('current.tenant.id');

        // null = Super-Admin → scope tidak diterapkan, akses lintas tenant
        if (is_null($sekolahId)) {
            return;
        }

        // Tambahkan kolom qualifier (table.column) untuk menghindari
        // ambiguity saat ada JOIN. Contoh: "siswas.sekolah_id"
        $builder->where(
            $model->getTable().'.sekolah_id',
            '=',
            $sekolahId
        );
    }

    /**
     * Extend query builder dengan macro untuk keperluan debugging.
     * Ini memungkinkan developer melihat apakah scope sedang aktif.
     *
     * Contoh penggunaan di tinker:
     *   Siswa::hasTenantScope() → true/false
     */
    public function extend(Builder $builder): void
    {
        $builder->macro('hasTenantScope', function (Builder $builder) {
            return array_key_exists(
                self::NAME,
                $builder->getModel()->getGlobalScopes()
            );
        });
    }
}
