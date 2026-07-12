<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Services\PajakService;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LaporanPajak extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Pajak';
    protected static ?string $title = 'Laporan Pajak';
    protected static ?int $navigationSort = 61;

    protected static string $view = 'filament.pages.laporan-pajak';

    public ?array $data = [];
    public array $pph21 = [];
    public array $pphBunga = [];
    public bool $loaded = false;

    public function mount(): void
    {
        $this->form->fill([
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                Select::make('bulan')->label('Bulan')->options([
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                ])->required(),
                Select::make('tahun')->label('Tahun')->options(array_combine(
                    range(2024, date('Y')), range(2024, date('Y'))
                ))->required(),
            ]),
        ])->statePath('data');
    }

    public function loadData(): void
    {
        $this->validate();
        $this->pph21 = PajakService::laporanPph21Bulanan(
            (string) $this->data['bulan'],
            (string) $this->data['tahun']
        )->toArray();
        $this->pphBunga = PajakService::laporanPphBunga(
            (string) $this->data['bulan'],
            (string) $this->data['tahun']
        );
        $this->loaded = true;
    }

    public function downloadPdf(): void
    {
        $this->validate();
        if (!$this->loaded) $this->loadData();

        $pdf = Pdf::loadView('filament.pages.laporan-pajak-pdf', [
            'pph21'    => $this->pph21,
            'pphBunga' => $this->pphBunga,
            'periode'  => $this->data['bulan'] . '/' . $this->data['tahun'],
        ])->setPaper('a4');

        response()->streamDownload(fn () => print($pdf->output()), "laporan-pajak-{$this->data['bulan']}-{$this->data['tahun']}.pdf")->send();
        exit;
    }
}
