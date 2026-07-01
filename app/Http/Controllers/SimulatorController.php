<?php

namespace App\Http\Controllers;

use App\Domain\Calculation\CalculatorFactory;
use Illuminate\Http\Request;

class SimulatorController extends Controller
{
    public function show(Request $request)
    {
        $methods   = CalculatorFactory::options();
        $plafon    = (int) $request->input('plafon', 10000000);
        $tenor     = (int) $request->input('tenor', 12);
        $rate      = (float) $request->input('rate', 1.5);
        $metode    = (string) $request->input('metode', 'flat');

        $jadwal      = null;
        $totalPokok  = 0;
        $totalMargin = 0;
        $totalBayar  = 0;
        $error       = null;

        if ($request->has('plafon')) {
            try {
                $calc   = CalculatorFactory::for($metode);
                $result = $calc->generate(
                    pokok: max(100000, $plafon),
                    rate:  max(0, $rate),
                    tenor: max(1, min(360, $tenor)),
                );
                $jadwal      = $result['schedule'] ?? [];
                $totalPokok  = (int) ($result['total_pokok'] ?? 0);
                $totalMargin = (int) ($result['total_margin'] ?? 0);
                $totalBayar  = (int) ($result['total_bayar'] ?? ($totalPokok + $totalMargin));
            } catch (\Throwable $e) {
                $error = 'Gagal menghitung: ' . $e->getMessage();
            }
        }

        return view('seo.simulator', [
            'methods'     => $methods,
            'plafon'      => $plafon,
            'tenor'       => $tenor,
            'rate'        => $rate,
            'metode'      => $metode,
            'jadwal'      => $jadwal,
            'error'       => $error,
            'totalPokok'  => $totalPokok,
            'totalMargin' => $totalMargin,
            'totalBayar'  => $totalBayar,
            'brand'       => config('pseo.brand'),
        ]);
    }
}
