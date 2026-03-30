<?php

// tests/Feature/SuperAdminAccessTest.php
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Src\Core\Models\BaseModel;
use Src\Core\Models\Sekolah;

/**
 * Memverifikasi bahwa Super-Admin (sekolah_id = null) memiliki
 * akses lintas tenant tanpa filter sekolah_id.
 */
beforeEach(function () {
    if (! Schema::hasTable('siswas')) {
        Schema::create('siswas', function ($table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sekolah_id');
            $table->string('nama_lengkap');
            $table->string('nis', 20);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    $this->sekolah_a = Sekolah::factory()->create();
    $this->sekolah_b = Sekolah::factory()->create();
    $this->sekolah_c = Sekolah::factory()->create();
    $this->super_admin = User::factory()->superAdmin()->create();

    $this->modelClass = new class extends BaseModel
    {
        protected $table = 'siswas';

        protected $fillable = ['nama_lengkap', 'nis'];
    };

    get_class($this->modelClass);
});

afterEach(fn () => app()->forgetInstance('current.tenant.id'));

it('[SUPER-ADMIN] dapat melihat data dari semua sekolah sekaligus', function () {
    $modelClass = $this->modelClass;

    // Buat data di tiga sekolah berbeda
    withTenant($this->sekolah_a->id, fn () => $modelClass::create(['nama_lengkap' => 'Siswa A', 'nis' => 'A001'])
    );
    withTenant($this->sekolah_b->id, fn () => $modelClass::create(['nama_lengkap' => 'Siswa B', 'nis' => 'B001'])
    );
    withTenant($this->sekolah_c->id, fn () => $modelClass::create(['nama_lengkap' => 'Siswa C', 'nis' => 'C001'])
    );

    // Super-Admin login — harus lihat semua 3 record
    actingAsSuperAdmin($this->super_admin);
    $semua = $modelClass::all();

    expect($semua)->toHaveCount(3);
    expect($semua->pluck('nama_lengkap')->toArray())
        ->toContain('Siswa A', 'Siswa B', 'Siswa C');
});

it('[SUPER-ADMIN] withoutTenantScope() mengembalikan data semua sekolah', function () {
    $modelClass = $this->modelClass;

    withTenant($this->sekolah_a->id, fn () => $modelClass::create(['nama_lengkap' => 'Data A', 'nis' => 'A001'])
    );
    withTenant($this->sekolah_b->id, fn () => $modelClass::create(['nama_lengkap' => 'Data B', 'nis' => 'B001'])
    );

    // Bahkan dari context user sekolah A, withoutTenantScope melihat semua
    actingAsSchool(
        User::factory()->forSekolah($this->sekolah_a)->create(),
        $this->sekolah_a
    );

    $semua = $modelClass::withoutTenantScope()->get();
    expect($semua)->toHaveCount(2);
});

it('[SUPER-ADMIN] isSuperAdmin() mengembalikan true untuk user tanpa sekolah_id', function () {
    expect($this->super_admin->isSuperAdmin())->toBeTrue();
    expect($this->super_admin->sekolah_id)->toBeNull();
});

it('[SUPER-ADMIN] user sekolah bukan Super-Admin meskipun punya nama role sama', function () {
    $userBiasa = User::factory()->forSekolah($this->sekolah_a)->create();
    expect($userBiasa->isSuperAdmin())->toBeFalse();
});
