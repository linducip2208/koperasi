<?php

namespace App\Filament\Widgets;

use App\Models\PinjamanPembayaran;
use App\Models\SimpananTransaksi;
use App\Filament\Widgets\DashboardWidgetFilter;
use Filament\Widgets\Widget;

class AktivitasTerbaru extends Widget
{
    use DashboardWidgetFilter;
    protected static string $view = 'filament.widgets.aktivitas-terbaru';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $simpanan = SimpananTransaksi::with('simpanan.anggota')
            ->orderByDesc('created_at')->limit(5)->get()
            ->map(fn ($t) => [
                'icon'   => $t->jenis === 'setor' ? '💰' : '📤',
                'color'  => $t->jenis === 'setor' ? 'emerald' : 'amber',
                'title'  => $t->simpanan?->anggota?->nama ?? 'Anggota',
                'desc'   => ucfirst($t->jenis) . ' simpanan ' . ($t->simpanan?->produk?->nama ?? ''),
                'amount' => 'Rp ' . number_format($t->jumlah, 0, ',', '.'),
                'time'   => $t->created_at->diffForHumans(),
            ]);

        $pinjaman = PinjamanPembayaran::with('pinjaman.anggota')
            ->orderByDesc('created_at')->limit(5)->get()
            ->map(fn ($p) => [
                'icon'   => '🏦',
                'color'  => 'cyan',
                'title'  => $p->pinjaman?->anggota?->nama ?? 'Anggota',
                'desc'   => 'Pembayaran angsuran ' . $p->pinjaman?->nomor_akad,
                'amount' => 'Rp ' . number_format($p->total_bayar, 0, ',', '.'),
                'time'   => $p->created_at->diffForHumans(),
            ]);

        return [
            'aktivitas' => $simpanan->concat($pinjaman)->sortByDesc('time')->take(8),
        ];
    }
}
