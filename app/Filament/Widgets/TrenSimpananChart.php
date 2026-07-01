<?php

namespace App\Filament\Widgets;

use App\Models\SimpananTransaksi;
use App\Filament\Widgets\DashboardWidgetFilter;
use Filament\Widgets\ChartWidget;

class TrenSimpananChart extends ChartWidget
{
    use DashboardWidgetFilter;

    protected static ?string $heading = '📈 Tren Simpanan vs Pinjaman (12 bulan)';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $bulan = collect();
        $simpananData = collect();
        $pinjamanData = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulan->push($date->format('M y'));

            $setor = (int) SimpananTransaksi::where('jenis', 'setor')
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->sum('jumlah');
            $simpananData->push($setor);

            $cair = (int) \App\Models\Pinjaman::whereYear('tanggal_pencairan', $date->year)
                ->whereMonth('tanggal_pencairan', $date->month)
                ->sum('plafon');
            $pinjamanData->push($cair);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Setoran Simpanan',
                    'data' => $simpananData->toArray(),
                    'borderColor' => '#059669',
                    'backgroundColor' => 'rgba(5,150,105,0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Pencairan Pinjaman',
                    'data' => $pinjamanData->toArray(),
                    'borderColor' => '#0891b2',
                    'backgroundColor' => 'rgba(8,145,178,0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $bulan->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'top'],
            ],
            'scales' => [
                'y' => ['beginAtZero' => true],
            ],
        ];
    }

    protected static function isVisibleToRole($user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'manajer', 'pengawas']);
    }
}
