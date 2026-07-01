<?php

namespace App\Domain\Calculation\Konvensional;

use App\Domain\Calculation\InterestCalculator;

class FlatCalculator implements InterestCalculator
{
    public function generate(int $pokok, float $rate, int $tenor, array $opts = []): array
    {
        $bungaPerBulan = (int) round($pokok * ($rate / 100) / 12);
        $pokokPerBulan = (int) round($pokok / $tenor);

        $schedule = [];
        $sisa = $pokok;
        $totalPokok = 0;
        $totalMargin = 0;

        for ($i = 1; $i <= $tenor; $i++) {
            $isLast    = ($i === $tenor);
            $thisPokok = $isLast ? $sisa : $pokokPerBulan;
            $sisa -= $thisPokok;

            $schedule[] = [
                'angsuran_ke' => $i,
                'pokok'       => $thisPokok,
                'margin'      => $bungaPerBulan,
                'total'       => $thisPokok + $bungaPerBulan,
                'saldo_pokok' => max(0, $sisa),
            ];
            $totalPokok  += $thisPokok;
            $totalMargin += $bungaPerBulan;
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
        return 'Bunga Flat';
    }
}
