<?php

// src/Core/Http/Middleware/ResolveTenantMiddleware.php

namespace Src\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\Core\Models\Sekolah;

/**
 * ResolveTenantMiddleware — Jantung Sistem Multi-Tenancy
 *
 * Middleware ini harus terpasang SETELAH Authenticate middleware
 * karena ia membutuhkan $request->user() yang sudah valid.
 *
 * Tanggung jawab:
 *   1. Membaca sekolah_id dari user yang sudah login
 *   2. Memvalidasi sekolah masih aktif (bukan di-suspend)
 *   3. Mengikat sekolah_id ke IoC Container sebagai 'current.tenant.id'
 *   4. Mengikat objek Sekolah lengkap sebagai 'current.tenant'
 *   5. Set Spatie Permission team context agar role check ter-scope
 *
 * Urutan middleware yang benar di Kernel / RouteServiceProvider:
 *   'auth'             → verifikasi user login
 *   'resolve.tenant'   → inject tenant context  ← middleware ini
 *   'check.permission' → cek role/permission per tenant
 *
 * Keamanan:
 *   Tenant identity HANYA berasal dari database user yang terautentikasi.
 *   Tidak ada header, query param, atau subdomain yang dapat mempengaruhi
 *   tenant resolution. Ini mencegah tenant spoofing dari client.
 */
class ResolveTenantMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();

        // ── Bind tenant context ke IoC Container ──────────────────────
        $sekolahId = $user?->sekolah_id; // null = Super-Admin

        app()->instance('current.tenant.id', $sekolahId);

        // ── Bind objek Sekolah (lazy, hanya jika bukan Super-Admin) ───
        // Objek Sekolah dibutuhkan oleh Filament panel resolver
        // dan service-service yang butuh akses ke settings sekolah.
        if (! is_null($sekolahId)) {
            $sekolah = Sekolah::find($sekolahId);

            // Validasi: sekolah harus aktif
            // Jika sekolah di-suspend oleh Super-Admin, tolak akses
            if (! $sekolah || ! $sekolah->is_aktif) {
                // Logout user dan return response 403
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
                    'message' => 'Sekolah Anda telah dinonaktifkan. Hubungi administrator.',
                ], Response::HTTP_FORBIDDEN);
            }

            app()->instance('current.tenant', $sekolah);

            // ── Set Spatie Permission team context ─────────────────────
            // Ini WAJIB dipanggil sebelum any permission check agar
            // hasPermissionTo() / hasRole() otomatis scoped ke sekolah ini.
            // Nilai team_id = sekolah_id sesuai config permission.php
            setPermissionsTeamId($sekolahId);
        } else {
            // Super-Admin: bind null tenant, tanpa team restriction
            app()->instance('current.tenant', null);

            // Super-Admin tidak punya team context di Spatie.
            // Akses diatur via Gate::before() di AuthServiceProvider.
            setPermissionsTeamId(null);
        }

        return $next($request);
    }

    /**
     * Cleanup setelah response dikirim.
     * Penting untuk Octane / long-running process agar
     * tenant context tidak bocor ke request berikutnya.
     */
    public function terminate(Request $request, mixed $response): void
    {
        app()->forgetInstance('current.tenant.id');
        app()->forgetInstance('current.tenant');
    }
}
