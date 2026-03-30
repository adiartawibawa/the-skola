<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Core\Models\Sekolah;
use Tests\TestCase;

uses(RefreshDatabase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Helper: Simulasikan request dari user sekolah tertentu.
 * Menginjeksi tenant context ke IoC container persis seperti
 * yang dilakukan ResolveTenantMiddleware di production.
 *
 * Penggunaan di test:
 *   $sekolah = Sekolah::factory()->create();
 *   $user = User::factory()->forSekolah($sekolah)->create();
 *   actingAsSchool($user, $sekolah);
 */
function actingAsSchool(User $user, Sekolah $sekolah): User
{
    test()->actingAs($user);

    // Inject tenant context — sama persis dengan ResolveTenantMiddleware
    app()->instance('current.tenant.id', $sekolah->id);
    app()->instance('current.tenant', $sekolah);
    setPermissionsTeamId($sekolah->id);

    return $user;
}

/**
 * Helper: Simulasikan request dari Super-Admin.
 */
function actingAsSuperAdmin(User $superAdmin): User
{
    test()->actingAs($superAdmin);
    app()->instance('current.tenant.id', null);
    app()->instance('current.tenant', null);
    setPermissionsTeamId(null);

    return $superAdmin;
}

/**
 * Helper: Jalankan callback dalam konteks tenant tertentu
 * tanpa perlu authenticated user (berguna untuk Unit test).
 *
 * Penggunaan:
 *   withTenant($sekolah->id, function () {
 *       $siswa = SiswaModel::create([...]);
 *       expect($siswa->sekolah_id)->toBe($sekolahId);
 *   });
 */
function withTenant(string $sekolahId, callable $callback): mixed
{
    app()->instance('current.tenant.id', $sekolahId);
    try {
        return $callback();
    } finally {
        // Selalu cleanup agar tidak bocor ke test berikutnya
        app()->forgetInstance('current.tenant.id');
        app()->forgetInstance('current.tenant');
    }
}

/**
 * Helper: Jalankan callback tanpa tenant context sama sekali
 * (simulasi Super-Admin atau console command).
 */
function withoutTenant(callable $callback): mixed
{
    app()->instance('current.tenant.id', null);
    try {
        return $callback();
    } finally {
        app()->forgetInstance('current.tenant.id');
        app()->forgetInstance('current.tenant');
    }
}
