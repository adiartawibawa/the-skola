<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        $this->call([
            RolePermissionSeeder::class,
            SekolahSeeder::class,
            // Tahap berikutnya — modul-modul akan menambah baris di sini:
            // SiswaSeeder::class,
            // GuruSeeder::class,
        ]);
    }
}
