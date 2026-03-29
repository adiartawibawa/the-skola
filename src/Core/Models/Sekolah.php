<?php

// src/Core/Models/Sekolah.php

namespace Src\Core\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Src\Core\Shared\Enums\AkreditasiEnum;
use Src\Core\Shared\Enums\JenjangSekolahEnum;
use Src\Core\Shared\Enums\KabupatenKotaEnum;
use Src\Core\Shared\Enums\StatusSekolahEnum;

/**
 * Model Sekolah — Tenant Entity
 *
 * PENTING: Model ini TIDAK extend BaseModel karena Sekolah adalah
 * tenant itu sendiri, bukan entitas yang perlu di-scope per tenant.
 * Sekolah extend langsung dari Illuminate\Database\Eloquent\Model.
 *
 * Tanggung jawab model ini:
 * 1. Merepresentasikan satu sekolah sebagai tenant
 * 2. Menyediakan akses ke relasi users (member sekolah)
 * 3. Menyediakan akses ke settings sekolah
 * 4. Digunakan oleh Filament sebagai tenant model
 *
 * Casting otomatis ke PHP Enum mencegah bug typo pada nilai
 * dan memberikan autocomplete di IDE.
 *
 * @property string $id
 * @property string $npsn
 * @property string|null $nss
 * @property string $nama_sekolah
 * @property JenjangSekolahEnum $jenjang
 * @property StatusSekolahEnum $status_sekolah
 * @property AkreditasiEnum $akreditasi
 * @property KabupatenKotaEnum $kabupaten_kota
 * @property string $kecamatan
 * @property string $desa_kelurahan
 * @property string $alamat_lengkap
 * @property string|null $kode_pos
 * @property string|null $telepon
 * @property string|null $email
 * @property string|null $website
 * @property string|null $logo_path
 * @property array|null $settings
 * @property bool $is_aktif
 * @property Carbon $tanggal_bergabung
 */
class Sekolah extends Model
{
    use HasFactory;
    use HasUuids;   // Auto-generate UUID v4 saat create
    use SoftDeletes;

    protected $table = 'sekolahs';

    protected $fillable = [
        'npsn',
        'nss',
        'nama_sekolah',
        'jenjang',
        'status_sekolah',
        'akreditasi',
        'kabupaten_kota',
        'kecamatan',
        'desa_kelurahan',
        'alamat_lengkap',
        'kode_pos',
        'telepon',
        'email',
        'website',
        'logo_path',
        'settings',
        'is_aktif',
        'tanggal_bergabung',
    ];

    /**
     * Casting otomatis ke tipe PHP yang tepat.
     *
     * Enum casting memastikan nilai selalu valid — jika ada nilai
     * tidak dikenal di database, Eloquent akan throw ValueError
     * yang mudah di-debug dibanding bug silent string mismatch.
     */
    protected $casts = [
        'jenjang' => JenjangSekolahEnum::class,
        'status_sekolah' => StatusSekolahEnum::class,
        'akreditasi' => AkreditasiEnum::class,
        'kabupaten_kota' => KabupatenKotaEnum::class,
        'settings' => 'array',
        'is_aktif' => 'boolean',
        'tanggal_bergabung' => 'date',
    ];

    // ── Relasi ─────────────────────────────────────────────────────────

    /**
     * Semua user yang terdaftar di sekolah ini.
     * Termasuk admin, guru, staf — semua role dalam satu sekolah.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'sekolah_id');
    }

    /**
     * Konfigurasi spesifik sekolah ini.
     */
    public function settings(): HasMany
    {
        return $this->hasMany(SekolahSetting::class, 'sekolah_id');
    }

    // ── Accessors ──────────────────────────────────────────────────────

    /**
     * Nama lengkap untuk ditampilkan di UI, termasuk jenjang.
     * Contoh: "SMA Negeri 1 Denpasar"
     */
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->jenjang->value} {$this->nama_sekolah}";
    }

    /**
     * Ambil satu nilai setting berdasarkan key.
     * Contoh: $sekolah->getSetting('akademik', 'format_nilai', 'angka')
     */
    public function getSetting(string $group, string $key, mixed $default = null): mixed
    {
        return $this->settings()
            ->where('group', $group)
            ->where('key', $key)
            ->value('value') ?? $default;
    }

    // ── Scopes ─────────────────────────────────────────────────────────

    /** Filter sekolah aktif — digunakan di dashboard Super-Admin */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    /** Filter berdasarkan kabupaten/kota */
    public function scopeKabupaten($query, KabupatenKotaEnum $kabupaten)
    {
        return $query->where('kabupaten_kota', $kabupaten->value);
    }

    /** Filter berdasarkan jenjang */
    public function scopeJenjang($query, JenjangSekolahEnum $jenjang)
    {
        return $query->where('jenjang', $jenjang->value);
    }

    // ── Filament Interface ──────────────────────────────────────────────

    /**
     * Label yang ditampilkan Filament di panel tenant selector.
     * Dipanggil oleh Filament secara otomatis saat render tenant switcher.
     */
    public function getTenantName(): string
    {
        return $this->nama_lengkap;
    }

    /**
     * Slug URL-friendly untuk tenant — digunakan jika Filament butuh identifier di URL.
     */
    public function getTenantSlug(): string
    {
        return str($this->nama_sekolah)->slug()->value();
    }
}
