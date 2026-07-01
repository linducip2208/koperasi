<?php

namespace App\Filament\Widgets;

use App\Models\KasTransaksi;
use App\Models\SimpananTransaksi;
use App\Models\TokoPenjualan;
use Filament\Widgets\Widget;

class KasirTodayWidget extends Widget
{
    use DashboardWidgetFilter;

    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.kasir-today';

    protected static function isVisibleToRole($user): bool
    {
        return $user->hasAnyRole(['kasir', 'super-admin', 'admin']);
    }

    public function getViewData(): array
    {
        $today = now()->toDateString();

        $transaksiHarian = SimpananTransaksi::whereDate('tanggal', $today)->count();
        $totalSetoran = (int) SimpananTransaksi::whereDate('tanggal', $today)
            ->where('jenis', 'setor')->sum('jumlah');
        $totalPenarikan = (int) SimpananTransaksi::whereDate('tanggal', $today)
            ->where('jenis', 'tarik')->sum('jumlah');

        $penjualanHarian = (int) TokoPenjualan::whereDate('tanggal', $today)->sum('total');

        $kasMasuk = (int) KasTransaksi::whereDate('tanggal', $today)
            ->where('jenis', 'masuk')->sum('jumlah');
        $kasKeluar = (int) KasTransaksi::whereDate('tanggal', $today)
            ->where('jenis', 'keluar')->sum('jumlah');

        $pembayaranHarian = (int) \App\Models\PinjamanPembayaran::whereDate('tanggal_bayar', $today)->sum('total');

        return compact(
            'transaksiHarian', 'totalSetoran', 'totalPenarikan',
            'penjualanHarian', 'kasMasuk', 'kasKeluar', 'pembayaranHarian'
        );
    }
}
