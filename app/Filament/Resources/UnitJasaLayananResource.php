<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitJasaLayananResource\Pages;
use App\Models\UnitJasaLayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UnitJasaLayananResource extends Resource
{
    protected static ?string $model = UnitJasaLayanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Master Layanan Jasa';
    protected static ?string $modelLabel = 'Layanan Jasa';
    protected static ?string $pluralModelLabel = 'Layanan Jasa';
    protected static ?int $navigationSort = 35;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Layanan')
                ->description('Data master layanan jasa koperasi')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Layanan')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->placeholder('mis. CUCI-MOTOR')
                            ->helperText('Kode unik untuk identifikasi layanan'),
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Layanan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('mis. Cuci Motor Standar')
                            ->helperText('Nama lengkap layanan yang ditawarkan'),
                        Forms\Components\TextInput::make('tarif')
                            ->label('Tarif')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Harga satuan layanan'),
                        Forms\Components\TextInput::make('satuan_tarif')
                            ->label('Satuan Tarif')
                            ->placeholder('mis. /unit, /jam, /paket')
                            ->helperText('Satuan hitung tarif (per unit, per jam, per paket)'),
                        Forms\Components\Toggle::make('aktif')
                            ->label('Layanan Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan jika layanan sudah tidak tersedia'),
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
                Tables\Columns\TextColumn::make('tarif')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('satuan_tarif')->placeholder('—'),
                Tables\Columns\TextColumn::make('orders_count')->label('Total Order')->counts('orders')->badge(),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('aktif')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUnitJasaLayanans::route('/'),
            'create' => Pages\CreateUnitJasaLayanan::route('/create'),
            'edit'   => Pages\EditUnitJasaLayanan::route('/{record}/edit'),
        ];
    }
}
