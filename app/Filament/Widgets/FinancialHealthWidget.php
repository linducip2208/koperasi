<?php

namespace App\Filament\Widgets;

use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Filament\Widgets\DashboardWidgetFilter;
use Filament\Widgets\Widget;

class FinancialHealthWidget extends Widget
{
    use DashboardWidgetFilter;

    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 1;
    protected static string $view = 'filament.widgets.financial-health';

    public function getViewData(): array
    {
        $totalSimpanan = (int) Simpanan::where('status', 'aktif')->sum('saldo');
        $totalPinjaman = (int) Pinjaman::whereIn('status', ['aktif', 'macet', 'cair'])->sum('saldo_pokok');
        $tunggakan = (int) Pinjaman::whereIn('status', ['aktif', 'macet'])
            ->where('tunggakan_hari', '>', 0)
            ->sum('saldo_pokok');

        $npl = $totalPinjaman > 0 ? round(($tunggakan / $totalPinjaman) * 100, 2) : 0;
        $ldr = $totalSimpanan > 0 ? round(($totalPinjaman / $totalSimpanan) * 100, 2) : 0;

        // Health score 0-100, weighted: NPL (40%), LDR sweet spot 70-90% (30%), Diversifikasi (30%)
        $nplScore = max(0, 100 - ($npl * 20));         // NPL 5% = score 0
        $ldrScore = $ldr >= 70 && $ldr <= 90 ? 100 : max(0, 100 - abs($ldr - 80) * 2);
        $diversifikasiScore = 75; // placeholder, perlu data lebih detail

        $healthScore = (int) round(($nplScore * 0.4) + ($ldrScore * 0.3) + ($diversifikasiScore * 0.3));
        $grade = match (true) {
            $healthScore >= 85 => ['grade' => 'A', 'label' => 'Sangat Sehat', 'color' => 'emerald'],
            $healthScore >= 70 => ['grade' => 'B', 'label' => 'Sehat', 'color' => 'blue'],
            $healthScore >= 55 => ['grade' => 'C', 'label' => 'Cukup', 'color' => 'amber'],
            $healthScore >= 40 => ['grade' => 'D', 'label' => 'Perlu Perhatian', 'color' => 'orange'],
            default            => ['grade' => 'E', 'label' => 'Tidak Sehat', 'color' => 'rose'],
        };

        return [
            'healthScore' => $healthScore,
            'grade'       => $grade,
            'npl'         => $npl,
            'ldr'         => $ldr,
            'totalAset'   => $totalSimpanan + $totalPinjaman,
        ];
    }

    protected static function isVisibleToRole($user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'manajer', 'pengawas', 'akuntan']);
    }
}
