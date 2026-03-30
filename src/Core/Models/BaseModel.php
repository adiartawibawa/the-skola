<?php

namespace Src\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Src\Core\Shared\Concerns\BelongsToTenant;
use Src\Core\Shared\Contracts\TenantScopeable;

/**
 * BaseModel — Fondasi Semua Model Domain SKOLA
 *
 * Setiap model bisnis dalam modul WAJIB extend class ini.
 * Model yang TIDAK boleh extend BaseModel:
 *   - Sekolah (tenant itu sendiri, bukan entitas yang di-scope)
 *   - User (ada di App\Models, extend Authenticatable)
 *   - SekolahSetting (diakses via relasi Sekolah, bukan langsung)
 *   - Model laravolt/indonesia (Province, City, District, Village)
 *
 * Yang diberikan BaseModel secara otomatis:
 *   1. UUID sebagai primary key (via HasUuids)
 *   2. TenantScope — isolasi data per sekolah (via BelongsToTenant)
 *   3. Auto-fill sekolah_id saat creating (via BelongsToTenant::boot)
 *   4. Proteksi sekolah_id dari perubahan (via BelongsToTenant::boot)
 *   5. Relasi sekolah() (via BelongsToTenant)
 *   6. SoftDeletes — data tidak pernah benar-benar dihapus
 *   7. Timestamps (created_at, updated_at)
 *   8. withoutTenantScope() static helper
 *
 * Contoh model modul yang extend BaseModel:
 *   class SiswaModel extends BaseModel { ... }
 *   class GuruModel extends BaseModel { ... }
 *   class BarangInventarisModel extends BaseModel { ... }
 *
 * Schema minimum tabel:
 *   $table->uuid('id')->primary();
 *   $table->foreignUuid('sekolah_id')->constrained('sekolahs');
 *   $table->timestamps();
 *   $table->softDeletes();
 *   $table->index('sekolah_id');
 */
abstract class BaseModel extends Model implements TenantScopeable
{
    use BelongsToTenant;
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /**
     * Kolom yang TIDAK boleh ada di $fillable child class.
     * sekolah_id diisi otomatis oleh BelongsToTenant::creating hook.
     *
     * Guard ini hanya dokumentasi — enforcement ada di trait boot.
     */
    protected array $tenantGuarded = ['sekolah_id', 'id'];

    /**
     * Cast default yang berlaku di semua model domain.
     * Child class bisa override dengan menambah cast tambahan.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Pastikan child model tidak memasukkan sekolah_id di $fillable.
     * Method ini dipanggil sebelum fill/create untuk memberikan
     * peringatan dini saat development.
     *
     * Di production, auto-fill dari BelongsToTenant tetap berjalan
     * meski sekolah_id tidak ada di $fillable.
     */
    protected static function booted(): void
    {
        parent::booted();

        // Peringatkan developer jika sekolah_id masuk $fillable
        // (akan menyebabkan override dari auto-fill hook)
        if (app()->isLocal() || app()->runningUnitTests()) {
            $instance = new static;
            if (in_array('sekolah_id', $instance->getFillable())) {
                logger()->warning(
                    static::class.': sekolah_id tidak perlu ada di $fillable. '.
                    'Kolom ini diisi otomatis oleh BelongsToTenant.'
                );
            }
        }
    }
}
