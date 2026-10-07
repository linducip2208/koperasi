<?php

namespace App\Filament\Pages;

use App\Domain\Akuntansi\LaporanKeuanganService;
use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\PinjamanJadwal;
use App\Models\PinjamanPembayaran;
use App\Models\Simpanan;
use App\Models\SimpananTransaksi;
use Filament\Pages\Page;

class ExecutiveDashboardPage extends Page
{
    use HasRoleAccess;
    use HasTranslatedNav;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navKey = 'Executive Dashboard';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $navigationLabel = 'Executive Dashboard';
    protected static ?string $title = 'Executive Dashboard';
    protected static ?int $navigationSort = 49;

    protected static string $view = 'filament.pages.executive-dashboard';

    public string $periode = 'year';

    public function getViewData(): array
    {
        [$dari, $sampai, $label] = $this->range();
        $neraca = LaporanKeuanganService::neraca($sampai);
        $lr = LaporanKeuanganService::labaRugi($dari, $sampai);
        $kas = LaporanKeuanganService::arusKas($dari, $sampai);

        $aset = collect($neraca['aset'])->sum('saldo');
        $ekuitas = collect($neraca['ekuitas'])->sum('saldo');
        $simpanan = (int) Simpanan::where('status', 'aktif')->sum('saldo');
        $outstanding = (int) Pinjaman::whereIn('status', ['aktif', 'macet'])->sum('saldo_pokok');
        $tunggakan = (int) Pinjaman::whereIn('status', ['aktif', 'macet'])->where('tunggakan_hari', '>', 0)->sum('saldo_pokok');
        $anggotaAktif = Anggota::where('status', 'aktif')->count();
        $anggotaTotal = Anggota::count();
        $par = $outstanding > 0 ? round($tunggakan / $outstanding * 100, 2) : 0;

        $jt = (int) PinjamanJadwal::whereDate('tanggal_jatuh_tempo', '>=', $dari)->whereDate('tanggal_jatuh_tempo', '<=', $sampai)->sum('total_angsuran');
        $terkumpul = (int) PinjamanPembayaran::where('status', 'disetujui')->whereDate('tanggal', '>=', $dari)->whereDate('tanggal', '<=', $sampai)->sum('total_bayar');
        $collection = $jt > 0 ? round($terkumpul / $jt * 100, 2) : 0;

        // Tren 12 bulan: aset proxy (setor neto + plafon cair), anggota baru, SHU proxy.
        $labels = $mAset = $mSimp = $mPinj = $mAnggota = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->startOfMonth()->subMonths($i);
            $e = (clone $m)->endOfMonth();
            $labels[] = $m->format('M y');
            $setor = (int) SimpananTransaksi::where('jenis', 'setor')->whereDate('tanggal', '>=', $m->toDateString())->whereDate('tanggal', '<=', $e->toDateString())->sum('jumlah');
            $tarik = (int) SimpananTransaksi::where('jenis', 'tarik')->whereDate('tanggal', '>=', $m->toDateString())->whereDate('tanggal', '<=', $e->toDateString())->sum('jumlah');
            $mSimp[] = $setor - $tarik;
            $mPinj[] = (int) Pinjaman::whereDate('tanggal_pencairan', '>=', $m->toDateString())->whereDate('tanggal_pencairan', '<=', $e->toDateString())->sum('plafon');
            $mAnggota[] = Anggota::whereDate('tanggal_masuk', '>=', $m->toDateString())->whereDate('tanggal_masuk', '<=', $e->toDateString())->count();
            $mAset[] = ($setor - $tarik);
        }

        // Today's actions — semua dari database nyata.
        $actions = [];
        $pinjamanPending = Pinjaman::where('status', 'pengajuan')->count();
        if ($pinjamanPending > 0) $actions[] = ['label' => "{$pinjamanPending} pinjaman menunggu approval", 'url' => route('filament.admin.pages.approval-center', ['tab' => 'pinjaman']), 'level' => 'warning'];
        $bayarPending = \App\Models\PinjamanPembayaran::where('status', 'pending')->count();
        if ($bayarPending > 0) $actions[] = ['label' => "{$bayarPending} pembayaran menunggu verifikasi", 'url' => route('filament.admin.pages.approval-center', ['tab' => 'pembayaran']), 'level' => 'warning'];
        $calonAnggota = Anggota::where('status', 'calon')->count();
        if ($calonAnggota > 0) $actions[] = ['label' => "{$calonAnggota} calon anggota menunggu aktivasi", 'url' => route('filament.admin.pages.approval-center', ['tab' => 'anggota']), 'level' => 'info'];
        $overdueToday = \App\Models\PinjamanJadwal::whereDate('tanggal_jatuh_tempo', now()->toDateString())->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo'])->count();
        if ($overdueToday > 0) $actions[] = ['label' => "{$overdueToday} angsuran jatuh tempo hari ini", 'url' => route('filament.admin.pages.collection-center'), 'level' => 'danger'];
        $dokExpiring = \App\Models\MemberDocument::where('status', 'aktif')->whereDate('tanggal_kedaluwarsa', '<=', now()->addDays(30)->toDateString())->count();
        if ($dokExpiring > 0) $actions[] = ['label' => "{$dokExpiring} dokumen kedaluwarsa ≤ 30 hari", 'url' => '/admin/member-documents', 'level' => 'warning'];
        $kritikal = \App\Models\AuditFinding::where('severity', 'critical')->whereIn('status', ['open', 'progress'])->count();
        if ($kritikal > 0) $actions[] = ['label' => "{$kritikal} temuan audit critical terbuka", 'url' => '/admin/audit-findings', 'level' => 'danger'];
        try {
            $st = app(\App\Services\LicenseClient::class)->status(strtolower(request()->getHost()));
            if (! in_array($st['status'], ['ACTIVE'], true)) $actions[] = ['label' => 'Lisensi: '.$st['status'], 'url' => '/admin/license-page', 'level' => 'danger'];
        } catch (\Throwable) {
        }

        return [
            'label' => $label,
            'actions' => $actions,
            'kpi' => [
                ['label' => 'Total Anggota', 'value' => number_format($anggotaTotal), 'sub' => $anggotaAktif.' aktif'],
                ['label' => 'Total Aset', 'value' => 'Rp '.number_format($aset, 0, ',', '.'), 'sub' => 'Ekuitas Rp '.number_format($ekuitas, 0, ',', '.')],
                ['label' => 'Total Simpanan', 'value' => 'Rp '.number_format($simpanan, 0, ',', '.'), 'sub' => 'Dana pihak anggota'],
                ['label' => 'Outstanding', 'value' => 'Rp '.number_format($outstanding, 0, ',', '.'), 'sub' => 'Pinjaman berjalan'],
                ['label' => 'Pendapatan', 'value' => 'Rp '.number_format($lr['total_pendapatan'], 0, ',', '.'), 'sub' => 'Beban Rp '.number_format($lr['total_beban'], 0, ',', '.')],
                ['label' => 'SHU Periode', 'value' => 'Rp '.number_format($lr['shu'], 0, ',', '.'), 'sub' => 'Sebelum pajak'],
                ['label' => 'Tunggakan', 'value' => 'Rp '.number_format($tunggakan, 0, ',', '.'), 'sub' => "PAR {$par}%"],
                ['label' => 'Collection Rate', 'value' => $collection.'%', 'sub' => 'Terkumpul vs jatuh tempo'],
                ['label' => 'Kas Bersih', 'value' => 'Rp '.number_format($kas['net'], 0, ',', '.'), 'sub' => 'Masuk − keluar'],
            ],
            'charts' => [
                ['type' => 'line', 'title' => 'Arus Simpanan Neto (12 bln)', 'labels' => $labels,
                    'datasets' => [['label' => 'Neto Rp', 'data' => $mSimp, 'color' => '#059669']]],
                ['type' => 'bar', 'title' => 'Plafon Cair per Bulan', 'labels' => $labels,
                    'datasets' => [['label' => 'Rp', 'data' => $mPinj, 'color' => '#d97706']]],
                ['type' => 'bar', 'title' => 'Anggota Baru per Bulan', 'labels' => $labels,
                    'datasets' => [['label' => 'Orang', 'data' => $mAnggota, 'color' => '#4f46e5']]],
            ],
        ];
    }

    protected function range(): array
    {
        return match ($this->periode) {
            'today' => [now()->toDateString(), now()->toDateString(), 'Hari ini'],
            'week' => [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString(), 'Minggu ini'],
            'month' => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString(), 'Bulan ini'],
            'quarter' => [now()->firstOfQuarter()->toDateString(), now()->lastOfQuarter()->toDateString(), 'Kuartal ini'],
            'last_year' => [now()->subYear()->startOfYear()->toDateString(), now()->subYear()->endOfYear()->toDateString(), 'Tahun lalu'],
            default => [now()->startOfYear()->toDateString(), now()->toDateString(), 'Tahun berjalan'],
        };
    }
}
