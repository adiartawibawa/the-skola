<?php

namespace Src\Core\Shared\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface marker untuk semua model yang harus di-scope per tenant.
 *
 * Semua model yang extends BaseModel otomatis mengimplementasikan
 * interface ini. Berguna untuk:
 * 1. Type-hint di Service dan Repository yang hanya menerima model tenant
 * 2. Deteksi di middleware / observer via instanceof check
 * 3. Dokumentasi arsitektur yang eksplisit
 *
 * Contoh penggunaan di BaseRepository:
 *   public function __construct(private TenantScopeable $model) {}
 */
interface TenantScopeable
{
    /**
     * Kembalikan query builder tanpa TenantScope.
     * Hanya boleh dipanggil oleh Super-Admin atau job sistem internal.
     */
    public static function withoutTenantScope(): Builder;
}
