<?php

namespace Src\Core\Shared\Enums\Wilayah;

/**
 * Desa dan kelurahan di Kabupaten Tabanan (5102).
 * Total: 133 wilayah (Kelurahan & Desa).
 * Data disinkronkan dengan BPS.
 */
enum DesaKelurahanTabananEnum: string
{
    use HasWilayahMethods;

    // ── SELEMADEG (510201) ──────────────────────────────────────────
    case BAJERA = '5102012001';
    case WANAGIRI = '5102012002';
    case PUPUAN_SAWAH = '5102012003';
    case BEREMBENG = '5102012004';
    case SELEMADEG = '5102012005';
    case SERAMPINGAN = '5102012006';
    case ANTAP = '5102012007';
    case WANAGIRI_KAUH = '5102012008';
    case MANIKYANG = '5102012009';
    case BAJERA_UTARA = '5102012010';

    // ── SELEMADEG TIMUR (510202) ────────────────────────────────────
    case GUNUNG_SALAK = '5102022001';
    case GADUNGAN = '5102022002';
    case BANTAS = '5102022003';
    case MAMBANG = '5102022004';
    case MEGATI = '5102022005';
    case TANGGUNTITI = '5102022006';
    case BERABAN_SELEMADEG_TIMUR = '5102022007';
    case TEGAL_MENGKEB = '5102022008';
    case DALANG = '5102022009';
    case GADUNGSARI = '5102022010';

    // ── SELEMADEG BARAT (510203) ────────────────────────────────────
    case MUNDEH = '5102032001';
    case MUNDEH_KANGIN = '5102032002';
    case LALANGLINGGAH = '5102032003';
    case ANTOSARI = '5102032004';
    case LUMBUNG = '5102032005';
    case LUMBUNG_KAUH = '5102032006';
    case TIYING_GADING = '5102032007';
    case MUNDEH_KAUH = '5102032008';
    case ANGKAH = '5102032009';
    case SELABIH = '5102032010';
    case BENGKEL_SARI = '5102032011';

    // ── KERAMBITAN (510204) ─────────────────────────────────────────
    case TIBUBIU = '5102042001';
    case KELATING = '5102042002';
    case PENARUKAN = '5102042003';
    case BELUMBANG = '5102042004';
    case TISTA = '5102042005';
    case KERAMBITAN = '5102042006';
    case PANGKUNG_KARUNG = '5102042007';
    case SAMSAM = '5102042008';
    case KUKUH_KERAMBITAN = '5102042009';
    case BATURITI_KERAMBITAN = '5102042010';
    case MELILING = '5102042011';
    case SEMBUNG_GEDE = '5102042012';
    case BATUAJI = '5102042013';
    case KESIUT = '5102042014';
    case TIMPAG = '5102042015';

    // ── TABANAN (510205) ────────────────────────────────────────────
    case SUDIMARA_TABANAN = '5102052001';
    case GUBUG = '5102052002';
    case BONGAN = '5102052003';
    case DELOD_PEKEN = '5102052004';
    case DAUH_PEKEN = '5102052005';
    case DAJAN_PEKEN = '5102052006';
    case DENBANTAS = '5102052007';
    case SUBAMIA = '5102052008';
    case WANASARI = '5102052009';
    case TUNJUK = '5102052010';
    case BUAHAN = '5102052011';
    case SESANDAN = '5102052012';

    // ── KEDIRI (510206) ─────────────────────────────────────────────
    case BENGKEL = '5102062001';
    case PANGKUNG_TIBAH = '5102062002';
    case BELALANG = '5102062003';
    case BERABAN_KEDIRI = '5102062004';
    case BUWIT = '5102062005';
    case CEPAKA = '5102062006';
    case KABA_KABA = '5102062007';
    case NYAMBU = '5102062008';
    case PANDAK_BANDUNG = '5102062009';
    case PANDAK_GEDE = '5102062010';
    case NYITDAH = '5102062011';
    case PEJATEN = '5102062012';
    case KEDIRI = '5102062013';
    case ABIAN_TUWUNG = '5102062014';
    case BANJAR_ANYAR = '5102062015';

    // ── MARGA (510207) ──────────────────────────────────────────────
    case KUKUH_MARGA = '5102072001';
    case BERINGKIT_BELAYU = '5102072002';
    case PEKEN_BELAYU = '5102072003';
    case BATANNYUH = '5102072004';
    case TEGALJADI = '5102072005';
    case KUWUM = '5102072006';
    case SELANBAWAK = '5102072007';
    case MARGA = '5102072008';
    case PETIGA = '5102072009';
    case CAU_BELAYU = '5102072010';
    case TUA = '5102072011';
    case PAYANGAN = '5102072012';
    case MARGA_DAJAN_PURI = '5102072013';
    case MARGA_DAUH_PURI = '5102072014';
    case GELUNTUNG = '5102072015';
    case BARU = '5102072016';

    // ── PENEBEL (510208) ────────────────────────────────────────────
    case REJASA = '5102082001';
    case JEGU = '5102082002';
    case RIANG_GEDE = '5102082003';
    case BURUAN = '5102082004';
    case BIAUNG = '5102082005';
    case PITRA = '5102082006';
    case PENATAHAN = '5102082007';
    case TENGKUDAK = '5102082008';
    case MENGESTA = '5102082009';
    case PENEBEL = '5102082010';
    case BABAHAN = '5102082011';
    case SENGANAN = '5102082012';
    case JATILUWIH = '5102082013';
    case WONGAYA_GEDE = '5102082014';
    case TAJEN = '5102082015';
    case TEGALLINGGAH = '5102082016';
    case PESAGI = '5102082017';
    case SANGKETAN = '5102082018';

    // ── BATURITI (510209) ───────────────────────────────────────────
    case PEREAN = '5102092001';
    case LUWUS = '5102092002';
    case APUAN = '5102092003';
    case ANGSERI = '5102092004';
    case BANGLI = '5102092005';
    case BATURITI = '5102092006';
    case ANTAPAN = '5102092007';
    case CANDIKUNING = '5102092008';
    case MEKARSARI = '5102092009';
    case BATUNYA = '5102092010';
    case PEREAN_TENGAH = '5102092011';
    case PEREAN_KANGIN = '5102092012';

    // ── PUPUAN (510210) ─────────────────────────────────────────────
    case BELIMBING = '5102102001';
    case SANDA = '5102102002';
    case BATUNGSEL = '5102102003';
    case KEBON_PADANGAN = '5102102004';
    case MUNDUKTEMU = '5102102005';
    case PUJUNGAN = '5102102006';
    case PUPUAN = '5102102007';
    case BANTIRAN = '5102102008';
    case PADANGAN = '5102102009';
    case JELIJIH_PUNGGANG = '5102102010';
    case BELATUNGAN = '5102102011';
    case PAJAHAN = '5102102012';
    case KARYASARI = '5102102013';
    case SAI = '5102102014';
}
