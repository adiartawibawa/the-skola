<?php

namespace Src\Core\Shared\Enums\Wilayah;

/**
 * Desa dan kelurahan di Kabupaten Karangasem (5107). ~75 desa/kelurahan.
 * Kawasan timur Bali — termasuk Gunung Agung, Tirta Gangga, dan Candidasa.
 */
enum DesaKelurahanKarangasemEnum: string
{
    use HasWilayahMethods;

    // ── Rendang (5107010) ────────────────────────────────────────────
    case NONGAN = '5107012001';
    case RENDANG = '5107012002';
    case MENANGA = '5107012003';
    case BESAKIH = '5107012004';
    case PEMPATAN = '5107012005';
    case PESABAN = '5107012006';

    // ── Sidemen (5107020) ────────────────────────────────────────────
    case TANGKUP = '5107022001';
    case TALIBENG = '5107022002';
    case SIDEMEN = '5107022003';
    case SANKAN_GUNUNG = '5107022004';
    case TELAGATAWANG = '5107022005';
    case SINDUWATI = '5107022006';
    case TRI_EKA_BUANA = '5107022007';
    case KERTHA_BUANA = '5107022008';
    case LOKASARI = '5107022009';
    case WISMA_KERTA = '5107022010';

    // ── Manggis (5107030) ────────────────────────────────────────────
    case GEGELANG = '5107032001';
    case ANTIGA = '5107032002';
    case ULAKAN = '5107032003';
    case MANGGIS = '5107032004';
    case NYUH_TEBEL = '5107032005';
    case TENGANAN = '5107032006';
    case NGIS = '5107032007';
    case SELUMBUNG = '5107032008';
    case PADANGBAI = '5107032009';
    case ANTIGA_KELOD = '5107032010';
    case PESEDAHAN = '5107032011';
    case SENGKIDU = '5107032012';

    // ── Karangasem (5107040) ─────────────────────────────────────────
    case SUBAGAN = '5107041002'; // Kelurahan
    case PADANGKERTA = '5107041003'; // Kelurahan
    case KARANGASEM_KELURAHAN = '5107041004'; // Kelurahan
    case BUGBUG = '5107042001';
    case TUMBU = '5107042005';
    case SERAYA = '5107042006';
    case SERAYA_BARAT = '5107042007';
    case SERAYA_TIMUR = '5107042008';
    case PERTIMA = '5107042009';
    case TEGALINGGAH = '5107042010';
    case BUKIT = '5107042011';

    // ── Abang (5107050) ──────────────────────────────────────────────
    case ABABI = '5107052001';
    case TIYINGTALI = '5107052002';
    case BUNUTAN = '5107052003';
    case TISTA = '5107052004';
    case ABANG = '5107052005';
    case PIDPID = '5107052006';
    case DATAH = '5107052007';
    case CULIK = '5107052008';
    case PURWA_KERTHI = '5107052009';
    case KERTHA_MANDALA = '5107052010';
    case LABA_SARI = '5107052011';
    case NAWA_KERTI = '5107052012';
    case KESIMPAR = '5107052013';
    case TRI_BUANA = '5107052014';

    // ── Bebandem (5107060) ───────────────────────────────────────────
    case BUNGAYA = '5107062001';
    case BUDAKELING = '5107062002';
    case BEBANDEM = '5107062003';
    case SIBETAN = '5107062004';
    case JUNGUTAN = '5107062005';
    case BUNGAYA_KANGIN = '5107062006';
    case BHUANA_GIRI = '5107062007';
    case MACANG = '5107062008';

    // ── Selat (5107070) ──────────────────────────────────────────────
    case MUNCAN = '5107072001';
    case SELAT = '5107072002';
    case DUDA = '5107072003';
    case SEBUDI = '5107072004';
    case DUDA_UTARA = '5107072005';
    case DUDA_TIMUR = '5107072006';
    case PERINGSARI = '5107072007';
    case AMERTA_BHUANA = '5107072008';

    // ── Kubu (5107080) ───────────────────────────────────────────────
    case BAN = '5107082001';
    case DUKUH = '5107082002';
    case KUBU = '5107082003';
    case TIANYAR = '5107082004';
    case TIANYAR_BARAT = '5107082005';
    case TIANYAR_TENGAH = '5107082006';
    case TULAMBEN = '5107082007';
    case BATURINGGIT = '5107082008';
    case SUKADANA = '5107082009';
}
