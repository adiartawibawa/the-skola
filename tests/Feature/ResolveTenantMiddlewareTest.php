<?php

// tests/Feature/ResolveTenantMiddlewareTest.php
use App\Models\User;
use Src\Core\Models\Sekolah;

/**
 * Memverifikasi behavior ResolveTenantMiddleware dalam berbagai skenario.
 */
beforeEach(function () {
    $this->sekolah = Sekolah::factory()->create(['is_aktif' => true]);
    $this->sekolahNonaktif = Sekolah::factory()->nonaktif()->create();
});

afterEach(fn () => app()->forgetInstance('current.tenant.id'));

it('middleware meng-inject current.tenant.id dari sekolah_id user', function () {
    $user = User::factory()->forSekolah($this->sekolah)->create();

    $this->actingAs($user)
        ->get(route('dashboard')) // route apapun yang pakai middleware
        ->assertOk();

    expect(app('current.tenant.id'))->toBe($this->sekolah->id);
});

it('middleware menolak akses jika sekolah tidak aktif dengan status 403', function () {
    $user = User::factory()->forSekolah($this->sekolahNonaktif)->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertStatus(403);
});

it('Super-Admin tidak terkena validasi is_aktif sekolah', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    $this->actingAs($superAdmin)
        ->get(route('dashboard'))
        ->assertOk();

    // current.tenant.id untuk Super-Admin = null
    expect(app('current.tenant.id'))->toBeNull();
});

it('middleware set Spatie team ID sesuai sekolah_id user', function () {
    $user = User::factory()->forSekolah($this->sekolah)->create();

    $this->actingAs($user)->get(route('dashboard'));

    // Verifikasi team ID di Spatie Permission
    expect(getPermissionsTeamId())->toBe($this->sekolah->id);
});

it('current.tenant berisi objek Sekolah yang benar', function () {
    $user = User::factory()->forSekolah($this->sekolah)->create();

    $this->actingAs($user)->get(route('dashboard'));

    $tenant = app('current.tenant');
    expect($tenant)->toBeInstanceOf(Sekolah::class);
    expect($tenant->id)->toBe($this->sekolah->id);
});
