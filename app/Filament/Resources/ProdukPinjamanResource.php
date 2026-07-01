<?php

namespace App\Filament\Resources;

use App\Domain\Calculation\CalculatorFactory;
use App\Filament\Resources\ProdukPinjamanResource\Pages;
use App\Models\Coa;
use App\Models\ProdukPinjaman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProdukPinjamanResource extends Resource
{
    protected static ?string $model = ProdukPinjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Simpan Pinjam';
    protected static ?string $navigationLabel = 'Produk Pinjaman';
    protected static ?string $modelLabel = 'Produk Pinjaman';
    protected static ?string $pluralModelLabel = 'Produk Pinjaman';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->tabs([
                Forms\Components\Tabs\Tab::make('Informasi Produk')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('kode')->label('Kode Produk')->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('nama')->label('Nama Produk')->required(),
                        Forms\Components\Select::make('jenis')->label('Jenis Pinjaman')->options([
                            'produktif'  => 'Produktif',
                            'konsumtif'  => 'Konsumtif',
                            'multiguna'  => 'Multiguna',
                        ])->required(),
                        Forms\Components\Select::make('akad_type')->label('Tipe Akad')->options([
                            'konvensional' => 'Konvensional',
                            'murabahah'    => 'Murabahah (Jual-Beli)',
                            'mudharabah'   => 'Mudharabah (Bagi Hasil)',
                            'musyarakah'   => 'Musyarakah (Kemitraan)',
                            'ijarah'       => 'Ijarah (Sewa)',
                            'ijarah_mb'    => 'Ijarah Muntahiya Bittamlik',
                            'qardh'        => 'Qardh (Pinjaman Kebajikan)',
                            'rahn'         => 'Rahn (Gadai)',
                            'salam'        => 'Salam (Pesanan)',
                            'istishna'     => 'Istishna (Pesanan Produksi)',
                            'wakalah'      => 'Wakalah (Perwakilan)',
                            'kafalah'      => 'Kafalah (Penjaminan)',
                            'hawalah'      => 'Hawalah (Pengalihan Utang)',
                        ])->required()->live(),
                        Forms\Components\Select::make('metode_perhitungan')->label('Metode Perhitungan')
                            ->options(collect(CalculatorFactory::options())->flatten(1)->merge(
                                collect(CalculatorFactory::options())->collapse()
                            )->all())
                            ->required()
                            ->helperText('Konvensional: flat/efektif/anuitas. Syariah: sesuai akad'),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Plafon & Tenor')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('plafon_minimum')->label('Plafon Minimum')->numeric()->prefix('Rp')->default(500000),
                        Forms\Components\TextInput::make('plafon_maksimum')->label('Plafon Maksimum')->numeric()->prefix('Rp')->default(100000000),
                        Forms\Components\TextInput::make('tenor_minimum')->label('Tenor Minimum (bln)')->numeric()->default(1),
                        Forms\Components\TextInput::make('tenor_maksimum')->label('Tenor Maksimum (bln)')->numeric()->default(60),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Bunga / Margin')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('bunga_persen')->label('Bunga (%/bulan utk konv)')->numeric()->step(0.0001)->suffix('%'),
                        Forms\Components\TextInput::make('margin_persen')->label('Margin (%/tahun utk syariah)')->numeric()->step(0.0001)->suffix('%'),
                        Forms\Components\TextInput::make('nisbah_anggota')->label('Nisbah Anggota')->numeric()->step(0.01)->suffix('%'),
                        Forms\Components\TextInput::make('nisbah_koperasi')->label('Nisbah Koperasi')->numeric()->step(0.01)->suffix('%'),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Biaya')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('biaya_admin_persen')->label('Biaya Admin (%)')->numeric()->step(0.01)->suffix('%'),
                        Forms\Components\TextInput::make('biaya_admin_flat')->label('Biaya Admin (flat)')->numeric()->prefix('Rp'),
                        Forms\Components\TextInput::make('biaya_provisi_persen')->label('Biaya Provisi (%)')->numeric()->step(0.01)->suffix('%'),
                        Forms\Components\TextInput::make('biaya_asuransi_persen')->label('Biaya Asuransi (%)')->numeric()->step(0.01)->suffix('%'),
                        Forms\Components\TextInput::make('biaya_materai')->label('Biaya Materai')->numeric()->prefix('Rp')->default(10000),
                        Forms\Components\TextInput::make('denda_persen_per_hari')->label('Denda %/hari')->numeric()->step(0.0001)->suffix('%'),
                        Forms\Components\TextInput::make('denda_flat_per_hari')->label('Denda flat/hari')->numeric()->prefix('Rp'),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Jaminan & Akuntansi')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Toggle::make('butuh_jaminan')->label('Butuh Jaminan')->default(true),
                        Forms\Components\Toggle::make('butuh_simpanan_blokir')->label('Blokir Simpanan')->live(),
                        Forms\Components\TextInput::make('rasio_simpanan_blokir')->label('Rasio Simpanan Blokir (%)')
                            ->numeric()->step(0.01)->suffix('%')
                            ->visible(fn ($get) => $get('butuh_simpanan_blokir')),
                        Forms\Components\Select::make('coa_pokok_id')->label('COA Pokok Pinjaman')
                            ->options(Coa::where('tipe', 'aset')->pluck('nama', 'id'))->searchable(),
                        Forms\Components\Select::make('coa_bunga_id')->label('COA Pendapatan Bunga/Margin')
                            ->options(Coa::where('tipe', 'pendapatan')->pluck('nama', 'id'))->searchable(),
                        Forms\Components\Select::make('coa_denda_id')->label('COA Pendapatan Denda')
                            ->options(Coa::where('tipe', 'pendapatan')->pluck('nama', 'id'))->searchable(),
                        Forms\Components\Toggle::make('aktif')->label('Aktif')->default(true),
                    ]),
                ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->searchable()->copyable()->weight('bold'),
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('akad_type')->label('Akad')->badge()
                    ->color(fn ($state) => $state === 'konvensional' ? 'gray' : 'success'),
                Tables\Columns\TextColumn::make('metode_perhitungan')->badge(),
                Tables\Columns\TextColumn::make('plafon_maksimum')->label('Plafon Maks')->money('IDR'),
                Tables\Columns\TextColumn::make('bunga_persen')->label('Bunga')->suffix('%'),
                Tables\Columns\TextColumn::make('margin_persen')->label('Margin')->suffix('%'),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('akad_type')->options([
                    'konvensional' => 'Konvensional',
                    'murabahah'    => 'Murabahah',
                    'mudharabah'   => 'Mudharabah',
                    'musyarakah'   => 'Musyarakah',
                    'ijarah'       => 'Ijarah',
                    'ijarah_mb'    => 'Ijarah MB',
                    'qardh'        => 'Qardh',
                    'rahn'         => 'Rahn',
                    'salam'        => 'Salam',
                    'istishna'     => 'Istishna',
                    'wakalah'      => 'Wakalah',
                    'kafalah'      => 'Kafalah',
                    'hawalah'      => 'Hawalah',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProdukPinjamen::route('/'),
            'create' => Pages\CreateProdukPinjaman::route('/create'),
            'edit'   => Pages\EditProdukPinjaman::route('/{record}/edit'),
        ];
    }
}
