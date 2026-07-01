<?php

namespace App\Domain\Calculation;

use App\Domain\Calculation\Konvensional\AnuityCalculator;
use App\Domain\Calculation\Konvensional\EffectiveCalculator;
use App\Domain\Calculation\Konvensional\FlatCalculator;
use App\Domain\Calculation\Syariah\IjarahCalculator;
use App\Domain\Calculation\Syariah\MudharabahCalculator;
use App\Domain\Calculation\Syariah\MurabahahCalculator;
use App\Domain\Calculation\Syariah\MusyarakahCalculator;
use App\Domain\Calculation\Syariah\QardhCalculator;
use InvalidArgumentException;

class CalculatorFactory
{
    public const KONVENSIONAL = ['flat', 'efektif', 'anuitas'];
    public const SYARIAH      = [
        'murabahah', 'mudharabah', 'musyarakah', 'ijarah', 'ijarah_mb',
        'qardh', 'rahn', 'salam', 'istishna',
        'wakalah', 'kafalah', 'hawalah',
    ];

    public static function for(string $metode): InterestCalculator
    {
        return match ($metode) {
            'flat'                                  => new FlatCalculator,
            'efektif', 'menurun'                    => new EffectiveCalculator,
            'anuitas'                               => new AnuityCalculator,
            'murabahah', 'salam', 'istishna',
            'hawalah'                               => new MurabahahCalculator,
            'mudharabah'                            => new MudharabahCalculator,
            'musyarakah'                            => new MusyarakahCalculator,
            'ijarah'                                => new IjarahCalculator,
            'ijarah_mb'                             => new class extends IjarahCalculator {
                public function generate(int $pokok, float $rate, int $tenor, array $opts = []): array
                {
                    $opts['mb'] = true;
                    return parent::generate($pokok, $rate, $tenor, $opts);
                }
            },
            'qardh', 'rahn',
            'wakalah', 'kafalah'                    => new QardhCalculator,
            default => throw new InvalidArgumentException("Metode '{$metode}' tidak dikenal."),
        };
    }

    public static function options(): array
    {
        return [
            'Konvensional' => [
                'flat'    => 'Bunga Flat',
                'efektif' => 'Bunga Menurun (Efektif)',
                'anuitas' => 'Anuitas',
            ],
            'Syariah Jual-Beli' => [
                'murabahah' => 'Murabahah',
                'salam'     => 'Salam (Pesanan)',
                'istishna'  => 'Istishna (Pesanan Produksi)',
            ],
            'Syariah Bagi Hasil' => [
                'mudharabah' => 'Mudharabah',
                'musyarakah' => 'Musyarakah',
            ],
            'Syariah Sewa' => [
                'ijarah'    => 'Ijarah',
                'ijarah_mb' => 'Ijarah Muntahiya Bittamlik',
            ],
            'Syariah Pinjaman & Gadai' => [
                'qardh' => 'Qardh (Kebajikan)',
                'rahn'  => 'Rahn (Gadai)',
            ],
            'Syariah Jasa & Pengalihan' => [
                'wakalah' => 'Wakalah (Perwakilan)',
                'kafalah' => 'Kafalah (Penjaminan)',
                'hawalah' => 'Hawalah (Pengalihan Utang)',
            ],
        ];
    }
}
