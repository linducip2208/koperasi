<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\PinjamanJadwal;
use App\Models\Simpanan;
use App\Models\SimpananTransaksi;
use Filament\Pages\Dashboard as BaseDashboard;

class KustomDashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Dasbor';
    protected static ?int $navigationSort = -100;
    protected static string $view = 'filament.pages.kustom-dashboard';
    protected static ?string $title = 'Dashboard';

    public function getColumns(): int|string|array
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [];
    }

    public function getVisibleWidgets(): array
    {
        return [];
    }

    public function getViewData(): array
    {
        $user = auth()->user();
        $hour = (int) now()->format('H');
        $greet = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));

        // KPI utama
        $totalAnggota = (int) Anggota::where('status', 'aktif')->count();
        $totalSimpanan = (int) Simpanan::where('status', 'aktif')->sum('saldo');
        $totalPinjaman = (int) Pinjaman::whereIn('status', ['aktif', 'macet', 'cair'])->sum('saldo_pokok');
        $tunggakan = (int) Pinjaman::whereIn('status', ['aktif', 'macet'])->where('tunggakan_hari', '>', 0)->sum('saldo_pokok');
        $npl = $totalPinjaman > 0 ? round(($tunggakan / $totalPinjaman) * 100, 2) : 0;
        $totalAset = $totalSimpanan + $totalPinjaman;

        // Pertumbuhan bulan lalu vs bulan ini
        $simpananBulanIni = (int) SimpananTransaksi::where('jenis', 'setor')
            ->whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->sum('jumlah');
        $simpananBulanLalu = (int) SimpananTransaksi::where('jenis', 'setor')
            ->whereYear('tanggal', now()->subMonth()->year)->whereMonth('tanggal', now()->subMonth()->month)->sum('jumlah');
        $simpananGrowth = $simpananBulanLalu > 0
            ? round((($simpananBulanIni - $simpananBulanLalu) / $simpananBulanLalu) * 100, 1)
            : 0;

        // Pengajuan tertunda
        $pengajuanCount = Pinjaman::where('status', 'pengajuan')->count();
        $pengajuanNilai = (int) Pinjaman::where('status', 'pengajuan')->sum('plafon');

        // Cicilan jatuh tempo minggu ini
        $cicilanMingguIni = (int) PinjamanJadwal::whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo'])
            ->whereBetween('tanggal_jatuh_tempo', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->sum('total_angsuran');

        // Distribusi kolektabilitas
        $kolektabilitas = Pinjaman::whereIn('status', ['aktif', 'macet', 'cair'])
            ->selectRaw('kolektabilitas, COUNT(*) as count')
            ->groupBy('kolektabilitas')
            ->pluck('count', 'kolektabilitas')
            ->toArray();
        $kolMap = [
            'lancar'        => $kolektabilitas['lancar'] ?? 0,
            'dpk'           => $kolektabilitas['dpk'] ?? 0,
            'kurang_lancar' => $kolektabilitas['kurang_lancar'] ?? 0,
            'diragukan'     => $kolektabilitas['diragukan'] ?? 0,
            'macet'         => $kolektabilitas['macet'] ?? 0,
        ];
        $totalKol = array_sum($kolMap) ?: 1;

        // Tren 12 bulan untuk chart
        $trendData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $setor = (int) SimpananTransaksi::where('jenis', 'setor')
                ->whereYear('tanggal', $date->year)->whereMonth('tanggal', $date->month)->sum('jumlah');
            $trendData[] = ['label' => $date->format('M'), 'value' => $setor];
        }

        // Aktivitas terbaru
        $aktivitas = SimpananTransaksi::with('simpanan.anggota')
            ->latest('id')
            ->limit(8)
            ->get()
            ->map(fn ($t) => [
                'tanggal'   => \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M'),
                'nama'      => $t->simpanan?->anggota?->nama ?? 'Anggota',
                'jenis'     => $t->jenis,
                'jumlah'    => $t->jumlah,
            ]);

        // Pengajuan tertunda detail
        $pengajuan = Pinjaman::with(['anggota', 'produk'])
            ->where('status', 'pengajuan')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Birthday
        $bdayBulan = Anggota::where('status', 'aktif')
            ->whereNotNull('tanggal_lahir')
            ->whereMonth('tanggal_lahir', now()->month)
            ->orderByRaw('DAY(tanggal_lahir)')
            ->limit(5)
            ->get();
        $bdayHariIni = Anggota::where('status', 'aktif')
            ->whereNotNull('tanggal_lahir')
            ->whereMonth('tanggal_lahir', now()->month)
            ->whereDay('tanggal_lahir', now()->day)
            ->count();

        // Top peminjam
        $topPeminjam = Pinjaman::with('anggota', 'produk')
            ->whereIn('status', ['aktif', 'cair'])
            ->orderByDesc('saldo_pokok')
            ->limit(5)
            ->get();

        $health = $this->buildSystemHealth();

        return compact(
            'greet', 'user', 'totalAnggota', 'totalSimpanan', 'totalPinjaman',
            'tunggakan', 'npl', 'totalAset', 'simpananGrowth', 'pengajuanCount',
            'pengajuanNilai', 'cicilanMingguIni', 'kolMap', 'totalKol',
            'trendData', 'aktivitas', 'pengajuan', 'bdayBulan', 'bdayHariIni', 'topPeminjam',
            'health'
        );
    }

    private function buildSystemHealth(): array
    {
        $waDriver = (string) config('services.whatsapp.driver', 'log');
        $waToken  = (string) config('services.whatsapp.token', '');
        $waOk     = $waDriver !== 'log' && !empty($waToken);

        $backupDir = storage_path('app/koperasi-backups');
        $lastBackup = null;
        $backupOk = false;
        if (is_dir($backupDir)) {
            $files = collect(scandir($backupDir))
                ->filter(fn ($f) => str_ends_with($f, '.zip'))
                ->map(fn ($f) => ['name' => $f, 'mtime' => filemtime($backupDir . DIRECTORY_SEPARATOR . $f)])
                ->sortByDesc('mtime')
                ->values();
            if ($files->isNotEmpty()) {
                $lastBackup = \Carbon\Carbon::createFromTimestamp($files->first()['mtime']);
                $backupOk   = $lastBackup->diffInDays(now()) < 2;
            }
        }

        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            $dbOk = true;
        } catch (\Throwable $e) {
            $dbOk = false;
        }

        $sessionDriver = (string) config('session.driver');
        $sessionsActive = $sessionDriver === 'database'
            ? (int) \Illuminate\Support\Facades\DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(30)->timestamp)
                ->count()
            : 0;

        $tenant = \App\Models\Tenant::find(1);
        $licenseStatus = $tenant?->status ?? 'tidak_aktif';

        return [
            ['label' => 'Database',       'icon' => '🗄️', 'ok' => $dbOk,      'detail' => $dbOk ? 'Connected (' . config('database.default') . ')' : 'Connection FAILED'],
            ['label' => 'WhatsApp',       'icon' => '📱', 'ok' => $waOk,      'detail' => $waOk ? 'Driver: ' . $waDriver : 'Mode dev (log only)'],
            ['label' => 'Backup',         'icon' => '💾', 'ok' => $backupOk,  'detail' => $lastBackup ? 'Terakhir: ' . $lastBackup->diffForHumans() : 'Belum ada backup'],
            ['label' => 'License',        'icon' => '🔐', 'ok' => $licenseStatus === 'aktif', 'detail' => 'Status: ' . ucfirst(str_replace('_', ' ', $licenseStatus))],
            ['label' => 'Sesi Aktif',     'icon' => '👥', 'ok' => true,       'detail' => $sessionsActive . ' user (30 menit terakhir)'],
            ['label' => 'Mode Operasi',   'icon' => '⚙️', 'ok' => true,       'detail' => ucfirst($tenant?->operation_mode ?? 'konvensional')],
        ];
    }
}
