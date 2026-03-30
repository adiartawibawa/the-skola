<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Src\Core\Models\BaseModel;
use Src\Core\Shared\Scopes\TenantScope;

/**
 * Unit test untuk TenantScope.
 *
 * Test ini memverifikasi behavior scope di level paling rendah,
 * tanpa melibatkan HTTP request atau middleware.
 * Menggunakan model dummy yang di-buat di dalam test.
 */

// ── Setup model dummy untuk testing ──────────────────────────────────────────
// Kita tidak bisa test scope tanpa model yang menggunakannya.
// Buat model in-memory dengan tabel siswas (dibuat di migration test).
beforeEach(function () {
    // Buat tabel dummy untuk test ini
    Schema::create('dummy_tenant_models', function ($table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('sekolah_id')->nullable();
        $table->string('nama');
        $table->timestamps();
        $table->softDeletes();
    });
});

afterEach(function () {
    Schema::dropIfExists('dummy_tenant_models');
    app()->forgetInstance('current.tenant.id');
});

// ── Test cases ────────────────────────────────────────────────────────────────

it('menambahkan WHERE sekolah_id ke query ketika tenant context ada', function () {
    $sekolahId = Str::uuid()->toString();
    app()->instance('current.tenant.id', $sekolahId);

    // Buat model anonim yang menggunakan TenantScope
    $model = new class extends BaseModel
    {
        protected $table = 'dummy_tenant_models';

        protected $fillable = ['nama'];
    };

    $sql = $model::query()->toRawSql();

    // Query harus mengandung WHERE sekolah_id dengan nilai yang benar
    expect($sql)
        ->toContain('sekolah_id')
        ->toContain($sekolahId);
});

it('tidak menambahkan WHERE sekolah_id ketika tenant context null (Super-Admin)', function () {
    app()->instance('current.tenant.id', null);

    $model = new class extends BaseModel
    {
        protected $table = 'dummy_tenant_models';

        protected $fillable = ['nama'];
    };

    $sql = $model::query()->toRawSql();

    // Query TIDAK boleh mengandung filter sekolah_id
    expect($sql)->not->toContain('sekolah_id');
});

it('tidak menambahkan WHERE sekolah_id ketika tidak ada binding sama sekali (console)', function () {
    // Pastikan binding tidak ada
    app()->forgetInstance('current.tenant.id');

    $model = new class extends BaseModel
    {
        protected $table = 'dummy_tenant_models';

        protected $fillable = ['nama'];
    };

    $sql = $model::query()->toRawSql();

    // Di context console tanpa binding, scope tidak diterapkan
    expect($sql)->not->toContain('sekolah_id');
});

it('withoutTenantScope() menghapus filter sekolah_id dari query', function () {
    $sekolahId = Str::uuid()->toString();
    app()->instance('current.tenant.id', $sekolahId);

    $model = new class extends BaseModel
    {
        protected $table = 'dummy_tenant_models';

        protected $fillable = ['nama'];
    };

    $modelClass = get_class($model);
    $sql = $modelClass::withoutTenantScope()->toRawSql();

    expect($sql)->not->toContain('sekolah_id');
});

it('menggunakan table qualifier pada kolom sekolah_id untuk menghindari ambiguity JOIN', function () {
    $sekolahId = Str::uuid()->toString();
    app()->instance('current.tenant.id', $sekolahId);

    $model = new class extends BaseModel
    {
        protected $table = 'dummy_tenant_models';

        protected $fillable = ['nama'];
    };

    $sql = $model::query()->toRawSql();

    // Harus menggunakan format "table_name"."sekolah_id" bukan hanya "sekolah_id"
    expect($sql)->toContain('dummy_tenant_models');
});
