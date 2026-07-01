<?php

namespace App\Domain\Calculation\Syariah;

use App\Domain\Calculation\InterestCalculator;

/**
 * Musyarakah — kemitraan modal. Mirip mudharabah tapi pokok proporsional ke share.
 * opts: porsi_koperasi (% modal), nisbah_koperasi (% bagi hasil).
 */
class MusyarakahCalculator implements InterestCalculator
{
    public function generate(int $pokok, float $rate, int $tenor, array $opts = []): array
    {
        $ekspektasi     = (int) ($opts['ekspektasi_pendapatan_bulanan'] ?? 0);
        $nisbahKoperasi = (float) ($opts['nisbah_koperasi'] ?? 25);
        $bagiHasil      = (int) round($ekspektasi * ($nisbahKoperasi / 100));
        $pokokPerBulan  = (int) round($pokok / $tenor);

        $schedule = [];
        $sisa = $pokok;
        $totalPokok = 0;
        $totalMargin = 0;

        for ($i = 1; $i <= $tenor; $i++) {
            $thisPokok = ($i === $tenor) ? $sisa : $pokokPerBulan;
            $sisa -= $thisPokok;

            $schedule[] = [
                'angsuran_ke' => $i,
                'pokok'       => $thisPokok,
                'margin'      => $bagiHasil,
                'total'       => $thisPokok + $bagiHasil,
                'saldo_pokok' => max(0, $sisa),
            ];
            $totalPokok  += $thisPokok;
            $totalMargin += $bagiHasil;
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
        return 'Musyarakah (Kemitraan)';
    }
}
