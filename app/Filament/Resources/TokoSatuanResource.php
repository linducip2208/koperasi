<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokoSatuanResource\Pages;
use App\Models\TokoSatuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TokoSatuanResource extends Resource
{
    protected static ?string $model = TokoSatuan::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Master Satuan';
    protected static ?string $modelLabel = 'Satuan';
    protected static ?string $pluralModelLabel = 'Satuan Barang';
    protected static ?int $navigationSort = 34;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('kode')->required()->maxLength(10)->placeholder('PCS, KG, BOX')->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nama')->required()->maxLength(255)->placeholder('Pieces, Kilogram, Box'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->copyable()->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('nama')->searchable(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTokoSatuans::route('/'),
            'create' => Pages\CreateTokoSatuan::route('/create'),
            'edit'   => Pages\EditTokoSatuan::route('/{record}/edit'),
        ];
    }
}
