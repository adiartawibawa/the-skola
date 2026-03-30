<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Laravolt\Indonesia\Seeds\DistrictsSeeder;
use Laravolt\Indonesia\Seeds\ProvincesSeeder;
use Laravolt\Indonesia\Seeds\VillagesSeeder;

/**
 * Entry point semua seeder.
 * Urutan pemanggilan KRITIS — jangan ubah urutannya:
 * 1. RolePermissionSeeder harus SEBELUM SekolahSeeder jika ingin
 *    auto-assign role saat sekolah dibuat
 * 2. SekolahSeeder harus SEBELUM seeder modul (siswa, guru, dll)
 *    karena modul bergantung pada sekolah_id yang valid
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── 1. Data wilayah Indonesia (laravolt) ──────────────────────
        // Jalankan sekali, tidak perlu diulang kecuali ada update data wilayah
        $this->call([
            ProvincesSeeder::class,  // 38 provinsi
            CitiesSeeder::class,     // ~514 kab/kota
            DistrictsSeeder::class,  // ~7.266 kecamatan
            VillagesSeeder::class,   // ~83.762 desa/kelurahan — LAMBAT (~5 menit)
        ]);

        // ── 2. Role & Permission ──────────────────────────────────────
        $this->call(RolePermissionSeeder::class);

        // ── 3. Data Sekolah Sample ────────────────────────────────────
        $this->call(SekolahSeeder::class);

        // ── 4. Modul-modul (diisi sprint berikutnya) ──────────────────
        // $this->call(SiswaSeeder::class);
        // $this->call(GuruSeeder::class);
    }
}
