<?php

namespace App\Filament\Widgets;

use App\Models\PinjamanJadwal;
use App\Filament\Widgets\DashboardWidgetFilter;
use Filament\Widgets\Widget;

class UpcomingPaymentsWidget extends Widget
{
    use DashboardWidgetFilter;
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 1;
    protected static string $view = 'filament.widgets.upcoming-payments';

    public function getViewData(): array
    {
        $weekFromNow = now()->addDays(7);
        $upcoming = PinjamanJadwal::with('pinjaman.anggota')
            ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo'])
            ->whereBetween('tanggal_jatuh_tempo', [now()->toDateString(), $weekFromNow->toDateString()])
            ->orderBy('tanggal_jatuh_tempo')
            ->limit(5)
            ->get();

        $totalAmount = (int) PinjamanJadwal::whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo'])
            ->whereBetween('tanggal_jatuh_tempo', [now()->toDateString(), $weekFromNow->toDateString()])
            ->sum('total_angsuran');

        $overdueCount = PinjamanJadwal::whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])
            ->where('tanggal_jatuh_tempo', '<', now()->toDateString())
            ->count();

        return [
            'upcoming'     => $upcoming,
            'totalAmount'  => $totalAmount,
            'overdueCount' => $overdueCount,
        ];
    }
}
