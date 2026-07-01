<?php

namespace App\Domain\Calculation\Konvensional;

use App\Domain\Calculation\InterestCalculator;

class EffectiveCalculator implements InterestCalculator
{
    public function generate(int $pokok, float $rate, int $tenor, array $opts = []): array
    {
        $pokokPerBulan = (int) round($pokok / $tenor);
        $rateBulanan   = $rate / 100 / 12;

        $schedule = [];
        $sisa = $pokok;
        $totalPokok = 0;
        $totalMargin = 0;

        for ($i = 1; $i <= $tenor; $i++) {
            $bunga     = (int) round($sisa * $rateBulanan);
            $isLast    = ($i === $tenor);
            $thisPokok = $isLast ? $sisa : $pokokPerBulan;
            $sisa -= $thisPokok;

            $schedule[] = [
                'angsuran_ke' => $i,
                'pokok'       => $thisPokok,
                'margin'      => $bunga,
                'total'       => $thisPokok + $bunga,
                'saldo_pokok' => max(0, $sisa),
            ];
            $totalPokok  += $thisPokok;
            $totalMargin += $bunga;
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
        return 'Bunga Menurun (Efektif)';
    }
}
