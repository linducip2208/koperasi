<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PpobProdukResource\Pages;
use App\Models\PpobProduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PpobProdukResource extends Resource
{
    protected static ?string $model = PpobProduk::class;
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Produk PPOB';
    protected static ?string $modelLabel = 'Produk PPOB';
    protected static ?string $pluralModelLabel = 'Produk PPOB';
    protected static ?int $navigationSort = 35;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('kategori')->label('Kategori')->options([
                    'pulsa' => 'Pulsa', 'pln' => 'PLN / Listrik', 'bpjs' => 'BPJS',
                    'ewallet' => 'E-Wallet', 'pdam' => 'PDAM', 'internet' => 'Internet',
                ])->required(),
                Forms\Components\TextInput::make('kode')->label('Kode Produk')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nama')->label('Nama Produk')->required(),
                Forms\Components\TextInput::make('nominal')->label('Nominal')->placeholder('50000 atau 20rb-100rb'),
                Forms\Components\TextInput::make('harga_jual')->label('Harga Jual')->prefix('Rp')->numeric()->required(),
                Forms\Components\TextInput::make('harga_beli')->label('Harga Beli')->prefix('Rp')->numeric()->required(),
                Forms\Components\Toggle::make('aktif')->label('Aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->label('Kode')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('kategori')->label('Kategori')->badge(),
                Tables\Columns\TextColumn::make('nominal')->label('Nominal'),
                Tables\Columns\TextColumn::make('harga_jual')->label('Harga Jual')->money('IDR'),
                Tables\Columns\IconColumn::make('aktif')->label('Aktif')->boolean(),
            ])
            ->filters([Tables\Filters\SelectFilter::make('kategori')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPpobProduks::route('/'),
            'create' => Pages\CreatePpobProduk::route('/create'),
            'edit' => Pages\EditPpobProduk::route('/{record}/edit'),
        ];
    }
}
