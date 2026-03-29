<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Core\Shared\Enums\AkreditasiEnum;
use Src\Core\Shared\Enums\JenjangSekolahEnum;
use Src\Core\Shared\Enums\KabupatenKotaEnum;
use Src\Core\Shared\Enums\StatusSekolahEnum;

/**
 * Migration: Tabel sekolahs — Tenant Entity SIAKAD
 *
 * Tabel ini adalah "pusat gravitasi" seluruh sistem multi-tenancy.
 * Setiap sekolah yang terdaftar mendapatkan isolasi data penuh
 * melalui mekanisme sekolah_id di seluruh tabel terkait.
 *
 * Filosofi desain kolom:
 * - Data identitas resmi (NPSN, NSS) → unik, tidak boleh null
 * - Data kontak (telepon, email, website) → nullable karena tidak semua
 *   sekolah di pelosok Bali (Karangasem, Bangli) memiliki email aktif
 * - settings (JSON) → fleksibel untuk konfigurasi per-sekolah
 *   seperti format nilai rapor, logo, warna tema, dll.
 * - tanggal_bergabung → untuk audit kapan sekolah onboarding ke sistem
 *
 * Index strategy:
 * - npsn: UNIQUE — identifier nasional, satu sekolah satu NPSN
 * - kabupaten_kota: INDEX — filter wilayah oleh Super-Admin
 * - jenjang + is_aktif: COMPOSITE — filter dashboard utama
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sekolahs', function (Blueprint $table) {
            // Primary key menggunakan UUID v4
            // Alasan: aman untuk expose di URL, tidak predictable,
            // kompatibel dengan distributed system di masa depan
            $table->uuid('id')->primary();

            // ── Identitas Resmi ───────────────────────────────────────
            // NPSN: Nomor Pokok Sekolah Nasional (8 digit angka)
            // Diterbitkan oleh Kemendikbud, unik secara nasional
            $table->string('npsn', 8)->unique()
                ->comment('Nomor Pokok Sekolah Nasional — 8 digit, dari Kemendikbud');

            // NSS: Nomor Statistik Sekolah (13 digit) — bisa null
            // untuk sekolah yang belum memperbarui data ke Dapodik
            $table->string('nss', 13)->nullable()->unique()
                ->comment('Nomor Statistik Sekolah — 13 digit BPS, nullable');

            $table->string('nama_sekolah', 150)
                ->comment('Nama resmi sesuai dokumen pendirian sekolah');

            // ── Klasifikasi ───────────────────────────────────────────
            $table->enum('jenjang', array_column(JenjangSekolahEnum::cases(), 'value'))
                ->comment('Jenjang pendidikan: SD, SMP, SMA, SMK, SLB');

            $table->enum('status_sekolah', array_column(StatusSekolahEnum::cases(), 'value'))
                ->default(StatusSekolahEnum::NEGERI->value)
                ->comment('Status kepemilikan: negeri atau swasta');

            $table->enum('akreditasi', array_column(AkreditasiEnum::cases(), 'value'))
                ->default(AkreditasiEnum::BELUM->value)
                ->comment('Akreditasi BAN-S/M: A, B, C, Belum, TT');

            // ── Lokasi (konteks Bali) ──────────────────────────────────
            $table->enum('kabupaten_kota', array_column(KabupatenKotaEnum::cases(), 'value'))
                ->comment('Kode BPS Kabupaten/Kota (4 digit), contoh: 5171 = Denpasar');

            // PERUBAHAN: String length diturunkan ke 6 digit sesuai Enum
            $table->string('kecamatan', 6)
                ->comment('Kode BPS kecamatan 6 digit, contoh: 517101 = Denpasar Selatan');

            $table->string('desa_kelurahan', 10)
                ->comment('Kode BPS desa/kelurahan 10 digit, contoh: 5171011001 = Sesetan');

            $table->text('alamat_lengkap')
                ->comment('Alamat jalan lengkap termasuk nomor dan gang');

            $table->string('kode_pos', 5)->nullable()
                ->comment('Kode pos 5 digit — Bali range 80000-82999');

            // ── Kontak ────────────────────────────────────────────────
            $table->string('telepon', 20)->nullable()
                ->comment('Nomor telepon/HP sekolah');

            $table->string('email', 100)->nullable()
                ->comment('Email resmi sekolah — bisa null untuk daerah terpencil');

            $table->string('website', 100)->nullable()
                ->comment('URL website sekolah tanpa trailing slash');

            // ── Aset & Konfigurasi ────────────────────────────────────
            $table->string('logo_path')->nullable()
                ->comment('Path relatif logo sekolah di storage');

            // JSON settings untuk konfigurasi per-tenant yang fleksibel.
            // Contoh isi: {"format_nilai": "angka", "warna_tema": "#2563eb",
            //              "nama_kepsek": "I Wayan Sudarta, S.Pd., M.M.",
            //              "tahun_pelajaran_aktif": "2025/2026"}
            $table->json('settings')->nullable()
                ->comment('Konfigurasi fleksibel per sekolah dalam format JSON');

            // ── Status & Audit ─────────────────────────────────────────
            $table->boolean('is_aktif')->default(true)
                ->comment('False = sekolah dinonaktifkan, tidak bisa login');

            $table->date('tanggal_bergabung')
                ->comment('Tanggal sekolah pertama kali onboarding ke SIAKAD');

            $table->timestamps();
            $table->softDeletes()
                ->comment('Soft delete — data sekolah tidak boleh dihapus permanen');

            // ── Index Strategy ─────────────────────────────────────────
            // Index tunggal untuk filter wilayah (dashboard Super-Admin)
            $table->index('kabupaten_kota', 'idx_sekolahs_kabkota');

            // Composite: filter jenjang + status aktif (query paling sering)
            $table->index(['jenjang', 'is_aktif'], 'idx_sekolahs_jenjang_aktif');

            // Composite: filter wilayah + jenjang (laporan per dinas)
            $table->index(['kabupaten_kota', 'jenjang'], 'idx_sekolahs_kabkota_jenjang');
            $table->index('kecamatan', 'idx_sekolahs_kecamatan');
            $table->index(['kecamatan', 'desa_kelurahan'], 'idx_sekolahs_kec_desa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};
