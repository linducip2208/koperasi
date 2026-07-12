<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LaporanOjk extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan OJK';
    protected static ?string $title = 'Laporan OJK';
    protected static ?int $navigationSort = 62;

    protected static string $view = 'filament.pages.laporan-ojk';

    public ?array $data = [];
    public array $reportData = [];
    public bool $loaded = false;

    public function mount(): void
    {
        $this->form->fill(['tanggal' => now()->toDateString()]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(1)->schema([DatePicker::make('tanggal')->label('Per Tanggal')->required()->default(now())]),
        ])->statePath('data');
    }

    public function loadData(): void
    {
        $tgl = $this->data['tanggal'] ?? now()->toDateString();
        $this->reportData = [
            'tanggal'             => $tgl,
            'total_anggota'       => Anggota::where('status', 'aktif')->count(),
            'total_simpanan'      => Simpanan::where('status', 'aktif')->sum('saldo'),
            'total_pinjaman'      => Pinjaman::whereIn('status', ['aktif', 'macet'])->sum('saldo_pokok'),
            'simpanan_pokok'      => Simpanan::where('status', 'aktif')->whereHas('produk', fn($q) => $q->where('jenis', 'pokok'))->sum('saldo'),
            'simpanan_wajib'      => Simpanan::where('status', 'aktif')->whereHas('produk', fn($q) => $q->where('jenis', 'wajib'))->sum('saldo'),
            'simpanan_sukarela'   => Simpanan::where('status', 'aktif')->whereHas('produk', fn($q) => $q->where('jenis', 'sukarela'))->sum('saldo'),
            'jumlah_rekening'     => Simpanan::where('status', 'aktif')->count(),
            'jumlah_pinjaman'     => Pinjaman::whereIn('status', ['aktif', 'macet'])->count(),
            'jumlah_macet'        => Pinjaman::where('kolektabilitas', 'macet')->count(),
            'jumlah_lancar'       => Pinjaman::where('kolektabilitas', 'lancar')->count(),
            'npl'                 => $this->hitungNpl(),
            'total_aset'          => \App\Models\Asset::sum('nilai_buku'),
            'ekuitas'             => Simpanan::where('status', 'aktif')->whereHas('produk', fn($q) => $q->whereIn('jenis', ['pokok', 'wajib']))->sum('saldo'),
        ];
        $this->loaded = true;
    }

    protected function hitungNpl(): float
    {
        $total = Pinjaman::whereIn('status', ['aktif', 'macet'])->sum('saldo_pokok');
        if ($total <= 0) return 0;
        return round((Pinjaman::where('kolektabilitas', 'macet')->sum('saldo_pokok') / $total) * 100, 2);
    }

    public function downloadPdf(): void
    {
        if (!$this->loaded) $this->loadData();
        $pdf = Pdf::loadView('filament.pages.laporan-ojk-pdf', ['data' => $this->reportData])->setPaper('a4');
        response()->streamDownload(fn() => print($pdf->output()), "laporan-ojk-{$this->data['tanggal']}.pdf")->send();
        exit;
    }
}
