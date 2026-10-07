<?php

namespace App\Imports;

use Carbon\Carbon;

/**
 * Normalisasi input Indonesia: "1.250.000,50" → 1250000 (rupiah, integer).
 * Desimal dibulatkan ke rupiah terdekat — tanpa float untuk uang.
 */
class IndonesianNumber
{
    public static function toInt(?string $raw): ?int
    {
        if ($raw === null) {
            return null;
        }
        $s = trim((string) $raw);
        if ($s === '') {
            return null;
        }
        $s = str_replace(['Rp', 'rp', ' '], '', $s);
        // Format ID: titik ribuan, koma desimal. Format EN: koma ribuan, titik desimal.
        if (str_contains($s, ',') && str_contains($s, '.')) {
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        } elseif (str_contains($s, ',')) {
            $s = str_replace(',', '.', $s);
        } elseif (preg_match('/^\d{1,3}(\.\d{3})+$/', $s)) {
            $s = str_replace('.', '', $s); // ribuan saja: 1.250.000
        }
        if (! is_numeric($s)) {
            return null;
        }

        return (int) round((float) $s);
    }

    public static function toDate(?string $raw): ?string
    {
        if ($raw === null || trim((string) $raw) === '') {
            return null;
        }
        $s = trim((string) $raw);
        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'Y/m/d', 'm/d/Y'] as $f) {
            try {
                $d = Carbon::createFromFormat($f, $s);
                if ($d && $d->format($f) === $s) {
                    return $d->toDateString();
                }
            } catch (\Throwable) {
            }
        }
        try {
            return Carbon::parse($s)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }
}
