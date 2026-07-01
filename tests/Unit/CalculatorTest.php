<?php

namespace Tests\Unit;

use App\Domain\Calculation\Konvensional\AnuityCalculator;
use App\Domain\Calculation\Konvensional\FlatCalculator;
use App\Domain\Calculation\Syariah\MurabahahCalculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    // ─── FLAT CALCULATOR ───

    public function test_flat_calculator_returns_correct_name(): void
    {
        $calc = new FlatCalculator();
        $this->assertSame('Bunga Flat', $calc->name());
    }

    public function test_flat_calculator_generates_correct_schedule(): void
    {
        $calc = new FlatCalculator();
        $result = $calc->generate(pokok: 1_000_000, rate: 12, tenor: 12);

        $this->assertSame(1_000_000, $result['total_pokok']);
        $this->assertSame(120_000, $result['total_margin']);
        $this->assertSame(1_120_000, $result['total_bayar']);
        $this->assertCount(12, $result['schedule']);

        $first = $result['schedule'][0];
        $this->assertSame(1, $first['angsuran_ke']);
        $this->assertSame(93_333, $first['total']); // (1000000/12) + (1000000*0.12/12)

        $last = $result['schedule'][11];
        $this->assertSame(12, $last['angsuran_ke']);
        $this->assertSame(0, $last['saldo_pokok']);
    }

    public function test_flat_calculator_with_zero_rate(): void
    {
        $calc = new FlatCalculator();
        $result = $calc->generate(pokok: 500_000, rate: 0, tenor: 6);

        $this->assertSame(500_000, $result['total_pokok']);
        $this->assertSame(0, $result['total_margin']);
        $this->assertSame(500_000, $result['total_bayar']);
    }

    public function test_flat_calculator_single_month(): void
    {
        $calc = new FlatCalculator();
        $result = $calc->generate(pokok: 300_000, rate: 24, tenor: 1);

        $this->assertCount(1, $result['schedule']);
        $this->assertSame(300_000, $result['schedule'][0]['pokok']);
        $this->assertSame(6_000, $result['schedule'][0]['margin']);
        $this->assertSame(306_000, $result['schedule'][0]['total']);
        $this->assertSame(0, $result['schedule'][0]['saldo_pokok']);
    }

    // ─── ANUITY CALCULATOR ───

    public function test_anuity_calculator_returns_correct_name(): void
    {
        $calc = new AnuityCalculator();
        $this->assertSame('Anuitas', $calc->name());
    }

    public function test_anuity_calculator_generates_correct_totals(): void
    {
        $calc = new AnuityCalculator();
        $result = $calc->generate(pokok: 10_000_000, rate: 12, tenor: 12);

        $this->assertSame(10_000_000, $result['total_pokok']);
        $this->assertSame(12, count($result['schedule']));
        $this->assertSame(0, $result['schedule'][11]['saldo_pokok']);

        $total = $result['total_bayar'];
        $this->assertGreaterThan(10_000_000, $total);
        $this->assertLessThan(11_000_000, $total);
    }

    public function test_anuity_calculator_decreasing_margin(): void
    {
        $calc = new AnuityCalculator();
        $result = $calc->generate(pokok: 10_000_000, rate: 12, tenor: 12);

        $firstMargin = $result['schedule'][0]['margin'];
        $lastMargin = $result['schedule'][11]['margin'];

        $this->assertGreaterThan($lastMargin, $firstMargin);
    }

    public function test_anuity_calculator_zero_rate_delegates_to_flat(): void
    {
        $calc = new AnuityCalculator();
        $result = $calc->generate(pokok: 1_000_000, rate: 0, tenor: 10);

        $this->assertSame(1_000_000, $result['total_pokok']);
        $this->assertSame(0, $result['total_margin']);
        $this->assertCount(10, $result['schedule']);
    }

    // ─── MURABAHAH CALCULATOR ───

    public function test_murabahah_calculator_returns_correct_name(): void
    {
        $calc = new MurabahahCalculator();
        $this->assertSame('Murabahah (Jual-Beli)', $calc->name());
    }

    public function test_murabahah_12_month(): void
    {
        $calc = new MurabahahCalculator();
        $result = $calc->generate(pokok: 5_000_000, rate: 12, tenor: 12);

        $this->assertSame(5_000_000, $result['total_pokok']);
        $this->assertSame(600_000, $result['total_margin']); // 12% x 1 tahun
        $this->assertSame(5_600_000, $result['total_bayar']);
        $this->assertCount(12, $result['schedule']);
        $this->assertSame(0, $result['schedule'][11]['saldo_pokok']);
    }

    public function test_murabahah_24_month(): void
    {
        $calc = new MurabahahCalculator();
        $result = $calc->generate(pokok: 10_000_000, rate: 10, tenor: 24);

        $expectedMargin = (int) round(10_000_000 * 0.10 * 2); // 2 tahun
        $this->assertSame($expectedMargin, $result['total_margin']);
        $this->assertCount(24, $result['schedule']);
    }

    public function test_murabahah_single_month(): void
    {
        $calc = new MurabahahCalculator();
        $result = $calc->generate(pokok: 1_000_000, rate: 24, tenor: 1);

        $this->assertCount(1, $result['schedule']);
        $this->assertSame(0, $result['schedule'][0]['saldo_pokok']);
    }

    // ─── EDGE CASES ───

    public function test_large_loan_values(): void
    {
        $calc = new FlatCalculator();
        $result = $calc->generate(pokok: 100_000_000, rate: 18, tenor: 36);

        $this->assertSame(100_000_000, $result['total_pokok']);
        $this->assertCount(36, $result['schedule']);
        $this->assertSame(0, $result['schedule'][35]['saldo_pokok']);
    }

    public function test_minimal_loan(): void
    {
        $calc = new FlatCalculator();
        $result = $calc->generate(pokok: 100_000, rate: 5, tenor: 3);

        $this->assertSame(100_000, $result['total_pokok']);
        $this->assertCount(3, $result['schedule']);
    }
}
