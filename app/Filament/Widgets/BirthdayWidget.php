<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use App\Filament\Widgets\DashboardWidgetFilter;
use Filament\Widgets\Widget;

class BirthdayWidget extends Widget
{
    use DashboardWidgetFilter;
    protected static ?int $sort = 7;
    protected int|string|array $columnSpan = 1;
    protected static string $view = 'filament.widgets.birthday';

    public function getViewData(): array
    {
        $month = now()->month;
        $birthdays = Anggota::where('status', 'aktif')
            ->whereNotNull('tanggal_lahir')
            ->whereMonth('tanggal_lahir', $month)
            ->orderByRaw('DAY(tanggal_lahir)')
            ->limit(8)
            ->get();

        $today = Anggota::where('status', 'aktif')
            ->whereNotNull('tanggal_lahir')
            ->whereMonth('tanggal_lahir', $month)
            ->whereDay('tanggal_lahir', now()->day)
            ->count();

        return [
            'birthdays'  => $birthdays,
            'totalMonth' => Anggota::where('status', 'aktif')
                ->whereNotNull('tanggal_lahir')
                ->whereMonth('tanggal_lahir', $month)
                ->count(),
            'todayCount' => $today,
        ];
    }
}
