<?php

namespace App\Services;

use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\SimpananTransaksi;
use Illuminate\Support\Collection;

class PajakService
{
    /**
     * Hitung PPh 21 karyawan (metode gross-up sederhana).
     * PKP = (gaji bruto tahunan - PTKP).
     * Tarif progresif sesuai UU HPP:
     *   0 - 60jt    → 5%
     *   60jt - 250jt → 15%
     *   250jt - 500jt → 25%
     *   500jt - 5M    → 30%
     *   > 5M         → 35%
     */
    public static function hitungPph21(int $gajiBulanan, string $statusKawin = 'tk0'): int
    {
        $gajiTahunan = $gajiBulanan * 12;
        $ptkp = self::ptkp($statusKawin);
        $pkp = max(0, $gajiTahunan - $ptkp);

        $pphTahunan = self::tarifProgresif($pkp);
        return (int) round($pphTahunan / 12);
    }

    /**
     * Hitung PPh Pasal 4 ayat (2) atas bunga simpanan koperasi.
     * Tarif: 10% (final) jika bunga > Rp 240.000/bulan.
     * Di bawah threshold, 0%.
     */
    public static function hitungPphBungaSimpanan(int $bungaDiterima): int
    {
        if ($bungaDiterima <= 240_000) return 0;
        return (int) round($bungaDiterima * 0.10);
    }

    /**
     * Generate laporan PPh 21 bulanan untuk semua karyawan.
     */
    public static function laporanPph21Bulanan(string $bulan, string $tahun): Collection
    {
        $karyawan = Karyawan::where('status', 'aktif')->get();

        return $karyawan->map(function ($k) use ($bulan, $tahun) {
            $gaji = Gaji::where('karyawan_id', $k->id)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();

            $gajiBulanan = $gaji ? (int) $gaji->gaji_kotor : (int) ($k->gaji_pokok ?? 0);
            $pph21 = self::hitungPph21($gajiBulanan, $k->status_pajak ?? 'tk0');

            return [
                'nip'          => $k->nip,
                'nama'         => $k->nama,
                'npwp'         => $k->npwp,
                'gaji_bruto'   => $gajiBulanan,
                'pph21'        => $pph21,
                'gaji_neto'    => $gajiBulanan - $pph21,
                'status_pajak' => $k->status_pajak ?? 'tk0',
            ];
        });
    }

    /**
     * Generate laporan PPh 4(2) atas bunga simpanan bulanan.
     */
    public static function laporanPphBunga(string $bulan, string $tahun): array
    {
        $transaksi = SimpananTransaksi::where('jenis', 'bunga')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->with('simpanan.anggota')
            ->get();

        $totalBunga = 0;
        $totalPph = 0;
        $detail = [];

        foreach ($transaksi as $tx) {
            $bunga = (int) $tx->jumlah;
            $pph = self::hitungPphBungaSimpanan($bunga);
            $totalBunga += $bunga;
            $totalPph += $pph;

            if ($pph > 0) {
                $anggota = $tx->simpanan->anggota ?? null;
                $detail[] = [
                    'nama'  => $anggota?->nama ?? '-',
                    'npwp'  => $anggota?->npwp ?? '-',
                    'bunga' => $bunga,
                    'pph'   => $pph,
                ];
            }
        }

        return [
            'total_bunga' => $totalBunga,
            'total_pph'   => $totalPph,
            'detail'      => $detail,
        ];
    }

    private static function ptkp(string $status): int
    {
        return match ($status) {
            'k0'   => 58_500_000,   // Kawin 0 tanggungan
            'k1'   => 63_000_000,   // Kawin 1 tanggungan
            'k2'   => 67_500_000,   // Kawin 2 tanggungan
            'k3'   => 72_000_000,   // Kawin 3 tanggungan
            'tk0'  => 54_000_000,   // Tidak kawin 0 tanggungan
            'tk1'  => 58_500_000,
            'tk2'  => 63_000_000,
            'tk3'  => 67_500_000,
            default => 54_000_000,
        };
    }

    private static function tarifProgresif(int $pkp): int
    {
        $pajak = 0;
        $brackets = [
            [60_000_000,    0.05],
            [250_000_000,   0.15],
            [500_000_000,   0.25],
            [5_000_000_000, 0.30],
            [PHP_INT_MAX,   0.35],
        ];

        $prev = 0;
        foreach ($brackets as [$limit, $rate]) {
            if ($pkp > $prev) {
                $taxable = min($pkp, $limit) - $prev;
                $pajak += $taxable * $rate;
                $prev = $limit;
            }
        }

        return (int) round($pajak);
    }
}
