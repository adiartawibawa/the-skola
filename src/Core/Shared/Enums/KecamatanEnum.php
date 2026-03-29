<?php

// src/Core/Shared/Enums/KecamatanEnum.php

namespace Src\Core\Shared\Enums;

/**
 * Enum seluruh kecamatan di Provinsi Bali — 57 kecamatan.
 *
 * Nilai (value) adalah kode BPS 7 digit: PPKKCCC
 *   PP  = kode provinsi (51)
 *   KK  = kode kabupaten/kota (01–08, 71)
 *   CCC = nomor urut kecamatan dalam kabupaten (010, 020, ...)
 *
 * Metode kabupaten() me-resolve ke KabupatenKotaEnum berdasarkan
 * 4 digit pertama kode, sehingga relasi hierarki selalu terjaga.
 *
 * Sumber: BPS Provinsi Bali — Publikasi Kecamatan Dalam Angka 2024
 */
enum KecamatanEnum: string
{
    // ── JEMBRANA (5101) — 5 kecamatan ───────────────────────────────
    case NEGARA = '5101010';
    case MENDOYO = '5101020';
    case PEKUTATAN = '5101030';
    case MELAYA = '5101040';
    case JEMBRANA = '5101050';

    // ── TABANAN (5102) — 10 kecamatan ───────────────────────────────
    case SELEMADEG = '5102010';
    case SELEMADEG_TIMUR = '5102020';
    case SELEMADEG_BARAT = '5102030';
    case KERAMBITAN = '5102040';
    case TABANAN = '5102050';
    case KEDIRI = '5102060';
    case MARGA = '5102070';
    case PENEBEL = '5102080';
    case BATURITI = '5102090';
    case PUPUAN = '5102100';

    // ── BADUNG (5103) — 6 kecamatan ─────────────────────────────────
    case KUTA = '5103010';
    case MENGWI = '5103020';
    case ABIANSEMAL = '5103030';
    case PETANG = '5103040';
    case KUTA_SELATAN = '5103050';
    case KUTA_UTARA = '5103060';

    // ── GIANYAR (5104) — 7 kecamatan ────────────────────────────────
    case SUKAWATI = '5104010';
    case BLAHBATUH = '5104020';
    case GIANYAR = '5104030';
    case TAMPAKSIRING = '5104040';
    case UBUD = '5104050';
    case TEGALLALANG = '5104060';
    case PAYANGAN = '5104070';

    // ── KLUNGKUNG (5105) — 4 kecamatan ──────────────────────────────
    case NUSA_PENIDA = '5105010';
    case BANJARANGKAN = '5105020';
    case KLUNGKUNG = '5105030';
    case DAWAN = '5105040';

    // ── BANGLI (5106) — 4 kecamatan ─────────────────────────────────
    case SUSUT = '5106010';
    case BANGLI = '5106020';
    case TEMBUKU = '5106030';
    case KINTAMANI = '5106040';

    // ── KARANGASEM (5107) — 8 kecamatan ─────────────────────────────
    case RENDANG = '5107010';
    case SIDEMEN = '5107020';
    case MANGGIS = '5107030';
    case KARANGASEM = '5107040';
    case ABANG = '5107050';
    case BEBANDEM = '5107060';
    case SELAT = '5107070';
    case KUBU = '5107080';

    // ── BULELENG (5108) — 9 kecamatan ───────────────────────────────
    case GEROKGAK = '5108010';
    case SERIRIT = '5108020';
    case BUSUNGBIU = '5108030';
    case BANJAR = '5108040';
    case SUKASADA = '5108050';
    case BULELENG = '5108060';
    case SAWAN = '5108070';
    case KUBUTAMBAHAN = '5108080';
    case TEJAKULA = '5108090';

    // ── KOTA DENPASAR (5171) — 4 kecamatan ──────────────────────────
    case DENPASAR_SELATAN = '5171010';
    case DENPASAR_TIMUR = '5171020';
    case DENPASAR_BARAT = '5171030';
    case DENPASAR_UTARA = '5171040';

    // ── Methods ──────────────────────────────────────────────────────

    /**
     * Nama resmi kecamatan untuk ditampilkan di UI.
     * Contoh: SELEMADEG_BARAT → "Selemadeg Barat"
     */
    public function label(): string
    {
        return collect(explode('_', $this->name))
            ->map(fn ($w) => ucfirst(strtolower($w)))
            ->implode(' ');
    }

    /**
     * Resolve ke KabupatenKotaEnum berdasarkan 4 digit pertama kode BPS.
     * Contoh: '5103020' → KabupatenKotaEnum::BADUNG
     */
    public function kabupaten(): KabupatenKotaEnum
    {
        $kodeKab = substr($this->value, 0, 4);

        return collect(KabupatenKotaEnum::cases())
            ->first(fn ($k) => $k->kodeBps() === $kodeKab)
            ?? throw new \ValueError("Kabupaten tidak ditemukan untuk kode: {$this->value}");
    }

    /**
     * Daftar semua kecamatan dalam kabupaten tertentu.
     * Berguna untuk cascade dropdown di Filament.
     */
    public static function byKabupaten(KabupatenKotaEnum $kabupaten): array
    {
        $prefix = $kabupaten->kodeBps();

        return collect(self::cases())
            ->filter(fn ($k) => str_starts_with($k->value, $prefix))
            ->mapWithKeys(fn ($k) => [$k->value => $k->label()])
            ->toArray();
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($k) => [$k->value => $k->label()])
            ->toArray();
    }
}
