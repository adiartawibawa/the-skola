<?php

namespace Src\Core\Shared\Enums\Wilayah;

/**
 * Desa dan kelurahan di Kabupaten Gianyar (5104). ~70 desa/kelurahan.
 * Data lengkap — verifikasi urutan sekuensial terhadap BPS.
 */
enum DesaKelurahanGianyarEnum: string
{
    use HasWilayahMethods;

    // ── SUKAWATI (510401) ──────────────────────────────────────────
    case BATUBULAN = '5104012001';
    case KETEWEL = '5104012002';
    case GUWANG = '5104012003';
    case SUKAWATI = '5104012004';
    case CELUK = '5104012005';
    case SINGAPADU = '5104012006';
    case BATUAN = '5104012007';
    case KEMENUH = '5104012008';
    case BATUBULAN_KANGIN = '5104012009';
    case SINGAPADU_TENGAH = '5104012010';
    case SINGAPADU_KALER = '5104012011';
    case BATUAN_KALER = '5104012012';

    // ── BLAHBATUH (510402) ─────────────────────────────────────────
    case SABA = '5104022001';
    case PERING = '5104022002';
    case KERAMAS = '5104022003';
    case BELEGA = '5104022004';
    case BLAHBATUH = '5104022005';
    case BURUAN = '5104022006';
    case BEDULU = '5104022007';
    case MEDAHAN = '5104022008';
    case BONA = '5104022009';

    // ── GIANYAR (510403) ───────────────────────────────────────────
    case SAMPLANGAN = '5104031003';
    case ABIANBASE = '5104031005';
    case GIANYAR = '5104031006';
    case BITERA = '5104031007';
    case BENG = '5104031008';
    case TULIKUP = '5104032001';
    case SIDAN = '5104032002';
    case LEBIH = '5104032004';
    case BAKBAKAN = '5104032009';
    case SIANGAN = '5104032010';
    case SUWAT = '5104032011';
    case PETAK = '5104032012';
    case SERONGGA = '5104032013';
    case PETAK_KAJA = '5104032014';
    case TEMESI = '5104032015';
    case SUMITA = '5104032016';
    case TEGAL_TUGU = '5104032017';

    // ── TAMPAKSIRING (510404) ──────────────────────────────────────
    case PEJENG = '5104042001';
    case SANDING = '5104042002';
    case TAMPAKSIRING = '5104042003';
    case MANUKAYA = '5104042004';
    case PEJENG_KAWAN = '5104042005';
    case PEJENG_KAJA = '5104042006';
    case PEJENG_KANGIN = '5104042007';
    case PEJENG_KELOD = '5104042008';

    // ── UBUD (510405) ──────────────────────────────────────────────
    case UBUD = '5104051005';
    case LODTUNDUH = '5104052001';
    case MAS = '5104052002';
    case SINGAKERTA = '5104052003';
    case KEDEWATAN = '5104052004';
    case PELIATAN = '5104052006';
    case PETULU = '5104052007';
    case SAYAN = '5104052008';

    // ── TEGALLALANG (510406) ───────────────────────────────────────
    case KELIKI = '5104062001';
    case TEGALLALANG = '5104062002';
    case KENDERAN = '5104062003';
    case KEDISAN = '5104062004';
    case PUPUAN = '5104062005';
    case SEBATU = '5104062006';
    case TARO = '5104062007';

    // ── PAYANGAN (510407) ──────────────────────────────────────────
    case MELINGGIH = '5104072001';
    case KELUSA = '5104072002';
    case BUKIAN = '5104072003';
    case PUHU = '5104072004';
    case BUAHAN = '5104072005';
    case KERTA = '5104072006';
    case MELINGGIH_KELOD = '5104072007';
    case BUAHAN_KAJA = '5104072008';
    case BRESELA = '5104072009';
}
