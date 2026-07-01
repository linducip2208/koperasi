<?php

namespace App\Domain\Calculation\Syariah;

use App\Domain\Calculation\InterestCalculator;

/**
 * Qardh — pinjaman kebajikan tanpa bunga/margin.
 * Hanya pokok yang dikembalikan, bisa flat atau bullet.
 */
class QardhCalculator implements InterestCalculator
{
    public function generate(int $pokok, float $rate, int $tenor, array $opts = []): array
    {
        $pokokPerBulan = (int) round($pokok / $tenor);
        $schedule = [];
        $sisa = $pokok;
        $totalPokok = 0;

        for ($i = 1; $i <= $tenor; $i++) {
            $thisPokok = ($i === $tenor) ? $sisa : $pokokPerBulan;
            $sisa -= $thisPokok;

            $schedule[] = [
                'angsuran_ke' => $i,
                'pokok'       => $thisPokok,
                'margin'      => 0,
                'total'       => $thisPokok,
                'saldo_pokok' => max(0, $sisa),
            ];
            $totalPokok += $thisPokok;
        }

        return [
            'total_pokok'  => $totalPokok,
            'total_margin' => 0,
            'total_bayar'  => $totalPokok,
            'schedule'     => $schedule,
        ];
    }

    public function name(): string
    {
        return 'Qardh (Pinjaman Kebajikan)';
    }
}
