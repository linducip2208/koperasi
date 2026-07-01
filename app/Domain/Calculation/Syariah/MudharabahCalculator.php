<?php

namespace App\Domain\Calculation\Syariah;

use App\Domain\Calculation\InterestCalculator;

/**
 * Mudharabah — bagi hasil dari ekspektasi pendapatan usaha.
 * $rate diabaikan; pakai opts['ekspektasi_pendapatan_bulanan'] dan opts['nisbah_koperasi'].
 * Pokok dibayar di akhir tenor (modal kerja) ATAU dicicil sesuai opts['cicil_pokok'].
 */
class MudharabahCalculator implements InterestCalculator
{
    public function generate(int $pokok, float $rate, int $tenor, array $opts = []): array
    {
        $ekspektasi      = (int) ($opts['ekspektasi_pendapatan_bulanan'] ?? 0);
        $nisbahKoperasi  = (float) ($opts['nisbah_koperasi'] ?? 30); // % dari pendapatan
        $cicilPokok      = (bool) ($opts['cicil_pokok'] ?? true);
        $bagiHasilPerBulan = (int) round($ekspektasi * ($nisbahKoperasi / 100));

        $pokokPerBulan = $cicilPokok ? (int) round($pokok / $tenor) : 0;

        $schedule = [];
        $sisa = $pokok;
        $totalPokok = 0;
        $totalMargin = 0;

        for ($i = 1; $i <= $tenor; $i++) {
            $isLast = ($i === $tenor);

            if ($cicilPokok) {
                $thisPokok = $isLast ? $sisa : $pokokPerBulan;
            } else {
                $thisPokok = $isLast ? $sisa : 0;
            }

            $sisa -= $thisPokok;

            $schedule[] = [
                'angsuran_ke' => $i,
                'pokok'       => $thisPokok,
                'margin'      => $bagiHasilPerBulan,
                'total'       => $thisPokok + $bagiHasilPerBulan,
                'saldo_pokok' => max(0, $sisa),
            ];
            $totalPokok  += $thisPokok;
            $totalMargin += $bagiHasilPerBulan;
        }

        return [
            'total_pokok'  => $totalPokok,
            'total_margin' => $totalMargin,
            'total_bayar'  => $totalPokok + $totalMargin,
            'schedule'     => $schedule,
        ];
    }

    public function name(): string
    {
        return 'Mudharabah (Bagi Hasil)';
    }
}
