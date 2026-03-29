<?php

// src/Core/Shared/Enums/Wilayah/DesaKelurahanBadungEnum.php

namespace Src\Core\Shared\Enums\Wilayah;

/**
 * Desa dan kelurahan di Kabupaten Badung (5103).
 *
 * Distribusi per kecamatan:
 *   Kuta Selatan (5103010) —  6 desa
 *   Kuta         (5103020) —  4 kelurahan
 *   Kuta Utara   (5103030) —  6 desa/kelurahan
 *   Mengwi       (5103040) — 15 desa
 *   Abiansemal   (5103050) — 14 desa
 *   Petang       (5103060) —  7 desa
 */
enum DesaKelurahanBadungEnum: string
{
    use HasWilayahMethods;

    // ── KUTA (510301) ───────────────────────────────────────────────
    case TUBAN = '5103011001';
    case KUTA = '5103011002';
    case KEDONGANAN = '5103011003';
    case LEGIAN = '5103011004';
    case SEMINYAK = '5103011005';

    // ── MENGWI (510302) ─────────────────────────────────────────────
    case KAPAL = '5103021004';
    case SEMPIDI = '5103021005';
    case ABIANBASE = '5103021014';
    case SADING = '5103021015';
    case LUKLUK = '5103021016';
    case MUNGGU = '5103022001';
    case BUDUK = '5103022002';
    case MENGWITANI = '5103022003';
    case PENARUNGAN = '5103022006';
    case SEMBUNG = '5103022007';
    case BAHA = '5103022008';
    case MENGWI = '5103022009';
    case KEKERAN = '5103022010';
    case SOBANGAN = '5103022011';
    case GULINGAN = '5103022012';
    case WERDI_BHUWANA = '5103022013';
    case CEMAGI = '5103022017';
    case PERERENAN = '5103022018';
    case TUMBAKBAYUH = '5103022019';
    case KUWUM = '5103022020';

    // ── ABIANSEMAL (510303) ─────────────────────────────────────────
    case DARMASABA = '5103032001';
    case SIBANGKAJA = '5103032002';
    case SIBANGGEDE = '5103032003';
    case JAGAPATI = '5103032004';
    case ANGANTAKA = '5103032005';
    case SEDANG = '5103032006';
    case MAMBAL = '5103032007';
    case ABIANSEMAL = '5103032008';
    case BONGKASA = '5103032009';
    case TAMAN = '5103032010';
    case BLAHKIUH = '5103032011';
    case AYUNAN = '5103032012';
    case SANGEH = '5103032013';
    case PUNGGUL = '5103032014';
    case MEKAR_BHUWANA = '5103032015';
    case ABIANSEMAL_DAUH_YEH_CANI = '5103032016';
    case SELAT = '5103032017';
    case BONGKASA_PERTIWI = '5103032018';

    // ── PETANG (510304) ─────────────────────────────────────────────
    case CARANGSARI = '5103042001';
    case PETANG = '5103042002';
    case BELOK_SIDAN = '5103042003';
    case PELAGA = '5103042004';
    case GETASAN = '5103042005';
    case PANGSAN = '5103042006';
    case SULANGAI = '5103042007';

    // ── KUTA SELATAN (510305) ───────────────────────────────────────
    case BENOA = '5103051004';
    case TANJUNG_BENOA = '5103051005';
    case JIMBARAN = '5103051006';
    case PECATU = '5103052001';
    case UNGASAN = '5103052002';
    case KUTUH = '5103052003';

    // ── KUTA UTARA (510306) ─────────────────────────────────────────
    case KEROBOKAN_KELOD = '5103061001';
    case KEROBOKAN = '5103061002';
    case KEROBOKAN_KAJA = '5103061003';
    case TIBUBENENG = '5103062004';
    case CANGGU = '5103062005';
    case DALUNG = '5103062006';
}
