<?php

namespace Src\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * EnsureTeamPermission — Validasi Permission Setelah Tenant Resolved
 *
 * Middleware ini bekerja setelah ResolveTenantMiddleware.
 * Memeriksa apakah user memiliki permission tertentu dalam
 * konteks team (sekolah) yang sudah di-resolve.
 *
 * Cara daftarkan di route:
 *   Route::middleware(['auth', 'resolve.tenant', 'permission:akademik.siswa.view-any'])
 *       ->group(fn() => ...);
 *
 * Atau lewat Filament canAccess() di Resource:
 *   public static function canViewAny(): bool
 *   {
 *       return auth()->user()->can('akademik.siswa.view-any');
 *   }
 *
 * Spatie sudah menangani team-aware check secara otomatis
 * setelah setPermissionsTeamId() dipanggil di ResolveTenantMiddleware.
 */
class EnsureTeamPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): mixed
    {
        $user = $request->user();

        if (! $user) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        // Super-Admin bypass — sekolah_id = null punya akses penuh
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Cek satu atau lebih permission (OR logic — cukup satu yang terpenuhi)
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return $next($request);
            }
        }

        abort(Response::HTTP_FORBIDDEN, 'Akses ditolak: permission tidak ditemukan.');
    }
}
