<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokoBarangResource\Pages;
use App\Models\TokoBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TokoBarangResource extends Resource
{
    protected static ?string $model = TokoBarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Master Barang';
    protected static ?string $modelLabel = 'Barang';
    protected static ?string $pluralModelLabel = 'Master Barang';
    protected static ?int $navigationSort = 24;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Barang')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('sku')->label('SKU')->required()->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('barcode')->label('Barcode'),
                    Forms\Components\TextInput::make('nama')->label('Nama Barang')->required()->columnSpanFull(),
                    Forms\Components\TextInput::make('brand')->label('Brand/Merk'),
                    Forms\Components\Toggle::make('is_jasa')->label('Ini Jasa (bukan stok)'),
                ]),
            ]),
            Forms\Components\Section::make('Harga')->schema([
                Forms\Components\Grid::make(4)->schema([
                    Forms\Components\TextInput::make('harga_beli')->label('Harga Beli')->numeric()->prefix('Rp')->default(0),
                    Forms\Components\TextInput::make('harga_jual_umum')->label('Harga Umum')->numeric()->prefix('Rp')->default(0),
                    Forms\Components\TextInput::make('harga_jual_anggota')->label('Harga Anggota')->numeric()->prefix('Rp')->default(0),
                    Forms\Components\TextInput::make('harga_jual_grosir')->label('Harga Grosir')->numeric()->prefix('Rp')->default(0),
                ]),
            ]),
            Forms\Components\Section::make('Stok')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('stok')->label('Stok Saat Ini')->numeric()->default(0)->disabled(),
                    Forms\Components\TextInput::make('stok_minimum')->label('Stok Minimum')->numeric()->default(0),
                    Forms\Components\TextInput::make('stok_maksimum')->label('Stok Maksimum')->numeric()->default(0),
                    Forms\Components\Select::make('metode_hpp')->label('Metode HPP')->options([
                        'average' => 'Average', 'fifo' => 'FIFO',
                    ])->default('average'),
                    Forms\Components\FileUpload::make('foto_path')->label('Foto')->image()->directory('barang'),
                    Forms\Components\Toggle::make('aktif')->label('Aktif')->default(true),
                ]),
            ])->visible(fn ($get) => ! $get('is_jasa')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_path')->square(),
                Tables\Columns\TextColumn::make('sku')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('barcode')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('nama')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('harga_jual_umum')->label('Harga Umum')->money('IDR'),
                Tables\Columns\TextColumn::make('harga_jual_anggota')->label('Harga Anggota')->money('IDR'),
                Tables\Columns\TextColumn::make('stok')->color(fn ($record) => $record->stok <= $record->stok_minimum ? 'danger' : 'success'),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTokoBarangs::route('/'),
            'create' => Pages\CreateTokoBarang::route('/create'),
            'edit'   => Pages\EditTokoBarang::route('/{record}/edit'),
        ];
    }
}
