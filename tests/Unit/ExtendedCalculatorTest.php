<?php

namespace Tests\Unit;

use App\Domain\Calculation\Konvensional\EffectiveCalculator;
use App\Domain\Calculation\Syariah\MudharabahCalculator;
use PHPUnit\Framework\TestCase;

class ExtendedCalculatorTest extends TestCase
{
    // ─── EFFECTIVE CALCULATOR ───

    public function test_effective_calculator_returns_correct_name(): void
    {
        $calc = new EffectiveCalculator();
        $this->assertSame('Bunga Menurun (Efektif)', $calc->name());
    }

    public function test_effective_calculator_generates_correct_schedule(): void
    {
        $calc = new EffectiveCalculator();
        $result = $calc->generate(pokok: 1_000_000, rate: 12, tenor: 6);

        $this->assertSame(1_000_000, $result['total_pokok']);
        $this->assertCount(6, $result['schedule']);
        $this->assertSame(0, $result['schedule'][5]['saldo_pokok']);

        $firstMargin = $result['schedule'][0]['margin'];
        $lastMargin = $result['schedule'][5]['margin'];
        $this->assertGreaterThan($lastMargin, $firstMargin);
    }

    public function test_effective_calculator_total_is_less_than_flat(): void
    {
        $flat = (new \App\Domain\Calculation\Konvensional\FlatCalculator())
            ->generate(pokok: 10_000_000, rate: 12, tenor: 12);
        $effective = (new EffectiveCalculator())
            ->generate(pokok: 10_000_000, rate: 12, tenor: 12);

        $this->assertLessThan($flat['total_margin'], $effective['total_margin']);
    }

    // ─── MUDHARABAH CALCULATOR ───

    public function test_mudharabah_calculator_returns_correct_name(): void
    {
        $calc = new MudharabahCalculator();
        $this->assertSame('Mudharabah (Bagi Hasil)', $calc->name());
    }

    public function test_mudharabah_generates_schedule(): void
    {
        $calc = new MudharabahCalculator();
        $result = $calc->generate(pokok: 5_000_000, rate: 15, tenor: 12);

        $this->assertSame(5_000_000, $result['total_pokok']);
        $this->assertCount(12, $result['schedule']);
        $this->assertSame(0, $result['schedule'][11]['saldo_pokok']);
    }

    public function test_mudharabah_zero_rate(): void
    {
        $calc = new MudharabahCalculator();
        $result = $calc->generate(pokok: 1_000_000, rate: 0, tenor: 6);

        $this->assertSame(1_000_000, $result['total_pokok']);
        $this->assertSame(0, $result['total_margin']);
    }

    // ─── CURRENCY ROUNDING ───

    public function test_all_calculators_handle_rounding(): void
    {
        $calculators = [
            new \App\Domain\Calculation\Konvensional\FlatCalculator(),
            new EffectiveCalculator(),
            new \App\Domain\Calculation\Konvensional\AnuityCalculator(),
            new \App\Domain\Calculation\Syariah\MurabahahCalculator(),
            new MudharabahCalculator(),
        ];

        foreach ($calculators as $calc) {
            $result = $calc->generate(pokok: 999_999, rate: 7.5, tenor: 13);

            $totalFromSchedule = 0;
            foreach ($result['schedule'] as $row) {
                $totalFromSchedule += $row['pokok'];
                $this->assertIsInt($row['pokok']);
                $this->assertIsInt($row['margin']);
                $this->assertIsInt($row['total']);
            }

            $this->assertEquals($result['total_pokok'], $totalFromSchedule);
            $this->assertSame(0, $result['schedule'][12]['saldo_pokok']);
        }
    }
}
