<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitProdusenKomoditiResource\Pages;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\UnitProdusenKomoditi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UnitProdusenKomoditiResource extends Resource
{
    use HasRoleAccess;
    protected static ?string $permissionModule = 'produsen';

    protected static ?string $model = UnitProdusenKomoditi::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Master Komoditi';
    protected static ?string $modelLabel = 'Komoditi';
    protected static ?string $pluralModelLabel = 'Komoditi';
    protected static ?int $navigationSort = 31;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Komoditi')
                ->description('Data master komoditi hasil produksi anggota')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Komoditi')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->placeholder('mis. SUSU-A1')
                            ->helperText('Kode unik untuk identifikasi komoditi'),
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Komoditi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('mis. Susu Sapi Segar Grade A')
                            ->helperText('Nama lengkap komoditi hasil produksi'),
                        Forms\Components\Select::make('jenis')
                            ->label('Jenis Komoditi')
                            ->options([
                                'pertanian'  => 'Pertanian',
                                'peternakan' => 'Peternakan',
                                'perikanan'  => 'Perikanan',
                                'perkebunan' => 'Perkebunan',
                                'kerajinan'  => 'Kerajinan / UKM',
                                'lainnya'    => 'Lainnya',
                            ])
                            ->helperText('Kategori jenis komoditi'),
                        Forms\Components\Select::make('satuan')
                            ->label('Satuan')
                            ->required()
                            ->options([
                                'kg'     => 'Kilogram (Kg)',
                                'liter'  => 'Liter',
                                'unit'   => 'Unit',
                                'ekor'   => 'Ekor',
                                'pack'   => 'Pack',
                                'meter'  => 'Meter',
                            ])
                            ->default('kg')
                            ->helperText('Satuan ukur standar komoditi'),
                    ]),
                ]),
            Forms\Components\Section::make('Harga Default')
                ->description('Harga standar beli dari anggota dan jual ke pasar')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('harga_beli_default')
                            ->label('Harga Beli Default')
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Harga standar koperasi membeli dari produsen anggota'),
                        Forms\Components\TextInput::make('harga_jual_default')
                            ->label('Harga Jual Default')
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Harga standar koperasi menjual ke pasar/konsumen'),
                        Forms\Components\Toggle::make('aktif')
                            ->label('Komoditi Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan jika komoditi sudah tidak diproduksi'),
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
                Tables\Columns\TextColumn::make('jenis')->badge()->color('info'),
                Tables\Columns\TextColumn::make('satuan'),
                Tables\Columns\TextColumn::make('harga_beli_default')->label('Harga Beli')->money('IDR'),
                Tables\Columns\TextColumn::make('harga_jual_default')->label('Harga Jual')->money('IDR'),
                Tables\Columns\TextColumn::make('setoran_count')->label('Total Setoran')->counts('setoran')->badge(),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')->options(['pertanian' => 'Pertanian', 'peternakan' => 'Peternakan', 'perkebunan' => 'Perkebunan', 'perikanan' => 'Perikanan', 'kerajinan' => 'Kerajinan']),
                Tables\Filters\TernaryFilter::make('aktif'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUnitProdusenKomoditis::route('/'),
            'create' => Pages\CreateUnitProdusenKomoditi::route('/create'),
            'edit'   => Pages\EditUnitProdusenKomoditi::route('/{record}/edit'),
        ];
    }
}
