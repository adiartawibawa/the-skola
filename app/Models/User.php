<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Src\Core\Models\Sekolah;

/**
 * Model User — Principal Authentication & Authorization
 *
 * User tetap di namespace App\Models (bukan Src\Core\Models) karena
 * Laravel mengharapkan User di lokasi ini untuk berbagai fitur bawaan
 * (Sanctum, Fortify, Auth guards, dll).
 *
 * Integrasi Spatie Permission dengan Teams:
 * - Trait HasRoles dari Spatie otomatis aware terhadap team_id
 *   setelah config permission.php teams = true dikonfigurasi
 * - setPermissionsTeamId() dipanggil di ResolveTenantMiddleware
 *   sehingga setiap role check otomatis scoped ke sekolah_id user
 *
 * Super-Admin detection:
 * - Cek sekolah_id === null, BUKAN cek role 'super-admin'
 * - Role 'super-admin' bisa saja dimiliki admin di level sekolah
 *   untuk keperluan manajemen internal sekolah
 *
 * @property string $id
 * @property string|null $sekolah_id
 * @property string $name
 * @property string $email
 * @property string|null $nip
 * @property string|null $avatar_path
 * @property bool $is_aktif
 * @property Carbon|null $last_login_at
 * @property-read Sekolah|null $sekolah
 * @property-read bool $isSuperAdmin
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasRoles;   // Spatie Permission — harus ada untuk role management
    use HasUuids;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sekolah_id',
        'name',
        'email',
        'password',
        'nip',
        'avatar_path',
        'is_aktif',
        'last_login_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_aktif' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // ── Relasi ─────────────────────────────────────────────────────────

    /**
     * Sekolah tempat user ini terdaftar.
     * Null jika user adalah Super-Admin.
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    // ── Helper Methods ─────────────────────────────────────────────────

    /**
     * Apakah user ini Super-Admin?
     *
     * Gunakan metode ini (bukan cek role) untuk menentukan akses lintas tenant.
     * Super-Admin diidentifikasi HANYA dari sekolah_id = null.
     */
    public function isSuperAdmin(): bool
    {
        return is_null($this->sekolah_id);
    }

    /**
     * Update timestamp last login — dipanggil di LoginController atau Observer.
     */
    public function recordLogin(): void
    {
        $this->forceFill(['last_login_at' => now()])->save();
    }

    // ── Scopes ─────────────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeDiSekolah($query, string $sekolahId)
    {
        return $query->where('sekolah_id', $sekolahId);
    }
}
