<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

/**
 * AuthServiceProvider — Konfigurasi Gate dan Policy
 *
 * Super-Admin bypass via Gate::before():
 *   Setiap Gate check diawali dengan callback ini.
 *   Jika user adalah Super-Admin (sekolah_id = null),
 *   return true langsung tanpa memeriksa permission.
 *
 *   Catatan: return null (bukan false) di Gate::before()
 *   berarti "lanjutkan ke check berikutnya" — ini penting
 *   agar user biasa masih melalui permission check normal.
 */
class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Policy modul akan didaftarkan di sini oleh masing-masing
        // ServiceProvider modul (bukan di sini langsung):
        // \Src\Akademik\Domain\Siswa::class => \Src\Akademik\Domain\Policies\SiswaPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Super-Admin mendapat akses ke semua gate check.
        // Ini adalah satu-satunya tempat logika Super-Admin bypass.
        // Jangan duplikasi check ini di controller atau service.
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true; // izinkan semua — short-circuit
            }

            return null; // lanjut ke permission check normal
        });
    }
}
