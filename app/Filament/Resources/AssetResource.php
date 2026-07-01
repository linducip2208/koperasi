<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Models\Asset;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'HR & Asset';
    protected static ?string $navigationLabel = 'Aset Tetap';
    protected static ?int $navigationSort = 52;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Aset')
                ->description('Data utama aset tetap koperasi')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Aset')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Kode unik untuk identifikasi aset'),
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Aset')
                            ->required()
                            ->helperText('Nama atau deskripsi aset'),
                        Forms\Components\Select::make('kategori')
                            ->label('Kategori')
                            ->options([
                                'tanah'      => 'Tanah',
                                'bangunan'   => 'Bangunan',
                                'kendaraan'  => 'Kendaraan',
                                'peralatan'  => 'Peralatan Kantor',
                                'inventaris' => 'Inventaris',
                            ])
                            ->required()
                            ->helperText('Jenis / golongan aset tetap'),
                        Forms\Components\DatePicker::make('tanggal_perolehan')
                            ->label('Tanggal Perolehan')
                            ->required()
                            ->helperText('Tanggal aset dibeli atau diperoleh'),
                    ]),
                ]),
            Forms\Components\Section::make('Nilai & Penyusutan')
                ->description('Harga perolehan, nilai residu, dan metode penyusutan')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('harga_perolehan')
                            ->label('Harga Perolehan')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->helperText('Harga beli awal aset termasuk biaya perolehan'),
                        Forms\Components\TextInput::make('nilai_residu')
                            ->label('Nilai Residu')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->helperText('Nilai sisa di akhir umur ekonomis'),
                        Forms\Components\TextInput::make('umur_ekonomis_bulan')
                            ->label('Umur Ekonomis (bulan)')
                            ->numeric()
                            ->required()
                            ->helperText('Estimasi masa pakai aset dalam bulan'),
                        Forms\Components\Select::make('metode_susut')
                            ->label('Metode Penyusutan')
                            ->options([
                                'garis_lurus'   => 'Garis Lurus',
                                'saldo_menurun' => 'Saldo Menurun (Double Declining)',
                            ])
                            ->default('garis_lurus')
                            ->required()
                            ->helperText('Metode perhitungan beban penyusutan'),
                    ]),
                ]),
            Forms\Components\Section::make('Akuntansi & Status')
                ->description('Mapping COA dan status aset')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('coa_aset_id')
                            ->label('COA Aset')
                            ->options(Coa::where('tipe', 'aset')->pluck('nama', 'id'))
                            ->searchable()
                            ->helperText('Akun aset tetap di neraca'),
                        Forms\Components\Select::make('coa_susut_id')
                            ->label('COA Beban Penyusutan')
                            ->options(Coa::where('tipe', 'beban')->pluck('nama', 'id'))
                            ->searchable()
                            ->helperText('Akun beban penyusutan di laba rugi'),
                        Forms\Components\Select::make('coa_akumulasi_id')
                            ->label('COA Akumulasi Penyusutan')
                            ->options(Coa::where('tipe', 'aset')->pluck('nama', 'id'))
                            ->searchable()
                            ->helperText('Akun kontra aset di neraca'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif'  => 'Aktif',
                                'dijual' => 'Dijual',
                                'rusak'  => 'Rusak',
                                'hapus'  => 'Dihapus',
                            ])
                            ->default('aktif')
                            ->helperText('Status terkini aset'),
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
                Tables\Columns\TextColumn::make('kategori')->badge(),
                Tables\Columns\TextColumn::make('tanggal_perolehan')->date('d M Y'),
                Tables\Columns\TextColumn::make('harga_perolehan')->money('IDR'),
                Tables\Columns\TextColumn::make('akumulasi_susut')->money('IDR')->color('warning'),
                Tables\Columns\TextColumn::make('nilai_buku')->money('IDR')->weight('bold'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'aktif' => 'success', 'dijual' => 'info', 'rusak' => 'warning', 'hapus' => 'danger',
                }),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit'   => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
