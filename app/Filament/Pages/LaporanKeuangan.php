<?php

namespace App\Filament\Pages;

use App\Models\Cabang;
use App\Models\ProdukPinjaman;
use App\Models\ProdukSimpanan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LaporanKeuangan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?int $navigationSort = 100;
    protected static string $view = 'filament.pages.laporan-keuangan';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'dari'                => now()->startOfYear()->toDateString(),
            'sampai'              => now()->toDateString(),
            'cabang_id'           => null,
            'produk_simpanan_id'  => null,
            'produk_pinjaman_id'  => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('dari')->label('Dari')->required(),
            DatePicker::make('sampai')->label('Sampai')->required(),
            Select::make('cabang_id')->label('Cabang')
                ->options(Cabang::where('aktif', true)->pluck('nama', 'id'))
                ->searchable()->placeholder('Semua Cabang'),
            Select::make('produk_simpanan_id')->label('Produk Simpanan')
                ->options(ProdukSimpanan::pluck('nama', 'id'))
                ->searchable()->placeholder('Semua Produk Simpanan'),
            Select::make('produk_pinjaman_id')->label('Produk Pinjaman')
                ->options(ProdukPinjaman::pluck('nama', 'id'))
                ->searchable()->placeholder('Semua Produk Pinjaman'),
        ])->columns(3)->statePath('data');
    }
}
