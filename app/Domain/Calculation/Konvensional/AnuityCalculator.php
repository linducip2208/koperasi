<?php

namespace App\Domain\Calculation\Konvensional;

use App\Domain\Calculation\InterestCalculator;

class AnuityCalculator implements InterestCalculator
{
    public function generate(int $pokok, float $rate, int $tenor, array $opts = []): array
    {
        $rateBulanan = $rate / 100 / 12;

        if ($rateBulanan <= 0) {
            return (new FlatCalculator)->generate($pokok, 0, $tenor);
        }

        $angsuranTetap = $pokok * $rateBulanan / (1 - pow(1 + $rateBulanan, -$tenor));
        $angsuranTetap = (int) round($angsuranTetap);

        $schedule = [];
        $sisa = $pokok;
        $totalPokok = 0;
        $totalMargin = 0;

        for ($i = 1; $i <= $tenor; $i++) {
            $bunga = (int) round($sisa * $rateBulanan);
            $isLast = ($i === $tenor);
            $thisPokok = $isLast ? $sisa : $angsuranTetap - $bunga;
            $thisTotal = $isLast ? $thisPokok + $bunga : $angsuranTetap;
            $sisa -= $thisPokok;

            $schedule[] = [
                'angsuran_ke' => $i,
                'pokok'       => $thisPokok,
                'margin'      => $bunga,
                'total'       => $thisTotal,
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
        return 'Anuitas';
    }
}
