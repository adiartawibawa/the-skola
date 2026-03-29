<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Core\Models\Sekolah;
use Src\Core\Shared\Enums\AkreditasiEnum;
use Src\Core\Shared\Enums\JenjangSekolahEnum;
use Src\Core\Shared\Enums\KabupatenKotaEnum;
use Src\Core\Shared\Enums\StatusSekolahEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanBadungEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanBulelengEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanDenpasarEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanGianyarEnum;
use Src\Core\Shared\Enums\Wilayah\DesaKelurahanKarangasemEnum;

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
            // ── Kota Denpasar (5171) ──────────────────────────────────────────
            [
                'npsn' => '50103491',
                'nss' => '3010226001001',
                'nama_sekolah' => 'SMA Negeri 1 Denpasar',
                'jenjang' => JenjangSekolahEnum::SMA,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::DENPASAR, // 5171
                'kecamatan' => '517104', // Denpasar Utara
                'desa_kelurahan' => DesaKelurahanDenpasarEnum::DANGIN_PURI_KANGIN->value, // 5171042001
                'alamat_lengkap' => 'Jl. Kamboja No. 4, Denpasar',
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
            [
                'npsn' => '50103496',
                'nss' => '4220226001002',
                'nama_sekolah' => 'SMK Negeri 1 Denpasar',
                'jenjang' => JenjangSekolahEnum::SMK,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::DENPASAR,
                'kecamatan' => '517104', // Denpasar Utara
                'desa_kelurahan' => DesaKelurahanDenpasarEnum::UBUNG_KAJA->value, // 5171042008
                'alamat_lengkap' => 'Jl. Hos. Cokroaminoto No. 84, Denpasar',
                'kode_pos' => '80116',
                'telepon' => '0361-422401',
                'email' => 'smkn1dps@example.bali.id',
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-01-20',
            ],

            // ── Kabupaten Badung (5103) ───────────────────────────────────────
            [
                'npsn' => '50103502',
                'nss' => '3010203001003',
                'nama_sekolah' => 'SMP Negeri 3 Mangupura',
                'jenjang' => JenjangSekolahEnum::SMP,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::BADUNG, // 5103
                'kecamatan' => '510304', // Mengwi
                'desa_kelurahan' => DesaKelurahanBadungEnum::MENGWI->value, // 5103040001
                'alamat_lengkap' => 'Jl. Raya Mengwi No. 12, Badung',
                'kode_pos' => '80351',
                'telepon' => '0361-8944101',
                'email' => 'smpn3badung@example.bali.id',
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-02-01',
            ],

            // ── Kabupaten Gianyar (5104) ──────────────────────────────────────
            [
                'npsn' => '50103610',
                'nss' => '1010204001004',
                'nama_sekolah' => 'SD Negeri 1 Gianyar',
                'jenjang' => JenjangSekolahEnum::SD,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::GIANYAR, // 5104
                'kecamatan' => '510401', // Sukawati
                'desa_kelurahan' => DesaKelurahanGianyarEnum::SUKAWATI->value, // 5104010001
                'alamat_lengkap' => 'Jl. Ngurah Rai No. 5, Gianyar',
                'kode_pos' => '80511',
                'telepon' => '0361-943071',
                'email' => 'sdn1gianyar@example.bali.id',
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-02-15',
            ],

            // ── Kabupaten Buleleng (5108) ─────────────────────────────────────
            [
                'npsn' => '50103750',
                'nss' => '3010208001005',
                'nama_sekolah' => 'SMA Negeri 1 Singaraja',
                'jenjang' => JenjangSekolahEnum::SMA,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::A,
                'kabupaten_kota' => KabupatenKotaEnum::BULELENG, // 5108
                'kecamatan' => '510806', // Buleleng
                'desa_kelurahan' => DesaKelurahanBulelengEnum::BANJAR_JAWA->value, // 5108060002
                'alamat_lengkap' => 'Jl. Pramuka No. 4, Singaraja',
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

            // ── Kabupaten Karangasem (5107) ───────────────────────────────────
            [
                'npsn' => '50103890',
                'nss' => '3010207001006',
                'nama_sekolah' => 'SMP Negeri 1 Amlapura',
                'jenjang' => JenjangSekolahEnum::SMP,
                'status_sekolah' => StatusSekolahEnum::NEGERI,
                'akreditasi' => AkreditasiEnum::B,
                'kabupaten_kota' => KabupatenKotaEnum::KARANGASEM, // 5107
                'kecamatan' => '510704', // Karangasem
                'desa_kelurahan' => DesaKelurahanKarangasemEnum::KARANGASEM_KELURAHAN->value, // 5107040001
                'alamat_lengkap' => 'Jl. Ngurah Rai No. 9, Amlapura',
                'kode_pos' => '80811',
                'telepon' => '0363-21067',
                'email' => null,
                'is_aktif' => true,
                'tanggal_bergabung' => '2024-03-15',
            ],
        ];

        foreach ($sekolahs as $data) {
            Sekolah::updateOrCreate(['npsn' => $data['npsn']], $data);
        }

        $this->command->info('Seeder SKOLA berhasil: '.count($sekolahs).' sekolah di Bali telah disinkronkan.');
    }
}
