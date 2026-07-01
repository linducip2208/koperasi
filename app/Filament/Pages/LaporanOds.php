<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Support\Htmlable;

class LaporanOds extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan ODS';
    protected static ?string $title = 'Laporan ODS Kemenkop';
    protected static ?int $navigationSort = 60;

    protected static string $view = 'filament.pages.laporan-ods';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'tanggal' => now()->toDateString(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                DatePicker::make('tanggal')
                    ->label('Per Tanggal')
                    ->required()
                    ->default(now()),
            ]),
        ])->statePath('data');
    }

    public function getOdsData(): array
    {
        $tanggal = $this->data['tanggal'] ?? now()->toDateString();

        return [
            'tanggal'           => $tanggal,
            'total_anggota'     => Anggota::where('status', 'aktif')->count(),
            'total_anggota_l'   => Anggota::where('status', 'aktif')->where('jenis_kelamin', 'L')->count(),
            'total_anggota_p'   => Anggota::where('status', 'aktif')->where('jenis_kelamin', 'P')->count(),
            'total_simpanan'    => Simpanan::where('status', 'aktif')->sum('saldo'),
            'total_simpanan_pokok' => Simpanan::where('status', 'aktif')->whereHas('produk', fn ($q) => $q->where('jenis', 'pokok'))->sum('saldo'),
            'total_simpanan_wajib' => Simpanan::where('status', 'aktif')->whereHas('produk', fn ($q) => $q->where('jenis', 'wajib'))->sum('saldo'),
            'total_simpanan_sukarela' => Simpanan::where('status', 'aktif')->whereHas('produk', fn ($q) => $q->where('jenis', 'sukarela'))->sum('saldo'),
            'jumlah_rekening'   => Simpanan::where('status', 'aktif')->count(),
            'total_pinjaman'    => Pinjaman::where('status', 'aktif')->sum('saldo_pokok'),
            'jumlah_pinjaman'   => Pinjaman::where('status', 'aktif')->count(),
            'npl'               => $this->hitungNpl(),
            'total_asset'       => \App\Models\Kas::sum('saldo') + Simpanan::where('status', 'aktif')->sum('saldo') + \App\Models\Asset::sum('nilai_buku'),
            'total_volume_usaha' => Simpanan::where('status', 'aktif')->sum('saldo') + Pinjaman::whereIn('status', ['aktif', 'lunas'])->sum('pokok'),
        ];
    }

    protected function hitungNpl(): float
    {
        $total = Pinjaman::whereIn('status', ['aktif', 'macet'])->sum('saldo_pokok');
        if ($total <= 0) return 0;
        $macet = Pinjaman::where('kolektabilitas', 'macet')->sum('saldo_pokok');
        return round(($macet / $total) * 100, 2);
    }

    public function downloadPdf()
    {
        $data = $this->getOdsData();
        $pdf = Pdf::loadView('filament.pages.laporan-ods-pdf', ['data' => $data])
            ->setPaper('a4', 'landscape');
        return response()->streamDownload(fn () => print($pdf->output()), "laporan-ods-{$data['tanggal']}.pdf");
    }

    public function getHeading(): string|Htmlable
    {
        return 'Laporan ODS (Organisasi, Data, Sarana) — Format Kemenkop';
    }
}
