<?php

namespace App\Domain\Syariah;

use InvalidArgumentException;

/**
 * ProfitSharingCalculator — bagi hasil mudharabah/musyarakah.
 *
 * - Semua nominal INTEGER rupiah (tanpa float).
 * - Nisbah dalam basis poin (10000 = 100%): 6000 = 60% anggota.
 * - Pembulatan ke rupiah dengan sisa dibuang ke koperasi (deterministik).
 * - Snapshot JSON per perhitungan agar historis tidak berubah bila nisbah berubah.
 */
class ProfitSharingCalculator
{
    /**
     * @param int $pool Pendapatan pool yang dibagihasilkan (rupiah)
     * @param array $peserta [['id' => mixed, 'saldo' => int, 'nisbah_bp' => int, 'days' => int, 'period_days' => int]]
     * @return array{total_anggota:int, total_koperasi:int, sisa_pembulatan:int, baris:array, snapshot:array}
     */
    public static function distribute(int $pool, array $peserta): array
    {
        if ($pool < 0) throw new InvalidArgumentException('Pool tidak boleh negatif.');
        if (empty($peserta)) {
            return ['total_anggota' => 0, 'total_koperasi' => $pool, 'sisa_pembulatan' => 0, 'baris' => [],
                'snapshot' => ['pool' => $pool, 'peserta' => [], 'at' => now()->toDateTimeString()]];
        }

        // Bobot = saldo × (hari_aktif / hari_periode).
        $bobot = [];
        $totalBobot = 0;
        foreach ($peserta as $p) {
            $saldo = max(0, (int) ($p['saldo'] ?? 0));
            $days = max(0, min((int) ($p['days'] ?? 0), max(1, (int) ($p['period_days'] ?? 30))));
            $w = $saldo * $days;
            $bobot[] = $w;
            $totalBobot += $w;
        }

        $baris = [];
        $totalAnggota = 0;
        if ($totalBobot <= 0) {
            foreach ($peserta as $i => $p) {
                $baris[] = ['id' => $p['id'] ?? $i, 'saldo' => (int) ($p['saldo'] ?? 0), 'nisbah_bp' => (int) ($p['nisbah_bp'] ?? 0), 'bagi_hasil' => 0];
            }
            return ['total_anggota' => 0, 'total_koperasi' => $pool, 'sisa_pembulatan' => 0, 'baris' => $baris,
                'snapshot' => ['pool' => $pool, 'peserta' => $peserta, 'at' => now()->toDateTimeString()]];
        }

        // Tahap 1: porsi kotor per peserta (floor), tahap 2: sisa terbesar (largest remainder).
        $kotor = [];
        foreach ($peserta as $i => $p) {
            $nisbah = max(0, min(10000, (int) ($p['nisbah_bp'] ?? 0)));
            $share = intdiv($pool * $bobot[$i], $totalBobot); // porsi pool proporsional bobot
            $untukAnggota = intdiv($share * $nisbah, 10000);
            $kotor[] = ['idx' => $i, 'floor' => $untukAnggota,
                'frac' => (($share * $nisbah) % 10000) / 10000 + fmod($pool * $bobot[$i] / $totalBobot, 1)];
            $baris[$i] = ['id' => $p['id'] ?? $i, 'saldo' => (int) ($p['saldo'] ?? 0), 'nisbah_bp' => $nisbah, 'bagi_hasil' => $untukAnggota];
            $totalAnggota += $untukAnggota;
        }

        // Sisa pembulatan ke penerima fraksi terbesar satu per satu (deterministik by id).
        $sisa = $pool - $totalAnggota - self::koperasiShare($pool, $peserta, $bobot, $totalBobot);
        usort($kotor, fn ($a, $b) => $b['frac'] <=> $a['frac'] ?: $a['idx'] <=> $b['idx']);
        $dibagi = 0;
        foreach ($kotor as $k) {
            if ($sisa <= 0) break;
            $baris[$k['idx']]['bagi_hasil']++;
            $totalAnggota++;
            $sisa--;
            $dibagi++;
        }

        $totalKoperasi = $pool - $totalAnggota;

        return ['total_anggota' => $totalAnggota, 'total_koperasi' => $totalKoperasi,
            'sisa_pembulatan' => $dibagi, 'baris' => array_values($baris),
            'snapshot' => ['pool' => $pool, 'peserta' => $peserta, 'at' => now()->toDateTimeString(), 'version' => config('product.version')]];
    }

    private static function koperasiShare(int $pool, array $peserta, array $bobot, int $totalBobot): int
    {
        $s = 0;
        foreach ($peserta as $i => $p) {
            $nisbah = max(0, min(10000, (int) ($p['nisbah_bp'] ?? 0)));
            $share = intdiv($pool * $bobot[$i], $totalBobot);
            $s += $share - intdiv($share * $nisbah, 10000);
        }
        return $s;
    }
}
