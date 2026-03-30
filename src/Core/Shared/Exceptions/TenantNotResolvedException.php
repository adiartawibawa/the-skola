<?php

namespace Src\Core\Shared\Exceptions;

use RuntimeException;

/**
 * Exception yang dilempar ketika TenantScope tidak dapat
 * menemukan tenant context di dalam IoC Container.
 *
 * Ini terjadi jika:
 * 1. Model domain diakses dari luar request context (mis. console command)
 *    tanpa terlebih dahulu meng-inject tenant secara manual.
 * 2. ResolveTenantMiddleware tidak terpasang di route group yang seharusnya.
 * 3. Unit test tidak menggunakan helper actingAsSchool() / withTenant().
 *
 * Cara mengatasi di console command:
 *   app()->instance('current.tenant.id', $sekolahId);
 *   // jalankan operasi...
 *   app()->forgetInstance('current.tenant.id');
 */
class TenantNotResolvedException extends RuntimeException
{
    public function __construct(string $modelClass = '')
    {
        $context = $modelClass ? " saat mengakses model [{$modelClass}]" : '';

        parent::__construct(
            "Tenant context belum di-resolve{$context}. ".
            'Pastikan ResolveTenantMiddleware terpasang di route group ini, '.
            "atau inject tenant secara manual via app()->instance('current.tenant.id', \$id)."
        );
    }
}
