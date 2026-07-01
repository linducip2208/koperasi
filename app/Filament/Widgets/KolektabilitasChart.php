<?php

namespace App\Filament\Widgets;

use App\Models\Pinjaman;
use App\Filament\Widgets\DashboardWidgetFilter;
use Filament\Widgets\ChartWidget;

class KolektabilitasChart extends ChartWidget
{
    use DashboardWidgetFilter;

    protected static ?string $heading = '🎯 Distribusi Kolektabilitas Pinjaman';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 1;
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $data = Pinjaman::whereIn('status', ['aktif', 'macet'])
            ->selectRaw('kolektabilitas, COUNT(*) as count')
            ->groupBy('kolektabilitas')
            ->pluck('count', 'kolektabilitas');

        $labels = ['lancar' => 'Lancar', 'dpk' => 'DPK', 'kurang_lancar' => 'Kurang Lancar', 'diragukan' => 'Diragukan', 'macet' => 'Macet'];
        $colors = ['#10b981', '#f59e0b', '#f97316', '#ef4444', '#dc2626'];

        return [
            'datasets' => [[
                'label' => 'Pinjaman',
                'data' => array_map(fn ($k) => $data[$k] ?? 0, array_keys($labels)),
                'backgroundColor' => $colors,
                'borderColor' => '#fff',
                'borderWidth' => 2,
            ]],
            'labels' => array_values($labels),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'right'],
            ],
            'cutout' => '65%',
        ];
    }

    protected static function isVisibleToRole($user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'manajer', 'pengawas']);
    }
}
