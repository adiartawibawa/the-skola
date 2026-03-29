<?php

namespace Src\Core\Shared\Enums\Wilayah;

/**
 * Desa dan kelurahan di Kabupaten Bangli (5106). ~72 desa/kelurahan.
 * Kecamatan Kintamani adalah kecamatan terbesar di Bali
 * dengan ~40 desa mencakup kawasan Gunung Batur dan Danau Batur.
 */
enum DesaKelurahanBangliEnum: string
{
    use HasWilayahMethods;

    // ── SUSUT (510601) ─────────────────────────────────────────────
    case APUAN = '5106012001';
    case DEMULIH = '5106012002';
    case ABUAN = '5106012003';
    case SUSUT = '5106012004';
    case SULAHAN = '5106012005';
    case PENGLUMBARAN = '5106012006';
    case TIGA = '5106012007';
    case SELAT = '5106012008';
    case PENGIANGAN = '5106012009';

    // ── BANGLI (510602) ────────────────────────────────────────────
    case BEBALANG = '5106021003';
    case KAWAN = '5106021004';
    case CEMPAGA = '5106021005';
    case KUBU = '5106021006';
    case BUNUTIN = '5106022001';
    case TAMANBALI = '5106022002';
    case KAYUBIHI = '5106022007';
    case PENGOTAN = '5106022008';
    case LANDIH = '5106022009';

    // ── TEMBUKU (510603) ───────────────────────────────────────────
    case JEHEM = '5106032001';
    case TEMBUKU = '5106032002';
    case YANGAPI = '5106032003';
    case UNDISAN = '5106032004';
    case BANGBANG = '5106032005';
    case PENINJOAN = '5106032006';

    // ── KINTAMANI (510604) ─────────────────────────────────────────
    case MENGANI = '5106042001';
    case BINYAN = '5106042002';
    case ULIAN = '5106042003';
    case BUNUTIN_KINTAMANI = '5106042004'; // Pembeda dari Bunutin Bangli
    case LANGGAHAN = '5106042005';
    case LEMBEAN = '5106042006';
    case MANIKLIYU = '5106042007';
    case BAYUNG_CERIK = '5106042008';
    case MANGGUH = '5106042009';
    case BELANCAN = '5106042010';
    case KATUNG = '5106042011';
    case BANUA = '5106042012';
    case ABUAN_KINTAMANI = '5106042013'; // Pembeda dari Abuan Susut
    case BONYOH = '5106042014';
    case SEKAAN = '5106042015';
    case BAYUNG_GEDE = '5106042016';
    case SEKARDADI = '5106042017';
    case KEDISAN = '5106042018';
    case BUAHAN = '5106042019';
    case ABANGSONGAN = '5106042020';
    case SUTER = '5106042021';
    case ABANG_BATUDINDING = '5106042022';
    case TERUNYAN = '5106042023';
    case SONGAN_A = '5106042024';
    case SONGAN_B = '5106042025';
    case BATUR_SELATAN = '5106042026';
    case BATUR_TENGAH = '5106042027';
    case BATUR_UTARA = '5106042028';
    case KINTAMANI = '5106042029';
    case SERAI = '5106042030';
    case DAUP = '5106042031';
    case AWAN = '5106042032';
    case GUNUNGBAU = '5106042033';
    case BELANGA = '5106042034';
    case BATUKAANG = '5106042035';
    case BELANTIH = '5106042036';
    case CATUR = '5106042037';
    case PENGEJARAN = '5106042038';
    case SELULUNG = '5106042039';
    case SATRA = '5106042040';
    case DAUSA = '5106042041';
    case BANTANG = '5106042042';
    case SUKAWANA = '5106042043';
    case KUTUH = '5106042044';
    case SUBAYA = '5106042045';
    case SIAKIN = '5106042046';
    case PINGGAN = '5106042047';
    case BELANDINGAN = '5106042048';
}
