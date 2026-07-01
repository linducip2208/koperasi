<?php

namespace App\Domain\Calculation\Syariah;

use App\Domain\Calculation\InterestCalculator;

/**
 * Ijarah — sewa. Ujrah (fee sewa) tetap per bulan.
 * Pokok bisa 0 (ijarah murni) atau dilunasi di akhir (ijarah muntahiya bittamlik).
 * opts['ujrah_per_bulan'], opts['mb' => true] (muntahiya bittamlik = sewa-beli).
 */
class IjarahCalculator implements InterestCalculator
{
    public function generate(int $pokok, float $rate, int $tenor, array $opts = []): array
    {
        $ujrah = (int) ($opts['ujrah_per_bulan'] ?? round($pokok * ($rate / 100) / 12));
        $isMb  = (bool) ($opts['mb'] ?? false); // muntahiya bittamlik

        $pokokPerBulan = $isMb ? (int) round($pokok / $tenor) : 0;

        $schedule = [];
        $sisa = $pokok;
        $totalPokok = 0;
        $totalMargin = 0;

        for ($i = 1; $i <= $tenor; $i++) {
            $thisPokok = $isMb ? (($i === $tenor) ? $sisa : $pokokPerBulan) : 0;
            $sisa -= $thisPokok;

            $schedule[] = [
                'angsuran_ke' => $i,
                'pokok'       => $thisPokok,
                'margin'      => $ujrah,
                'total'       => $thisPokok + $ujrah,
                'saldo_pokok' => max(0, $sisa),
            ];
            $totalPokok  += $thisPokok;
            $totalMargin += $ujrah;
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
        return 'Ijarah (Sewa)';
    }
}
