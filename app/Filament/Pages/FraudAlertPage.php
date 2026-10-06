<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\Jurnal;
use App\Models\PinjamanPembayaran;
use App\Models\SimpananTransaksi;
use Filament\Pages\Page;

class FraudAlertPage extends Page
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $navigationLabel = 'Fraud Alerts';
    protected static ?string $title = 'Deteksi Anomali (Rule-based)';
    protected static ?int $navigationSort = 60;

    protected static string $view = 'filament.pages.fraud-alert';

    public function getViewData(): array
    {
        $ambangBesar = 50_000_000;
        $alerts = [];

        $besar = SimpananTransaksi::with('simpanan.anggota')->where('jenis', 'tarik')
            ->where('jumlah', '>=', $ambangBesar)->orderByDesc('id')->limit(20)->get()
            ->map(fn ($t) => ['waktu' => $t->created_at, 'info' => "Tarik Rp ".number_format($t->jumlah, 0, ',', '.')." — ".($t->simpanan->anggota->nama ?? '?')." ({$t->nomor})"]);
        if ($besar->isNotEmpty()) $alerts[] = ['severity' => 'HIGH', 'judul' => 'Penarikan besar (≥ Rp 50 jt)', 'items' => $besar];

        $hourExpr = \Illuminate\Support\Facades\DB::getDriverName() === 'sqlite'
            ? "strftime('%H', created_at) NOT BETWEEN '06' AND '21'"
            : 'HOUR(created_at) NOT BETWEEN 6 AND 21';
        $malam = SimpananTransaksi::whereRaw($hourExpr)
            ->whereDate('created_at', '>=', now()->subDays(7)->toDateString())->orderByDesc('id')->limit(20)->get()
            ->map(fn ($t) => ['waktu' => $t->created_at, 'info' => "{$t->jenis} Rp ".number_format($t->jumlah, 0, ',', '.')." ({$t->nomor})"]);
        if ($malam->isNotEmpty()) $alerts[] = ['severity' => 'MEDIUM', 'judul' => 'Transaksi luar jam operasional (7 hari)', 'items' => $malam];

        $duplikat = SimpananTransaksi::selectRaw('jumlah, simpanan_id, COUNT(*) c')->whereDate('created_at', '>=', now()->subDays(7)->toDateString())
            ->groupBy('jumlah', 'simpanan_id')->having('c', '>', 2)->limit(10)->get()
            ->map(fn ($r) => ['waktu' => null, 'info' => "Rp ".number_format($r->jumlah, 0, ',', '.')." × {$r->c} pada rekening #{$r->simpanan_id}"]);
        if ($duplikat->isNotEmpty()) $alerts[] = ['severity' => 'MEDIUM', 'judul' => 'Nominal identik berulang (>2x/minggu)', 'items' => $duplikat];

        $reversal = Jurnal::where('tipe', 'balik')->whereDate('created_at', '>=', now()->subDays(30)->toDateString())->orderByDesc('id')->limit(20)->get()
            ->map(fn ($j) => ['waktu' => $j->created_at, 'info' => "{$j->nomor} — {$j->keterangan}"]);
        if ($reversal->count() > 5) $alerts[] = ['severity' => 'LOW', 'judul' => 'Reversal tinggi 30 hari ('.$reversal->count().'x)', 'items' => $reversal->take(10)];

        return ['alerts' => $alerts];
    }
}
