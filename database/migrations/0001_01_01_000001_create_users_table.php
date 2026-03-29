<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Modifikasi tabel users untuk multi-tenancy
 *
 * Tabel users adalah Laravel default — kita TIDAK membuat ulang,
 * hanya menambahkan kolom yang dibutuhkan sistem SIAKAD.
 *
 * Keputusan desain krusial:
 *
 * 1. sekolah_id NULLABLE:
 *    - NULL = Super-Admin (lintas tenant, tidak terikat satu sekolah)
 *    - UUID = User sekolah biasa (terikat satu sekolah)
 *    Ini adalah konvensi inti seluruh sistem — jangan ubah.
 *
 * 2. email UNIQUE tetap dipertahankan (default Laravel):
 *    Email unik secara global, bukan per sekolah. Artinya satu
 *    orang tidak bisa punya dua akun di sekolah berbeda dengan
 *    email yang sama. Ini disengaja untuk keamanan.
 *    Jika bisnis requirement berubah, ubah ke UNIQUE(sekolah_id, email).
 *
 * 3. nip (Nomor Induk Pegawai):
 *    Nullable karena siswa dan orang tua tidak memiliki NIP.
 *    Unique per sekolah (composite) karena NIP guru bisa saja
 *    terdaftar di lebih dari satu sekolah swasta.
 *
 * 4. UUID untuk id:
 *    Ganti bigIncrements default Laravel ke UUID.
 *    Perlu drop dan recreate karena Laravel tidak bisa alter PK.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Relasi ke tenant — ini adalah kolom paling penting
            // constrained() menambahkan FK otomatis ke sekolahs.id
            // nullOnDelete() = jika sekolah dihapus (soft delete tidak terhitung),
            // kita TIDAK ingin user ikut terhapus — lebih aman set null
            $table->foreignUuid('sekolah_id')
                ->nullable()
                ->constrained('sekolahs')
                ->nullOnDelete()
                ->comment('NULL = Super-Admin. UUID = terikat ke satu sekolah');

            // ── Identitas ──────────────────────────────────────────────
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // ── Data Kepegawaian ───────────────────────────────────────
            // NIP: 18 digit (ASN) atau kosong (guru honorer/swasta/siswa)
            $table->string('nip', 18)->nullable()
                ->comment('Nomor Induk Pegawai ASN — 18 digit, nullable untuk non-ASN');

            $table->string('avatar_path')->nullable()
                ->comment('Path relatif foto profil di storage');

            // ── Status & Audit ─────────────────────────────────────────
            $table->boolean('is_aktif')->default(true)
                ->comment('False = akun diblokir, tidak bisa login');

            $table->timestamp('last_login_at')->nullable()
                ->comment('Timestamp login terakhir untuk audit keamanan');

            $table->timestamps();
            $table->softDeletes();

            // ── Index Strategy ─────────────────────────────────────────
            // Index pada sekolah_id — query paling sering dieksekusi
            // (setiap request user yang login akan query berdasarkan ini)
            $table->index('sekolah_id', 'idx_users_sekolah_id');

            // Composite: isolasi NIP per sekolah
            // Memungkinkan NIP yang sama terdaftar di dua sekolah berbeda
            $table->unique(['sekolah_id', 'nip'], 'uq_users_sekolah_nip');

            // Filter user aktif per sekolah (dashboard admin sekolah)
            $table->index(['sekolah_id', 'is_aktif'], 'idx_users_sekolah_aktif');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
