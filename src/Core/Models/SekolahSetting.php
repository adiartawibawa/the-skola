<?php

namespace Src\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model SekolahSetting — Konfigurasi Per Tenant
 *
 * Model ini TIDAK extend BaseModel karena setting diakses
 * melalui relasi dari Sekolah — isolasi sudah terjamin.
 *
 * Cara penggunaan yang benar:
 *   $sekolah->getSetting('akademik', 'format_nilai')  ← via relasi (BENAR)
 *   SekolahSetting::where('key', '...')->get()        ← tanpa scope (SALAH)
 */
class SekolahSetting extends Model
{
    use HasUuids;

    protected $table = 'sekolah_settings';

    protected $fillable = [
        'sekolah_id',
        'group',
        'key',
        'value',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
