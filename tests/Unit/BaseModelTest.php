<?php

use Illuminate\Support\Facades\Schema;
use Src\Core\Models\BaseModel;
use Src\Core\Models\Sekolah;

beforeEach(function () {
    Schema::create('dummy_base_models', function ($table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('sekolah_id')->nullable();
        $table->string('nama');
        $table->timestamps();
        $table->softDeletes();
    });
});

afterEach(function () {
    Schema::dropIfExists('dummy_base_models');
    app()->forgetInstance('current.tenant.id');
});

// Model anonim yang digunakan sepanjang test ini
function makeDummyModel(): string
{
    $instance = new class extends BaseModel
    {
        protected $table = 'dummy_base_models';

        protected $fillable = ['nama'];
    };

    return get_class($instance);
}

it('auto-fill sekolah_id dari tenant context saat creating', function () {
    $sekolah = Sekolah::factory()->create();
    app()->instance('current.tenant.id', $sekolah->id);

    $modelClass = makeDummyModel();
    $instance = $modelClass::create(['nama' => 'Test Record']);

    expect($instance->sekolah_id)->toBe($sekolah->id);
});

it('tidak override sekolah_id yang sudah di-set secara eksplisit', function () {
    $sekolah1 = Sekolah::factory()->create();
    $sekolah2 = Sekolah::factory()->create();

    // Tenant aktif adalah sekolah1
    app()->instance('current.tenant.id', $sekolah1->id);

    $modelClass = makeDummyModel();

    // Tapi kita set sekolah_id ke sekolah2 secara manual (kasus seeder)
    // Ini seharusnya TIDAK di-override oleh auto-fill
    $instance = new $modelClass(['nama' => 'Seeder Record']);
    $instance->sekolah_id = $sekolah2->id;
    $instance->save();

    expect($instance->sekolah_id)->toBe($sekolah2->id); // tetap sekolah2
});

it('melempar exception ketika mencoba mengubah sekolah_id setelah create', function () {
    $sekolah1 = Sekolah::factory()->create();
    $sekolah2 = Sekolah::factory()->create();
    app()->instance('current.tenant.id', $sekolah1->id);

    $modelClass = makeDummyModel();
    $instance = $modelClass::create(['nama' => 'Original']);

    expect(fn () => tap($instance)->update(['sekolah_id' => $sekolah2->id]))
        ->toThrow(RuntimeException::class, 'sekolah_id tidak dapat diubah');
});

it('primary key adalah UUID valid', function () {
    $sekolah = Sekolah::factory()->create();
    app()->instance('current.tenant.id', $sekolah->id);

    $modelClass = makeDummyModel();
    $instance = $modelClass::create(['nama' => 'UUID Test']);

    expect($instance->id)
        ->toBeString()
        ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i');
});

it('soft delete tidak menghapus record secara permanen', function () {
    $sekolah = Sekolah::factory()->create();
    app()->instance('current.tenant.id', $sekolah->id);

    $modelClass = makeDummyModel();
    $instance = $modelClass::create(['nama' => 'Will Be Deleted']);
    $id = $instance->id;

    $instance->delete();

    // Dengan scope aktif, record tidak ditemukan via query normal
    expect($modelClass::find($id))->toBeNull();

    // Tapi masih ada di database (soft delete)
    expect($modelClass::withTrashed()->find($id))->not->toBeNull();
    expect($modelClass::withTrashed()->find($id)->deleted_at)->not->toBeNull();
});
