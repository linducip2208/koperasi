<?php

namespace App\Helpers;

class Terbilang
{
    public static function make(float|int $angka): string
    {
        $angka = abs((int) $angka);
        $satuan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

        if ($angka < 12) {
            return $satuan[$angka];
        }
        if ($angka < 20) {
            return self::make($angka - 10) . ' belas';
        }
        if ($angka < 100) {
            return self::make((int) ($angka / 10)) . ' puluh' . ($angka % 10 ? ' ' . self::make($angka % 10) : '');
        }
        if ($angka < 200) {
            return 'seratus' . ($angka - 100 ? ' ' . self::make($angka - 100) : '');
        }
        if ($angka < 1000) {
            return self::make((int) ($angka / 100)) . ' ratus' . ($angka % 100 ? ' ' . self::make($angka % 100) : '');
        }
        if ($angka < 2000) {
            return 'seribu' . ($angka - 1000 ? ' ' . self::make($angka - 1000) : '');
        }
        if ($angka < 1_000_000) {
            return self::make((int) ($angka / 1000)) . ' ribu' . ($angka % 1000 ? ' ' . self::make($angka % 1000) : '');
        }
        if ($angka < 1_000_000_000) {
            return self::make((int) ($angka / 1_000_000)) . ' juta' . ($angka % 1_000_000 ? ' ' . self::make($angka % 1_000_000) : '');
        }
        if ($angka < 1_000_000_000_000) {
            return self::make((int) ($angka / 1_000_000_000)) . ' miliar' . ($angka % 1_000_000_000 ? ' ' . self::make($angka % 1_000_000_000) : '');
        }
        return self::make((int) ($angka / 1_000_000_000_000)) . ' triliun' . ($angka % 1_000_000_000_000 ? ' ' . self::make($angka % 1_000_000_000_000) : '');
    }
}
