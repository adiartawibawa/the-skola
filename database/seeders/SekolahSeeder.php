<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Core\Models\Sekolah;
use Src\Core\Shared\Enums\AkreditasiEnum;
use Src\Core\Shared\Enums\JenjangSekolahEnum;
use Src\Core\Shared\Enums\KabupatenKotaEnum;
use Src\Core\Shared\Enums\StatusSekolahEnum;

/**
 * Seeder: Data sample sekolah-sekolah di Bali
 *
 * Data ini merepresentasikan sekolah nyata di Bali untuk keperluan
 * development dan demonstration. Data kontak adalah fiktif.
 *
 * Tujuan seeder ini:
 * 1. Menyediakan data realistic untuk testing multi-tenant isolation
 * 2. Mencakup berbagai kombinasi jenjang, status, dan wilayah
 * 3. Mencakup semua 9 kabupaten/kota di Bali
 *
 * Jalankan dengan: php artisan db:seed --class=SekolahSeeder
 */
class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        $sekolahs = [
            // ── Kota Denpasar — SMAN 1 ────────────────────────────────
            [
                'npsn' => '50103491',
                'nss' => '3010226001001',
                'nama_sekolah' => 'Negeri 1 Denpasar',
                'jenjang' => JenjangSekolahEnum::SMA,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::DENPASAR->value, // '5171'
                'kecamatan' => '5171020',  // Kec. Denpasar Timur
                'desa_kelurahan' => '5171020002', // Kel. Dangin Puri Kangin
                'alamat_lengkap' => 'Jl. Kamboja No. 4',
                'kode_pos' => '80233',
                'telepon' => '0361-222605',
                'email' => 'sman1dps@example.bali.id',
                'website' => 'https://sman1denpasar.sch.id',
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-01-15',
                'settings' => [
                    'format_nilai' => 'angka',
                    'passing_grade' => 75,
                    'tahun_pelajaran' => '2024/2025',
                    'warna_tema' => '#1e40af',
                ],
            ],

            // ── Kota Denpasar — SMKN 1 ────────────────────────────────
            [
                'npsn' => '50103496',
                'nss' => '4220226001002',
                'nama_sekolah' => 'Negeri 1 Denpasar',
                'jenjang' => JenjangSekolahEnum::SMK,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::DENPASAR->value, // '5171'
                'kecamatan' => '5171030',  // Kec. Denpasar Barat
                'desa_kelurahan' => '5171030007', // Kel. Pemecutan
                'alamat_lengkap' => 'Jl. Hos. Cokroaminoto No. 84',
                'kode_pos' => '80116',
                'telepon' => '0361-422401',
                'email' => 'smkn1dps@example.bali.id',
                'website' => null,
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-01-20',
                'settings' => null,
            ],

            // ── Kabupaten Badung — SMPN 3 Mengwi ──────────────────────
            [
                'npsn' => '50103502',
                'nss' => '3010203001003',
                'nama_sekolah' => 'Negeri 3 Mengwi',
                'jenjang' => JenjangSekolahEnum::SMP,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::BADUNG->value, // '5103'
                'kecamatan' => '5103040',  // Kec. Mengwi
                'desa_kelurahan' => '5103040001', // Desa Mengwi
                'alamat_lengkap' => 'Jl. Raya Mengwi No. 12',
                'kode_pos' => '80351',
                'telepon' => '0361-8944101',
                'email' => 'smpn3badung@example.bali.id',
                'website' => null,
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-02-01',
                'settings' => null,
            ],

            // ── Kabupaten Gianyar — SDN 1 Gianyar ─────────────────────
            [
                'npsn' => '50103610',
                'nss' => '1010204001004',
                'nama_sekolah' => 'Negeri 1 Gianyar',
                'jenjang' => JenjangSekolahEnum::SD,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::GIANYAR->value, // '5104'
                'kecamatan' => '5104030',  // Kec. Gianyar
                'desa_kelurahan' => '5104030001', // Kel. Gianyar
                'alamat_lengkap' => 'Jl. Ngurah Rai No. 5',
                'kode_pos' => '80511',
                'telepon' => '0361-943071',
                'email' => 'sdn1gianyar@example.bali.id',
                'website' => null,
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-02-15',
                'settings' => null,
            ],

            // ── Kabupaten Buleleng — SMAN 1 Singaraja ─────────────────
            [
                'npsn' => '50103750',
                'nss' => '3010208001005',
                'nama_sekolah' => 'Negeri 1 Singaraja',
                'jenjang' => JenjangSekolahEnum::SMA,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::BULELENG->value, // '5108'
                'kecamatan' => '5108060',  // Kec. Buleleng
                'desa_kelurahan' => '5108060002', // Kel. Banjar Jawa
                'alamat_lengkap' => 'Jl. Pramuka No. 4',
                'kode_pos' => '81117',
                'telepon' => '0362-22441',
                'email' => 'sman1singaraja@example.bali.id',
                'website' => 'https://sman1singaraja.sch.id',
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-03-01',
                'settings' => [
                    'format_nilai' => 'angka',
                    'passing_grade' => 70,
                    'tahun_pelajaran' => '2024/2025',
                    'warna_tema' => '#065f46',
                ],
            ],

            // ── Kabupaten Karangasem — SMPN 1 Amlapura ────────────────
            [
                'npsn' => '50103890',
                'nss' => '3010207001006',
                'nama_sekolah' => 'Negeri 1 Amlapura',
                'jenjang' => JenjangSekolahEnum::SMP,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::B,
                'kabupaten_kota' => KabupatenKotaEnum::KARANGASEM->value, // '5107'
                'kecamatan' => '5107040',  // Kec. Karangasem
                'desa_kelurahan' => '5107040001', // Kel. Karangasem
                'alamat_lengkap' => 'Jl. Ngurah Rai No. 9',
                'kode_pos' => '80811',
                'telepon' => '0363-21067',
                'email' => null,
                'website' => null,
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-03-15',
                'settings' => null,
            ],

            // ── Kota Denpasar — SLBN 1 Bali ───────────────────────────
            [
                'npsn' => '50104012',
                'nss' => null,
                'nama_sekolah' => 'Negeri 1 Bali',
                'jenjang' => JenjangSekolahEnum::SLB,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::DENPASAR->value, // '5171'
                'kecamatan' => '5171010',  // Kec. Denpasar Selatan
                'desa_kelurahan' => '5171010004', // Kel. Panjer
                'alamat_lengkap' => 'Jl. Seroja Gang IV No. 9',
                'kode_pos' => '80225',
                'telepon' => '0361-224745',
                'email' => 'slbn1bali@example.bali.id',
                'website' => null,
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-04-01',
                'settings' => null,
            ],
        ];

        foreach ($sekolahs as $data) {
            Sekolah::create($data);
        }

        $this->command->info('Seeder sekolahs: '.count($sekolahs).' sekolah berhasil ditambahkan.');
    }
}
