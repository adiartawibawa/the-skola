<?php

// tests/Feature/TenantIsolationTest.php
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Src\Core\Models\BaseModel;
use Src\Core\Models\Sekolah;

/**
 * Test Suite Utama — Isolasi Data Antar Tenant
 *
 * Ini adalah test PALING PENTING dalam seluruh sistem.
 * Setiap test di sini memverifikasi bahwa data satu sekolah
 * tidak dapat diakses oleh user sekolah lain.
 *
 * Jika test ini gagal, sistem memiliki kebocoran data tenant —
 * yang merupakan bug keamanan level kritis.
 *
 * Menggunakan model Siswa sebagai representative domain model.
 * Saat modul Akademik belum dibangun, buat model stub di bawah.
 */

// ── Setup ─────────────────────────────────────────────────────────────────────
beforeEach(function () {
    // Buat tabel siswas minimal untuk keperluan test ini
    // (akan diganti model asli saat modul Akademik dibangun)
    if (! Schema::hasTable('siswas')) {
        Schema::create('siswas', function ($table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sekolah_id');
            $table->string('nama_lengkap');
            $table->string('nis', 20);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['sekolah_id', 'nis']);
        });
    }

    // Buat dua sekolah terpisah sebagai "dua tenant berbeda"
    $this->sekolah_a = Sekolah::factory()->create(['nama_sekolah' => 'Negeri 1 Test']);
    $this->sekolah_b = Sekolah::factory()->create(['nama_sekolah' => 'Negeri 2 Test']);

    // Buat user untuk masing-masing sekolah
    $this->user_a = User::factory()->forSekolah($this->sekolah_a)->create();
    $this->user_b = User::factory()->forSekolah($this->sekolah_b)->create();

    // Buat Super-Admin
    $this->super_admin = User::factory()->superAdmin()->create();

    // Model Siswa stub (replace dengan model asli saat tersedia)
    $this->siswaModel = new class extends BaseModel
    {
        protected $table = 'siswas';

        protected $fillable = ['nama_lengkap', 'nis'];
    };
    $this->siswaModelClass = get_class($this->siswaModel);
});

afterEach(function () {
    app()->forgetInstance('current.tenant.id');
    app()->forgetInstance('current.tenant');
});

// ── Test Cases ────────────────────────────────────────────────────────────────

it('[ISOLASI] user sekolah A tidak dapat melihat data sekolah B', function () {
    $modelClass = $this->siswaModelClass;

    // Buat siswa di sekolah B
    withTenant($this->sekolah_b->id, function () use ($modelClass) {
        $modelClass::create(['nama_lengkap' => 'Ni Luh Ayu Bali', 'nis' => 'B001']);
        $modelClass::create(['nama_lengkap' => 'I Gede Wira Bali', 'nis' => 'B002']);
    });

    // User A login — hanya bisa lihat data sekolahnya sendiri
    actingAsSchool($this->user_a, $this->sekolah_a);

    $siswaYangTerlihat = $modelClass::all();

    // User A seharusnya tidak melihat SATU PUN siswa dari sekolah B
    expect($siswaYangTerlihat)->toHaveCount(0);
    expect($siswaYangTerlihat->pluck('nama_lengkap'))
        ->not->toContain('Ni Luh Ayu Bali')
        ->not->toContain('I Gede Wira Bali');
});

it('[ISOLASI] user sekolah B tidak dapat melihat data sekolah A', function () {
    $modelClass = $this->siswaModelClass;

    // Buat 3 siswa di sekolah A
    withTenant($this->sekolah_a->id, function () use ($modelClass) {
        $modelClass::create(['nama_lengkap' => 'I Wayan Surya', 'nis' => 'A001']);
        $modelClass::create(['nama_lengkap' => 'Ni Ketut Sari', 'nis' => 'A002']);
        $modelClass::create(['nama_lengkap' => 'I Made Dharma', 'nis' => 'A003']);
    });

    // User B login
    actingAsSchool($this->user_b, $this->sekolah_b);
    $siswaYangTerlihat = $modelClass::all();

    expect($siswaYangTerlihat)->toHaveCount(0);
});

it('[ISOLASI] user sekolah A hanya melihat datanya sendiri ketika ada data di dua sekolah', function () {
    $modelClass = $this->siswaModelClass;

    // Buat data di kedua sekolah
    withTenant($this->sekolah_a->id, fn () => $modelClass::create([
        'nama_lengkap' => 'Siswa Sekolah A', 'nis' => 'A001',
    ]));
    withTenant($this->sekolah_b->id, fn () => $modelClass::create([
        'nama_lengkap' => 'Siswa Sekolah B', 'nis' => 'B001',
    ]));

    // Login sebagai user A
    actingAsSchool($this->user_a, $this->sekolah_a);
    $hasil = $modelClass::all();

    // Tepat 1 record — milik sekolah A saja
    expect($hasil)->toHaveCount(1);
    expect($hasil->first()->nama_lengkap)->toBe('Siswa Sekolah A');
    expect($hasil->first()->sekolah_id)->toBe($this->sekolah_a->id);
});

it('[ISOLASI] find() dengan ID milik sekolah lain mengembalikan null', function () {
    $modelClass = $this->siswaModelClass;

    // Buat siswa di sekolah B
    $siswaB = withTenant($this->sekolah_b->id, fn () => $modelClass::create(['nama_lengkap' => 'Siswa Rahasia B', 'nis' => 'B001'])
    );

    // User A mencoba akses langsung via ID siswa sekolah B
    actingAsSchool($this->user_a, $this->sekolah_a);
    $hasil = $modelClass::find($siswaB->id);

    // Harus null — TenantScope mencegah akses
    expect($hasil)->toBeNull();
});

it('[ISOLASI] count() hanya menghitung record milik tenant aktif', function () {
    $modelClass = $this->siswaModelClass;

    // Buat 5 siswa di sekolah A dan 3 di sekolah B
    withTenant($this->sekolah_a->id, function () use ($modelClass) {
        foreach (range(1, 5) as $i) {
            $modelClass::create(['nama_lengkap' => "Siswa A{$i}", 'nis' => "A00{$i}"]);
        }
    });
    withTenant($this->sekolah_b->id, function () use ($modelClass) {
        foreach (range(1, 3) as $i) {
            $modelClass::create(['nama_lengkap' => "Siswa B{$i}", 'nis' => "B00{$i}"]);
        }
    });

    actingAsSchool($this->user_a, $this->sekolah_a);
    expect($modelClass::count())->toBe(5); // hanya milik A

    actingAsSchool($this->user_b, $this->sekolah_b);
    expect($modelClass::count())->toBe(3); // hanya milik B
});

it('[AUTO-FILL] record yang dibuat user A otomatis memiliki sekolah_id sekolah A', function () {
    $modelClass = $this->siswaModelClass;

    actingAsSchool($this->user_a, $this->sekolah_a);
    $siswa = $modelClass::create(['nama_lengkap' => 'Auto Fill Test', 'nis' => 'AF001']);

    expect($siswa->sekolah_id)->toBe($this->sekolah_a->id);
});

it('[SOFT DELETE] record yang di-soft-delete tidak muncul di query normal tapi masih di-scope', function () {
    $modelClass = $this->siswaModelClass;

    actingAsSchool($this->user_a, $this->sekolah_a);
    $siswa = $modelClass::create(['nama_lengkap' => 'Akan Dihapus', 'nis' => 'DEL001']);
    $siswa->delete();

    // Tidak muncul di query normal
    expect($modelClass::all())->toHaveCount(0);

    // Tapi withTrashed tetap di-scope ke sekolah A
    $trashedDariA = $modelClass::withTrashed()->get();
    expect($trashedDariA)->toHaveCount(1);
    expect($trashedDariA->first()->sekolah_id)->toBe($this->sekolah_a->id);

    // User B tidak bisa lihat trashed record sekolah A
    actingAsSchool($this->user_b, $this->sekolah_b);
    expect($modelClass::withTrashed()->get())->toHaveCount(0);
});
