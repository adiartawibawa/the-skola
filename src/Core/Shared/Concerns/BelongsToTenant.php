<?php

namespace Src\Core\Shared\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Core\Models\Sekolah;
use Src\Core\Shared\Scopes\TenantScope;

/**
 * Trait BelongsToTenant
 *
 * Menyediakan tiga fungsi utama yang dibutuhkan setiap model domain:
 *   1. Boot hook: otomatis mendaftarkan TenantScope dan auto-fill sekolah_id
 *   2. Relasi sekolah() yang konsisten di semua model
 *   3. Static helper withoutTenantScope() untuk Super-Admin bypass
 *
 * Cara penggunaan:
 *   class SiswaModel extends BaseModel
 *   {
 *       // BelongsToTenant sudah di-include via BaseModel::booted()
 *       // Tidak perlu deklarasi ulang di tiap model.
 *   }
 *
 * Trait ini dipisah dari BaseModel agar bisa digunakan
 * secara selektif pada model yang tidak extend BaseModel
 * (edge case: model pivot yang butuh tenant scope).
 */
trait BelongsToTenant
{
    /**
     * Boot trait — dipanggil sekali saat model class di-load.
     * Semua hooks di sini berlaku untuk seluruh instance model.
     */
    public static function bootBelongsToTenant(): void
    {
        // ── Daftarkan TenantScope ──────────────────────────────────────
        static::addGlobalScope(new TenantScope);

        // ── Auto-fill sekolah_id saat CREATE ──────────────────────────
        // Memastikan setiap record baru otomatis memiliki sekolah_id
        // dari tenant yang sedang aktif, tanpa harus set manual di controller.
        //
        // Guard: skip jika sekolah_id sudah di-set (mis. dari seeder/import)
        // atau jika tidak ada tenant context (console command).
        static::creating(function (self $model): void {
            if (
                empty($model->sekolah_id)
                && app()->bound('current.tenant.id')
                && ! is_null(app('current.tenant.id'))
            ) {
                $model->sekolah_id = app('current.tenant.id');
            }
        });

        // ── Validasi sekolah_id sebelum UPDATE ────────────────────────
        // Mencegah sekolah_id diubah setelah record dibuat.
        // Ini adalah invariant yang tidak boleh dilanggar.
        static::updating(function (self $model): void {
            if ($model->isDirty('sekolah_id')) {
                throw new \RuntimeException(
                    'sekolah_id tidak dapat diubah setelah record dibuat. '.
                    'Model: '.static::class
                );
            }
        });
    }

    // ── Relasi ────────────────────────────────────────────────────────

    /**
     * Relasi ke model Sekolah (tenant owner).
     * Tersedia di semua model domain secara otomatis.
     *
     * Contoh: $siswa->sekolah->nama_sekolah
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────

    /**
     * Query tanpa TenantScope — hanya untuk Super-Admin / sistem internal.
     *
     * PERHATIAN: Gunakan dengan sangat hati-hati. Method ini menghapus
     * isolasi tenant dan memperlihatkan data dari SEMUA sekolah.
     *
     * Konteks yang diizinkan:
     *   - Super-Admin melakukan operasi lintas tenant
     *   - Job background yang memproses data multi-sekolah
     *   - Console command maintenance
     *   - Test yang memverifikasi data lintas tenant
     *
     * Contoh:
     *   SiswaModel::withoutTenantScope()->whereIn('sekolah_id', [...])->get()
     */
    public static function withoutTenantScope(): Builder
    {
        return static::withoutGlobalScope(TenantScope::class);
    }

    /**
     * Cek apakah model ini memiliki TenantScope aktif.
     * Berguna untuk debugging di tinker.
     */
    public static function hasTenantScope(): bool
    {
        return array_key_exists(
            TenantScope::class,
            (new static)->getGlobalScopes()
        );
    }
}
