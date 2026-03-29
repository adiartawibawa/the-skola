<?php

namespace Src\Core\Shared\Enums\Wilayah;

/**
 * Desa dan kelurahan di Kabupaten Klungkung (5105). ~59 desa/kelurahan.
 * Nusa Penida adalah kecamatan terluas, mencakup tiga pulau:
 * Nusa Penida, Nusa Lembongan, dan Nusa Ceningan.
 */
enum DesaKelurahanKlungkungEnum: string
{
    use HasWilayahMethods;

    // ── NUSA PENIDA (510501) ───────────────────────────────────────
    case SAKTI = '5105012001';
    case BATUMADEG = '5105012002';
    case KLUMPU = '5105012003';
    case BATUKANDIK = '5105012004';
    case SEKARTAJI = '5105012005';
    case TANGLAD = '5105012006';
    case SUANA = '5105012007';
    case BATUNUNGGUL = '5105012008';
    case KUTAMPI = '5105012009';
    case PED = '5105012010';
    case KAMPUNG_TOYAPAKEH = '5105012011';
    case LEMBONGAN = '5105012012';
    case JUNGUTBATU = '5105012013';
    case PEJUKUTAN = '5105012014';
    case KUTAMPI_KALER = '5105012015';
    case BUNGA_MEKAR = '5105012016';

    // ── BANJARANGKAN (510502) ──────────────────────────────────────
    case NEGARI = '5105022001';
    case TAKMUNG = '5105022002';
    case BANJARANGKAN = '5105022003';
    case TUSAN = '5105022004';
    case BAKAS = '5105022005';
    case GETAKAN = '5105022006';
    case TIHINGAN = '5105022007';
    case AAN = '5105022008';
    case NYALIAN = '5105022009';
    case BUNGBUNGAN = '5105022010';
    case TIMUHUN = '5105022011';
    case NYANGLAN = '5105022012';
    case TOHPATI = '5105022013';

    // ── KLUNGKUNG (510503) ─────────────────────────────────────────
    case SEMARAPURA_KAJA = '5105031008';
    case SEMARAPURA_KAUH = '5105031009';
    case SEMARAPURA_TENGAH = '5105031010';
    case SEMARAPURA_KANGIN = '5105031011';
    case SEMARAPURA_KLOD_KANGIN = '5105031012';
    case SEMARAPURA_KLOD = '5105031013';
    case SATRA = '5105032001';
    case TOJAN = '5105032002';
    case GELGEL = '5105032003';
    case KAMPUNG_GELGEL = '5105032004';
    case JUMPAI = '5105032005';
    case TANGKAS = '5105032006';
    case KAMASAN = '5105032007';
    case AKAH = '5105032014';
    case MANDUANG = '5105032015';
    case SELAT = '5105032016';
    case TEGAK = '5105032017';
    case SELISIHAN = '5105032018';

    // ── DAWAN (510504) ─────────────────────────────────────────────
    case PAKSEBALI = '5105042001';
    case SAMPALAN_TENGAH = '5105042002';
    case SAMPALAN_KLOD = '5105042003';
    case SULANG = '5105042004';
    case GUNAKSA = '5105042005';
    case KUSAMBA = '5105042006';
    case KAMPUNG_KUSAMBA = '5105042007';
    case PESINGGAHAN = '5105042008';
    case DAWAN_KLOD = '5105042009';
    case PIKAT = '5105042010';
    case DAWAN_KALER = '5105042011';
    case BESAN = '5105042012';
}
