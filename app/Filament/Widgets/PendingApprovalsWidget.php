<?php

namespace App\Filament\Widgets;

use App\Models\Pinjaman;
use App\Filament\Widgets\DashboardWidgetFilter;
use Filament\Widgets\Widget;

class PendingApprovalsWidget extends Widget
{
    use DashboardWidgetFilter;

    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 1;
    protected static string $view = 'filament.widgets.pending-approvals';

    public function getViewData(): array
    {
        $pengajuan = Pinjaman::with('anggota', 'produk')
            ->where('status', 'pengajuan')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return [
            'pengajuan'  => $pengajuan,
            'totalCount' => Pinjaman::where('status', 'pengajuan')->count(),
            'totalNilai' => (int) Pinjaman::where('status', 'pengajuan')->sum('plafon'),
        ];
    }

    protected static function isVisibleToRole($user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'manajer', 'pengawas']);
    }
}
