<?php

namespace App\Filament\Widgets;

use App\Models\PinjamanJadwal;
use Filament\Widgets\Widget;

class KolektorTagihanWidget extends Widget
{
    use DashboardWidgetFilter;

    protected static ?int $sort = 8;
    protected int|string|array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.kolektor-tagihan';

    protected static function isVisibleToRole($user): bool
    {
        return $user->hasAnyRole(['kolektor', 'super-admin', 'admin', 'manajer']);
    }

    public function getViewData(): array
    {
        $today = now()->toDateString();

        $tagihanHariIni = PinjamanJadwal::with(['pinjaman.anggota', 'pinjaman.produk'])
            ->whereDate('tanggal_jatuh_tempo', $today)
            ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'tertunggak'])
            ->orderBy('tanggal_jatuh_tempo')
            ->get();

        $totalTagihan = (int) $tagihanHariIni->sum('total_angsuran');
        $jumlahTagihan = $tagihanHariIni->count();

        $tertunggak = PinjamanJadwal::with(['pinjaman.anggota'])
            ->whereIn('status', ['tertunggak'])
            ->whereDate('tanggal_jatuh_tempo', '<', $today)
            ->orderBy('tanggal_jatuh_tempo')
            ->limit(10)
            ->get();
        $totalTunggakan = (int) $tertunggak->sum('total_angsuran');

        $mingguIni = PinjamanJadwal::whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo'])
            ->whereBetween('tanggal_jatuh_tempo', [$today, now()->addDays(7)->toDateString()])
            ->count();

        return compact(
            'tagihanHariIni', 'totalTagihan', 'jumlahTagihan',
            'tertunggak', 'totalTunggakan', 'mingguIni'
        );
    }
}
