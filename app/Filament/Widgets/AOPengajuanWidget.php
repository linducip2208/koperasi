<?php

namespace App\Filament\Widgets;

use App\Models\Pinjaman;
use Filament\Widgets\Widget;

class AOPengajuanWidget extends Widget
{
    use DashboardWidgetFilter;

    protected static ?int $sort = 7;
    protected int|string|array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.ao-pengajuan';

    protected static function isVisibleToRole($user): bool
    {
        return $user->hasAnyRole(['ao', 'super-admin', 'admin', 'manajer']);
    }

    public function getViewData(): array
    {
        $pengajuan = Pinjaman::with(['anggota', 'produk'])
            ->where('status', 'pengajuan')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $totalPengajuan = Pinjaman::where('status', 'pengajuan')->count();
        $totalNilai = (int) Pinjaman::where('status', 'pengajuan')->sum('plafon');

        $disetujuiBulanIni = Pinjaman::where('status', '!=', 'pengajuan')
            ->where('status', '!=', 'ditolak')
            ->whereMonth('created_at', now()->month)
            ->count();

        return compact('pengajuan', 'totalPengajuan', 'totalNilai', 'disetujuiBulanIni');
    }
}
