<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Filament\Widgets\DashboardWidgetFilter;
use Filament\Widgets\Widget;

class StatsKoperasi extends Widget
{
    use DashboardWidgetFilter;
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.stats-koperasi';

    public function getStats(): array
    {
        $totalAnggotaAktif = Anggota::where('status', 'aktif')->count();
        $totalSimpanan     = (int) Simpanan::where('status', 'aktif')->sum('saldo');
        $totalPinjaman     = (int) Pinjaman::whereIn('status', ['aktif', 'macet'])->sum('saldo_pokok');
        $tunggakan         = (int) Pinjaman::whereIn('status', ['aktif', 'macet'])
            ->where('tunggakan_hari', '>', 0)->sum('saldo_pokok');
        $npl               = $totalPinjaman > 0 ? round(($tunggakan / $totalPinjaman) * 100, 2) : 0;

        // Tren 12 bulan nyata dari database (bukan dummy).
        $anggotaTrend  = $this->monthlySeries(Anggota::class, 'created_at', 'count');
        $setoranTrend  = $this->monthlySeries(\App\Models\SimpananTransaksi::class, 'tanggal', 'sum_jumlah', ['jenis' => 'setor']);
        $pinjamanTrend = $this->monthlySeries(Pinjaman::class, 'tanggal_pengajuan', 'sum_plafon');

        $anggotaChange  = $this->momChange($anggotaTrend);
        $setoranChange  = $this->momChange($setoranTrend);
        $pinjamanChange = $this->momChange($pinjamanTrend);

        return [
            [
                'label'    => 'Anggota Aktif',
                'value'    => number_format($totalAnggotaAktif),
                'sub'      => 'Total terdaftar',
                'change'   => $anggotaChange['label'],
                'positive' => $anggotaChange['positive'],
                'color'    => 'emerald',
                'gradient' => 'from-emerald-500 to-teal-500',
                'spark'    => '#10b981',
                'data'     => $anggotaTrend,
                'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>',
            ],
            [
                'label'    => 'Total Simpanan',
                'value'    => 'Rp ' . self::format($totalSimpanan),
                'sub'      => 'Setoran 12 bln terakhir (tren)',
                'change'   => $setoranChange['label'],
                'positive' => $setoranChange['positive'],
                'color'    => 'cyan',
                'gradient' => 'from-cyan-500 to-blue-500',
                'spark'    => '#06b6d4',
                'data'     => $setoranTrend,
                'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>',
            ],
            [
                'label'    => 'Pinjaman Berjalan',
                'value'    => 'Rp ' . self::format($totalPinjaman),
                'sub'      => 'Plafon cair 12 bln terakhir (tren)',
                'change'   => $pinjamanChange['label'],
                'positive' => $pinjamanChange['positive'],
                'color'    => 'amber',
                'gradient' => 'from-amber-500 to-orange-500',
                'spark'    => '#f59e0b',
                'data'     => $pinjamanTrend,
                'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>',
            ],
            [
                'label'    => 'NPL Ratio',
                'value'    => $npl . '%',
                'sub'      => $npl > 5 ? 'Perlu perhatian' : 'Sehat — Lancar',
                'change'   => $npl > 5 ? 'Risiko' : 'Aman',
                'positive' => $npl <= 5,
                'color'    => $npl > 5 ? 'rose' : 'emerald',
                'gradient' => $npl > 5 ? 'from-rose-500 to-red-500' : 'from-emerald-500 to-teal-500',
                'spark'    => $npl > 5 ? '#f43f5e' : '#10b981',
                'data'     => $pinjamanTrend,
                'icon'     => $npl > 5
                    ? '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
            ],
        ];
    }

    /** Deret 12 bulan: count / sum(jumlah) / sum(plafon) per bulan kalender. */
    private function monthlySeries(string $model, string $dateCol, string $mode, array $where = []): array
    {
        $out = [];
        for ($i = 11; $i >= 0; $i--) {
            $start = now()->startOfMonth()->subMonths($i);
            $end = (clone $start)->endOfMonth();
            $q = $model::whereBetween($dateCol, [$start, $end]);
            foreach ($where as $k => $v) $q->where($k, $v);
            $out[] = match ($mode) {
                'count' => (int) (clone $q)->count(),
                'sum_jumlah' => (int) (clone $q)->sum('jumlah'),
                'sum_plafon' => (int) (clone $q)->sum('plafon'),
                default => 0,
            };
        }
        return $out;
    }

    /** Month-over-month: bulan terakhir vs sebelumnya. */
    private function momChange(array $series): array
    {
        $last = (float) ($series[11] ?? 0);
        $prev = (float) ($series[10] ?? 0);
        if ($prev <= 0) {
            return ['label' => $last > 0 ? 'baru ▲' : '—', 'positive' => $last >= 0];
        }
        $pct = round(($last - $prev) / $prev * 100, 1);
        return [
            'label' => ($pct >= 0 ? '+' : '') . $pct . '% MoM',
            'positive' => $pct >= 0,
        ];
    }

    private static function format(int $n): string
    {
        if ($n >= 1_000_000_000) return number_format($n / 1_000_000_000, 2, ',', '.') . ' M';
        if ($n >= 1_000_000)     return number_format($n / 1_000_000, 1, ',', '.') . ' jt';
        if ($n >= 1_000)         return number_format($n / 1_000, 0, ',', '.') . ' rb';
        return number_format($n, 0, ',', '.');
    }
}
