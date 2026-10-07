<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use App\Models\Jurnal;
use App\Models\PeriodeAkuntansi;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class YearEndClosingPage extends Page
{
    use HasRoleAccess;
    use HasTranslatedNav;

    protected static ?string $permissionModule = 'jurnal';
    protected static ?string $navKey = 'Tutup Tahun Buku';
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationGroup = 'ACCOUNTING';
    protected static ?string $navigationLabel = 'Tutup Tahun Buku';
    protected static ?string $title = 'Tutup Tahun Buku';
    protected static ?int $navigationSort = 45;

    protected static string $view = 'filament.pages.year-end-closing';

    public int $tahun;

    public function mount(): void
    {
        $this->tahun = (int) (request()->query('tahun', now()->year));
    }

    public function getViewData(): array
    {
        $dari = "{$this->tahun}-01-01";
        $sampai = "{$this->tahun}-12-31";

        $checks = [
            ['label' => 'Tidak ada jurnal draft (semua posted)', 'ok' => Jurnal::where('is_posted', false)->whereDate('tanggal', '>=', $dari)->whereDate('tanggal', '<=', $sampai)->count() === 0,
                'detail' => Jurnal::where('is_posted', false)->whereDate('tanggal', '>=', $dari)->whereDate('tanggal', '<=', $sampai)->count().' draft'],
            ['label' => 'Semua jurnal balance', 'ok' => Jurnal::whereDate('tanggal', '>=', $dari)->whereDate('tanggal', '<=', $sampai)->whereColumn('total_debit', '!=', 'total_kredit')->count() === 0, 'detail' => 'debit = kredit'],
            ['label' => 'Kolektabilitas mutakhir', 'ok' => true, 'detail' => 'jalankan update-kolektabilitas sebelum tutup'],
            ['label' => 'Penyusutan aset tahun berjalan', 'ok' => true, 'detail' => 'jalankan penyusutan-aset'],
            ['label' => 'Backup terbaru tersedia', 'ok' => true, 'detail' => 'wajib backup sebelum lock (tombol di bawah)'],
        ];

        $periodes = PeriodeAkuntansi::where('tahun', $this->tahun)->orderBy('bulan')->get();
        $allClosed = $periodes->isNotEmpty() && $periodes->every(fn ($p) => $p->status === 'closed');

        return [
            'checks' => $checks,
            'periodes' => $periodes,
            'allClosed' => $allClosed,
            'simpananAktif' => Simpanan::where('status', 'aktif')->count(),
            'pinjamanAktif' => Pinjaman::whereIn('status', ['aktif', 'macet'])->count(),
        ];
    }

    public function runJob(string $job): void
    {
        $allow = ['koperasi:update-kolektabilitas' => 1, 'koperasi:penyusutan-aset' => 1, 'app:backup' => 1];
        abort_unless(isset($allow[$job]), 422);
        Artisan::call($job);
        Notification::make()->title("Job {$job} selesai.")->success()->send();
    }

    public function lockYear(): void
    {
        foreach (PeriodeAkuntansi::where('tahun', $this->tahun)->where('status', '!=', 'closed')->get() as $p) {
            $p->update(['status' => 'closed', 'closed_at' => now(), 'closed_by' => auth()->id()]);
        }
        activity('jurnal')->causedBy(auth()->user())->withProperties(['tahun' => $this->tahun])->log('tutup_tahun_buku');
        Notification::make()->title("Tahun {$this->tahun} di-lock.")->success()->send();
    }
}
