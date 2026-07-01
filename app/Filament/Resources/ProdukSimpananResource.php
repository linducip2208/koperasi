<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukSimpananResource\Pages;
use App\Models\Coa;
use App\Models\ProdukSimpanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProdukSimpananResource extends Resource
{
    protected static ?string $model = ProdukSimpanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Simpan Pinjam';
    protected static ?string $navigationLabel = 'Produk Simpanan';
    protected static ?string $modelLabel = 'Produk Simpanan';
    protected static ?string $pluralModelLabel = 'Produk Simpanan';
    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Produk')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('kode')->label('Kode Produk')
                        ->required()->maxLength(20)->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('nama')->label('Nama Produk')->required(),
                    Forms\Components\Select::make('jenis')->label('Jenis Simpanan')->options([
                        'pokok'     => 'Simpanan Pokok',
                        'wajib'     => 'Simpanan Wajib',
                        'sukarela'  => 'Simpanan Sukarela',
                        'berjangka' => 'Simpanan Berjangka / Deposito',
                        'khusus'    => 'Simpanan Khusus',
                    ])->required()->live(),
                    Forms\Components\Select::make('akad_type')->label('Tipe Akad')->options([
                        'konvensional' => 'Konvensional',
                        'wadiah'       => 'Wadiah (Syariah - Titipan)',
                        'mudharabah'   => 'Mudharabah (Syariah - Bagi Hasil)',
                    ])->required()->live(),
                ]),
            ]),
            Forms\Components\Section::make('Setoran & Saldo')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('setoran_minimum')->label('Setoran Minimum')->numeric()->prefix('Rp')->default(0),
                    Forms\Components\TextInput::make('setoran_wajib')->label('Setoran Wajib (Bulanan)')->numeric()->prefix('Rp')->default(0),
                    Forms\Components\TextInput::make('saldo_minimum')->label('Saldo Minimum')->numeric()->prefix('Rp')->default(0),
                ]),
            ]),
            Forms\Components\Section::make('Bunga / Bagi Hasil')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('bunga_persen_tahun')
                        ->label('Bunga (%/tahun)')
                        ->numeric()->step(0.01)->suffix('%')
                        ->visible(fn ($get) => $get('akad_type') === 'konvensional'),
                    Forms\Components\Select::make('metode_bunga')->label('Metode Perhitungan')
                        ->options([
                            'saldo_harian'  => 'Saldo Harian',
                            'saldo_rata2'   => 'Saldo Rata-rata',
                            'saldo_akhir'   => 'Saldo Akhir Bulan',
                        ])
                        ->visible(fn ($get) => $get('akad_type') === 'konvensional'),
                    Forms\Components\TextInput::make('nisbah_anggota')->label('Nisbah Anggota (%)')
                        ->numeric()->step(0.01)->suffix('%')
                        ->visible(fn ($get) => $get('akad_type') === 'mudharabah'),
                    Forms\Components\TextInput::make('nisbah_koperasi')->label('Nisbah Koperasi (%)')
                        ->numeric()->step(0.01)->suffix('%')
                        ->visible(fn ($get) => $get('akad_type') === 'mudharabah'),
                ]),
            ]),
            Forms\Components\Section::make('Berjangka')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Toggle::make('berjangka')->label('Simpanan Berjangka')->live(),
                    Forms\Components\TextInput::make('tenor_bulan')->label('Tenor (bulan)')
                        ->numeric()->minValue(1)
                        ->visible(fn ($get) => $get('berjangka')),
                ]),
            ])->visible(fn ($get) => in_array($get('jenis'), ['berjangka', 'khusus'])),
            Forms\Components\Section::make('Akuntansi & Lainnya')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('coa_simpanan_id')->label('COA Simpanan')
                        ->options(Coa::where('tipe', 'kewajiban')->pluck('nama', 'id'))->searchable(),
                    Forms\Components\Select::make('coa_bunga_id')->label('COA Beban Bunga')
                        ->options(Coa::where('tipe', 'beban')->pluck('nama', 'id'))->searchable(),
                    Forms\Components\Toggle::make('boleh_tarik')->label('Boleh Ditarik')->default(true),
                    Forms\Components\Toggle::make('auto_potong_shu')->label('Auto Potong dari SHU'),
                    Forms\Components\Toggle::make('aktif')->label('Aktif')->default(true),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->searchable()->copyable()->weight('bold'),
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('jenis')->badge(),
                Tables\Columns\TextColumn::make('akad_type')->label('Akad')->badge()
                    ->color(fn ($state) => $state === 'konvensional' ? 'gray' : 'success'),
                Tables\Columns\TextColumn::make('setoran_minimum')->label('Setor Min')->money('IDR'),
                Tables\Columns\TextColumn::make('bunga_persen_tahun')->label('Bunga/thn')->suffix('%'),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('akad_type')->options([
                    'konvensional' => 'Konvensional', 'wadiah' => 'Wadiah', 'mudharabah' => 'Mudharabah',
                ]),
                Tables\Filters\SelectFilter::make('jenis')->options([
                    'pokok' => 'Pokok', 'wajib' => 'Wajib', 'sukarela' => 'Sukarela',
                    'berjangka' => 'Berjangka', 'khusus' => 'Khusus',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProdukSimpanans::route('/'),
            'create' => Pages\CreateProdukSimpanan::route('/create'),
            'edit'   => Pages\EditProdukSimpanan::route('/{record}/edit'),
        ];
    }
}
