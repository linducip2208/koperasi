<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Filament\Widgets\DashboardWidgetFilter;
use Filament\Widgets\Widget;

class HeroBanner extends Widget
{
    use DashboardWidgetFilter;
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.hero-banner';

    public function getViewData(): array
    {
        $user = auth()->user();
        $hour = (int) now()->format('H');
        $greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));

        $totalSimpanan  = (int) Simpanan::where('status', 'aktif')->sum('saldo');
        $pinjamanAktif  = Pinjaman::whereIn('status', ['aktif', 'macet'])->count();
        $anggotaAktif   = Anggota::where('status', 'aktif')->count();

        return [
            'greeting'      => $greeting,
            'name'          => $user?->name ?? 'Admin',
            'date'          => now()->translatedFormat('l, d F Y'),
            'totalSimpanan' => $totalSimpanan,
            'pinjamanAktif' => $pinjamanAktif,
            'anggotaAktif'  => $anggotaAktif,
        ];
    }
}
