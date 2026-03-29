<?php

namespace Src\Core\Shared\Enums\Wilayah;

/**
 * Desa dan kelurahan di Kabupaten Buleleng (5108). ~148 desa/kelurahan.
 * Buleleng adalah kabupaten terbesar di Bali dengan 9 kecamatan.
 *
 * Catatan: Hanya desa-desa kunci yang dicantumkan per kecamatan
 * karena jumlah total sangat besar. Untuk data lengkap, gunakan
 * import CSV dari data.bps.go.id dengan kode wilayah 5108.
 */
enum DesaKelurahanBulelengEnum: string
{
    use HasWilayahMethods;

    // ── Gerokgak (510801) ─────────────────────────────────────────────
    case SUMBERKLAMPOK = '5108012001';
    case PEJARAKAN = '5108012002';
    case SUMBERKIMA = '5108012003';
    case PEMUTERAN = '5108012004';
    case BANYUPOH = '5108012005';
    case PENYABANGAN = '5108012006';
    case MUSI = '5108012007';
    case SANGGALANGIT = '5108012008';
    case GEROKGAK = '5108012009';
    case PATAS = '5108012010';
    case PENGULON = '5108012011';
    case TINGA_TINGA = '5108012012';
    case CELUKANBAWANG = '5108012013';
    case TUKADSUMAGA = '5108012014';

    // ── Seririt (510802) ──────────────────────────────────────────────
    case SERIRIT = '5108021015'; // Kelurahan
    case UNGGAHAN = '5108022001';
    case ULARAN = '5108022002';
    case RINGDIKIT = '5108022003';
    case RANGDU = '5108022004';
    case MAYONG = '5108022005';
    case GUNUNGSARI = '5108022006';
    case MUNDUK_BESTALA = '5108022007';
    case BESTALA = '5108022008';
    case KALIANGET = '5108022009';
    case JOANYAR = '5108022010';
    case TANGGUWISIA = '5108022011';
    case SULANYAH = '5108022012';
    case BUBUNAN = '5108022013';
    case PATEMON = '5108022014';
    case PENGASTULAN = '5108022016';
    case LOKAPAKSA = '5108022017';
    case PANGKUNGPARUK = '5108022018';
    case BANJARASEM = '5108022019';
    case KALISADA = '5108022020';
    case UMEANYAR = '5108022021';

    // ── Busungbiu (510803) ────────────────────────────────────────────
    case SEPANG = '5108032001';
    case DAPDAP_PUTIH = '5108032002';
    case BONGANCINA = '5108032003';
    case PUCAKSARI = '5108032004';
    case TELAGA = '5108032005';
    case TITAB = '5108032006';
    case SUBUK = '5108032007';
    case TINGGARSARI = '5108032008';
    case KEDIS = '5108032009';
    case KEKERAN_BUSUNGBIU = '5108032010';
    case BUSUNGBIU = '5108032011';
    case PELAPUAN = '5108032012';
    case BENGKEL_BUSUNGBIU = '5108032013';
    case UMEJERO_BUSUNGBIU = '5108032014';
    case SEPANG_KELOD = '5108032015';

    // ── Banjar (510804) ───────────────────────────────────────────────
    case BANYUSERI = '5108042001';
    case TIRTASARI = '5108042002';
    case KAYUPUTIH_BANJAR = '5108042003';
    case BANYUATIS = '5108042004';
    case GESING = '5108042005';
    case MUNDUK = '5108042006';
    case GOBLEG = '5108042007';
    case PEDAWA = '5108042008';
    case CEMPAGA = '5108042009';
    case SIDETAPA = '5108042010';
    case TAMPEKAN = '5108042011';
    case BANJAR_TEGEHA = '5108042012';
    case BANJAR_DESA = '5108042013';
    case DENCARIK = '5108042014';
    case TEMUKUS = '5108042015';
    case TIGAWASA = '5108042016';
    case KALIASEM = '5108042017';

    // ── Sukasada (510805) ─────────────────────────────────────────────
    case SUKASADA = '5108051009'; // Kelurahan
    case PANCASARI = '5108052001';
    case WANAGIRI = '5108052002';
    case AMBENGAN = '5108052003';
    case GITGIT = '5108052004';
    case PEGAYAMAN = '5108052005';
    case SILANGJANA = '5108052006';
    case PEGADUNGAN = '5108052007';
    case PADANGBULIA = '5108052008';
    case SAMBANGAN = '5108052010';
    case PANJI = '5108052011';
    case PANJI_ANOM = '5108052012';
    case TEGALLINGGAH = '5108052013';
    case SELAT_SUKASADA = '5108052014';
    case KAYUPUTIH_SUKASADA = '5108052015';

    // ── Buleleng (510806) ─────────────────────────────────────────────
    case BANYUASRI = '5108061006';
    case BANJAR_TEGAL = '5108061007';
    case KENDRAN = '5108061008';
    case PAKET_AGUNG = '5108061009';
    case KAMPUNG_SINGARAJA = '5108061010';
    case LILIGUNDI = '5108061011';
    case BERATAN = '5108061012';
    case BANYUNING = '5108061019';
    case PENARUKAN = '5108061020';
    case KAMPUNG_KAJANAN = '5108061021';
    case ASTINA = '5108061022';
    case BANJAR_JAWA = '5108061023';
    case KALIUNTU = '5108061024';
    case KAMPUNG_ANYAR = '5108061025';
    case KAMPUNG_BUGIS = '5108061026';
    case BANJAR_BALI = '5108061027';
    case KAMPUNG_BARU = '5108061029';
    case KALIBUKBUK = '5108062001';
    case ANTURAN = '5108062002';
    case TUKADMUNGGA = '5108062003';
    case PEMARON = '5108062004';
    case BAKTISERAGA = '5108062005';
    case SARIMEKAR = '5108062013';
    case NAGASEPAHA = '5108062014';
    case PETANDAKAN = '5108062015';
    case ALASANGKER = '5108062016';
    case POH_BERGONG = '5108062017';
    case JINENGDALEM = '5108062018';
    case PENGLATAN = '5108062028';

    // ── Sawan (510807) ────────────────────────────────────────────────
    case LEMUKIH = '5108072001';
    case GALUNGAN = '5108072002';
    case SEKUMPUL = '5108072003';
    case BEBETIN = '5108072004';
    case SUDAJI = '5108072005';
    case SAWAN = '5108072006';
    case MENYALI = '5108072007';
    case SUWUG = '5108072008';
    case JAGARAGA = '5108072009';
    case SINABUN = '5108072010';
    case KEROBOKAN = '5108072011';
    case SANGSIT = '5108072012';
    case BUNGKULAN = '5108072013';
    case GIRI_EMAS = '5108072014';

    // ── Kubutambahan (510808) ─────────────────────────────────────────
    case TAMBAKAN = '5108082001';
    case PAKISAN = '5108082002';
    case BONTIHING = '5108082003';
    case TAJUN = '5108082004';
    case TUNJUNG = '5108082005';
    case DEPEHA = '5108082006';
    case TAMBLANG = '5108082007';
    case BULIAN = '5108082008';
    case BILA = '5108082009';
    case BENGKALA = '5108082010';
    case KUBUTAMBAHAN = '5108082011';
    case BUKTI = '5108082012';
    case MENGENING = '5108082013';

    // ── Tejakula (510809) ─────────────────────────────────────────────
    case SEMBIRAN = '5108092001';
    case PACUNG = '5108092002';
    case JULAH = '5108092003';
    case MADENAN = '5108092004';
    case BONDALEM = '5108092005';
    case TEJAKULA = '5108092006';
    case LES = '5108092007';
    case PENUKTUKAN = '5108092008';
    case SAMBIRENTENG = '5108092009';
    case TEMBOK = '5108092010';
}
