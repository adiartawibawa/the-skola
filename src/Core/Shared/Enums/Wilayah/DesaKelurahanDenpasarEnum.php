<?php

namespace Src\Core\Shared\Enums\Wilayah;

/**
 * 43 kelurahan di Kota Denpasar (5171).
 * Kota Denpasar hanya memiliki kelurahan, tidak ada desa.
 *
 * Distribusi per kecamatan:
 *   Denpasar Selatan (5171010) — 10 kelurahan
 *   Denpasar Timur   (5171020) — 11 kelurahan
 *   Denpasar Barat   (5171030) — 11 kelurahan
 *   Denpasar Utara   (5171040) — 11 kelurahan
 */
enum DesaKelurahanDenpasarEnum: string
{
    use HasWilayahMethods;

    // ── Denpasar Selatan (517101) ────────────────────────────────────
    case SERANGAN = '5171011001'; // Kelurahan
    case PEDUNGAN = '5171011002'; // Kelurahan
    case SESETAN = '5171011003'; // Kelurahan
    case PANJER = '5171011004'; // Kelurahan
    case RENON = '5171011005'; // Kelurahan
    case SANUR = '5171011006'; // Kelurahan
    case SIDAKARYA = '5171012007';
    case PEMOGAN = '5171012008';
    case SANUR_KAJA = '5171012009';
    case SANUR_KAUH = '5171012010';

    // ── Denpasar Timur (517102) ──────────────────────────────────────
    case KESIMAN = '5171021003'; // Kelurahan
    case SUMERTA = '5171021006'; // Kelurahan
    case DANGIN_PURI = '5171021010'; // Kelurahan
    case PENATIH = '5171021014'; // Kelurahan
    case DANGIN_PURI_KELOD = '5171022001';
    case SUMERTA_KELOD = '5171022002';
    case KESIMAN_PETILAN = '5171022004';
    case KESIMAN_KERTALANGU = '5171022005';
    case SUMERTA_KAJA = '5171022007';
    case SUMERTA_KAUH = '5171022008';
    case PENATIH_DANGIN_PURI = '5171022015';

    // ── Denpasar Barat (517103) ──────────────────────────────────────
    case DAUH_PURI = '5171031005'; // Kelurahan
    case PEMECUTAN = '5171031007'; // Kelurahan
    case PADANGSAMBIAN = '5171031010'; // Kelurahan
    case PADANGSAMBIAN_KELOD = '5171032001';
    case PEMECUTAN_KELOD = '5171032002';
    case DAUH_PURI_KAUH = '5171032003';
    case DAUH_PURI_KELOD = '5171032004';
    case DAUH_PURI_KANGIN = '5171032006';
    case TEGAL_HARUM = '5171032008';
    case TEGAL_KERTHA = '5171032009';
    case PADANG_SAMBIAN_KAJA = '5171032011';

    // ── Denpasar Utara (517104) ──────────────────────────────────────
    case TONJA = '5171041004'; // Kelurahan
    case UBUNG = '5171041007'; // Kelurahan
    case PEGUYANGAN = '5171041009'; // Kelurahan
    case DANGIN_PURI_KANGIN = '5171042001';
    case DANGIN_PURI_KAUH = '5171042002';
    case DANGIN_PURI_KAJA = '5171042003';
    case PEMECUTAN_KAJA = '5171042005';
    case DAUH_PURI_KAJA = '5171042006';
    case UBUNG_KAJA = '5171042008';
    case PEGUYANGAN_KAJA = '5171042010';
    case PEGUYANGAN_KANGIN = '5171042011';
}
