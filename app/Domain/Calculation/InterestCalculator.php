<?php

namespace App\Domain\Calculation;

interface InterestCalculator
{
    /**
     * Generate jadwal angsuran.
     *
     * @param int   $pokok    Pokok pinjaman (rupiah)
     * @param float $rate     Bunga/margin per tahun (% — e.g. 12 = 12%)
     * @param int   $tenor    Tenor dalam bulan
     * @param array $opts     Opsi tambahan (nisbah_anggota, ekspektasi_bagi_hasil, dll)
     *
     * @return array{
     *   total_pokok: int,
     *   total_margin: int,
     *   total_bayar: int,
     *   schedule: array<int, array{angsuran_ke:int, pokok:int, margin:int, total:int, saldo_pokok:int}>
     * }
     */
    public function generate(int $pokok, float $rate, int $tenor, array $opts = []): array;

    public function name(): string;
}
