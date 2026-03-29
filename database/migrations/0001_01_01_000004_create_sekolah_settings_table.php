<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel sekolah_settings — Konfigurasi Fleksibel Per Tenant
 *
 * Alternatif dari kolom JSON settings di tabel sekolahs.
 * Tabel terpisah dipilih karena:
 * 1. Setting dapat ditambah tanpa migration baru
 * 2. Mendukung versi/history perubahan setting
 * 3. Lebih mudah di-query untuk satu key spesifik
 * 4. Mendukung grouping (group: 'akademik', 'tampilan', 'notifikasi')
 *
 * Contoh data:
 * | sekolah_id | group     | key                   | value              |
 * |------------|-----------|-----------------------|--------------------|
 * | uuid-smkn1 | akademik  | format_nilai          | angka              |
 * | uuid-smkn1 | akademik  | passing_grade         | 75                 |
 * | uuid-smkn1 | tampilan  | warna_primer          | #1e40af            |
 * | uuid-smkn1 | notifikasi| email_absensi_aktif   | true               |
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sekolah_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('sekolah_id')
                ->constrained('sekolahs')
                ->cascadeOnDelete()
                ->comment('Cascade delete — setting ikut terhapus jika sekolah dihapus permanen');

            $table->string('group', 50)->default('umum')
                ->comment('Kelompok setting: umum, akademik, tampilan, notifikasi');

            $table->string('key', 100)
                ->comment('Kunci unik dalam group, contoh: format_nilai, passing_grade');

            $table->text('value')->nullable()
                ->comment('Nilai setting dalam format string/JSON');

            $table->timestamps();

            // Setiap kombinasi sekolah + group + key harus unik
            $table->unique(['sekolah_id', 'group', 'key'], 'uq_sekolah_settings_key');

            // Filter settings per group (query paling sering)
            $table->index(['sekolah_id', 'group'], 'idx_sekolah_settings_group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolah_settings');
    }
};
