<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;
use Src\Core\Models\Sekolah;

/**
 * Seeder: Role, Permission, dan User Awal
 *
 * Strategi permission mengikuti pola: {modul}.{resource}.{aksi}
 * Contoh: akademik.siswa.create, kepegawaian.guru.edit
 *
 * Hierarki role di level sekolah:
 * 1. kepala-sekolah    → bisa lihat semua laporan, tidak bisa hapus data
 * 2. wakasek-akademik  → kelola data akademik
 * 3. wakasek-kesiswaan → kelola data siswa & kesiswaan
 * 4. guru              → input nilai & absensi kelas yang diajar
 * 5. staf-tata-usaha   → data administratif sekolah
 * 6. operator-dapodik  → sinkronisasi data ke Dapodik Kemendikbud
 *
 * Role Super-Admin tidak dibuat via Spatie (bypass via Gate::before)
 * karena sekolah_id = null sudah cukup sebagai identifier.
 */
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions sebelum seeding
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Definisi Permission ────────────────────────────────────────
        $permissions = [
            // Manajemen Sekolah (hanya Super-Admin)
            'sekolah.view-any', 'sekolah.create', 'sekolah.edit', 'sekolah.delete',
            'sekolah.settings.edit',

            // Akademik — Siswa
            'akademik.siswa.view-any', 'akademik.siswa.view',
            'akademik.siswa.create', 'akademik.siswa.edit', 'akademik.siswa.delete',

            // Akademik — Nilai
            'akademik.nilai.view-any', 'akademik.nilai.view',
            'akademik.nilai.create', 'akademik.nilai.edit', 'akademik.nilai.delete',

            // Akademik — Absensi
            'akademik.absensi.view-any', 'akademik.absensi.create', 'akademik.absensi.edit',

            // Akademik — Jadwal
            'akademik.jadwal.view-any', 'akademik.jadwal.create', 'akademik.jadwal.edit',

            // Kepegawaian — Guru & Staf
            'kepegawaian.guru.view-any', 'kepegawaian.guru.create',
            'kepegawaian.guru.edit', 'kepegawaian.guru.delete',

            // Inventaris
            'inventaris.barang.view-any', 'inventaris.barang.create',
            'inventaris.barang.edit', 'inventaris.barang.delete',

            // Laporan
            'laporan.akademik.view', 'laporan.kepegawaian.view', 'laporan.inventaris.view',

            // Manajemen User (dalam sekolah)
            'user.view-any', 'user.create', 'user.edit', 'user.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ── Definisi Role (tanpa team untuk tahap ini) ─────────────────
        // Role akan di-assign ke user dengan team context (sekolah_id)
        // melalui setPermissionsTeamId() di middleware

        $roles = [
            'kepala-sekolah' => [
                'akademik.siswa.view-any', 'akademik.siswa.view',
                'akademik.nilai.view-any', 'akademik.nilai.view',
                'akademik.absensi.view-any',
                'kepegawaian.guru.view-any',
                'inventaris.barang.view-any',
                'laporan.akademik.view', 'laporan.kepegawaian.view', 'laporan.inventaris.view',
            ],
            'wakasek-akademik' => [
                'akademik.siswa.view-any', 'akademik.siswa.view',
                'akademik.siswa.create', 'akademik.siswa.edit',
                'akademik.nilai.view-any', 'akademik.nilai.create', 'akademik.nilai.edit',
                'akademik.absensi.view-any', 'akademik.absensi.create',
                'akademik.jadwal.view-any', 'akademik.jadwal.create', 'akademik.jadwal.edit',
                'laporan.akademik.view',
            ],
            'guru' => [
                'akademik.siswa.view-any', 'akademik.siswa.view',
                'akademik.nilai.view-any', 'akademik.nilai.create', 'akademik.nilai.edit',
                'akademik.absensi.create', 'akademik.absensi.edit',
                'akademik.jadwal.view-any',
            ],
            'staf-tata-usaha' => [
                'akademik.siswa.view-any', 'akademik.siswa.view',
                'akademik.siswa.create', 'akademik.siswa.edit',
                'kepegawaian.guru.view-any',
                'inventaris.barang.view-any', 'inventaris.barang.create',
                'user.view-any', 'user.create', 'user.edit',
            ],
            'operator-dapodik' => [
                'akademik.siswa.view-any', 'akademik.siswa.view',
                'kepegawaian.guru.view-any',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }

        // ── Buat Super-Admin ───────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'superadmin@siakad.bali.id'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('P@ssw0rd!Bali2025'),
                'sekolah_id' => null, // null = Super-Admin
                'is_aktif' => true,
                'tanggal_bergabung' => now(),
            ]
        );

        // ── Buat Admin Demo per Sekolah ────────────────────────────────
        $sekolahSample = Sekolah::first();

        if ($sekolahSample) {
            $adminSekolah = User::firstOrCreate(
                ['email' => 'admin@sman1denpasar.demo'],
                [
                    'name' => 'I Wayan Admin Demo',
                    'password' => Hash::make('Demo@2025'),
                    'sekolah_id' => $sekolahSample->id,
                    'nip' => '198501012010011001',
                    'is_aktif' => true,
                ]
            );

            // Assign role dalam konteks team (sekolah)
            setPermissionsTeamId($sekolahSample->id);
            $adminSekolah->assignRole('wakasek-akademik');
        }

        $this->command->info('Seeder role & permission berhasil dibuat.');
        $this->command->table(
            ['Email', 'Password', 'Role'],
            [
                ['superadmin@siakad.bali.id', 'P@ssw0rd!Bali2025', 'Super-Admin (bypass)'],
                ['admin@sman1denpasar.demo',  'Demo@2025',          'wakasek-akademik'],
            ]
        );
    }
}
