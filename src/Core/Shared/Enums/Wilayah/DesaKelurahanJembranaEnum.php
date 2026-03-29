<?php

namespace Src\Core\Shared\Enums\Wilayah;

/**
 * Desa dan kelurahan di Kabupaten Jembrana (5101).
 * Total: 64 wilayah (Kelurahan & Desa).
 * Berdasarkan data BPS.
 */
enum DesaKelurahanJembranaEnum: string
{
    use HasWilayahMethods;

    // ── JEMBRANA (510101) ───────────────────────────────────────────
    case BALER_BALE_AGUNG = '5101011006';
    case BANJAR_TENGAH = '5101011008';
    case LELATENG = '5101011013';
    case LOLOAN_BARAT = '5101011014';
    case CUPEL = '5101012001';
    case BALUK = '5101012002';
    case BANYUBIRU = '5101012003';
    case KALIAKAH = '5101012004';
    case BERANGBANG = '5101012005';
    case TEGAL_BADENG_TIMUR = '5101012009';
    case TEGAL_BADENG_BARAT = '5101012010';
    case PENGAMBENGAN = '5101012011';

    // ── MENDOYO (510102) ────────────────────────────────────────────
    case TEGALCANGKRING = '5101021006';
    case MENDOYO_DAUH_TUKAD = '5101022001';
    case POHSANTEN = '5101022002';
    case MENDOYO_DANGIN_TUKAD = '5101022003';
    case PERGUNG = '5101022004';
    case DELODBERAWAH = '5101022005';
    case PENYARINGAN = '5101022007';
    case YEHEMBANG = '5101022008';
    case YEH_SUMBUL = '5101022009';
    case YEHEMBANG_KAUH = '5101022010';
    case YEHEMBANG_KANGIN = '5101022011';

    // ── PEKUTATAN (510103) ──────────────────────────────────────────
    case MEDEWI = '5101032001';
    case PULUKAN = '5101032002';
    case ASAHDUREN = '5101032003';
    case PEKUTATAN = '5101032004';
    case PANGYANGAN = '5101032005';
    case GUMBRIH = '5101032006';
    case MANGGIS_SARI = '5101032007';
    case PENGERAGOAN = '5101032008';

    // ── MELAYA (510104) ─────────────────────────────────────────────
    case GILIMANUK = '5101041001';
    case MELAYA = '5101042002';
    case BELIMBING_SARI = '5101042003';
    case EKASARI = '5101042004';
    case NUSASARI = '5101042005';
    case WARNASARI = '5101042006';
    case CANDI_KUSUMA = '5101042007';
    case TUWED = '5101042008';
    case TUKADAYA = '5101042009';
    case MANISTUTU = '5101042010';

    // ── NEGARA (510105) ─────────────────────────────────────────────
    case PENDEM = '5101051001';
    case LOLOAN_TIMUR = '5101051003';
    case DAUHWARU = '5101051005';
    case SANGKARAGUNG = '5101051009';
    case PERANCAK = '5101052002';
    case BATU_AGUNG = '5101052004';
    case BUDENG = '5101052006';
    case AIR_KUNING = '5101052007';
    case YEH_KUNING = '5101052008';
    case DANGIN_TUKADAYA = '5101052010';
}
