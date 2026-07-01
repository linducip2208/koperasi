<?php

namespace App\Domain\Calculation\Syariah;

use App\Domain\Calculation\InterestCalculator;

/**
 * Murabahah — jual-beli dengan margin tetap.
 * Harga jual = pokok + margin (margin% dari pokok × tahun).
 * Angsuran tetap setiap bulan = (pokok + margin) / tenor.
 */
class MurabahahCalculator implements InterestCalculator
{
    public function generate(int $pokok, float $rate, int $tenor, array $opts = []): array
    {
        // rate = margin per tahun (e.g. 12% per tahun)
        $tahun        = max(1, $tenor / 12);
        $marginTotal  = (int) round($pokok * ($rate / 100) * $tahun);
        $totalJual    = $pokok + $marginTotal;
        $angsuranTetap = (int) round($totalJual / $tenor);
        $pokokPerBulan = (int) round($pokok / $tenor);
        $marginPerBulan = (int) round($marginTotal / $tenor);

        $schedule = [];
        $sisaPokok = $pokok;
        $totalPokok = 0;
        $totalMargin = 0;

        for ($i = 1; $i <= $tenor; $i++) {
            $isLast = ($i === $tenor);
            $thisPokok = $isLast ? $sisaPokok : $pokokPerBulan;
            $thisMargin = $isLast ? ($marginTotal - $totalMargin) : $marginPerBulan;
            $sisaPokok -= $thisPokok;

            $schedule[] = [
                'angsuran_ke' => $i,
                'pokok'       => $thisPokok,
                'margin'      => $thisMargin,
                'total'       => $thisPokok + $thisMargin,
                'saldo_pokok' => max(0, $sisaPokok),
            ];
            $totalPokok  += $thisPokok;
            $totalMargin += $thisMargin;
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
        return 'Murabahah (Jual-Beli)';
    }
}
