<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokoKategoriResource\Pages;
use App\Models\TokoKategori;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TokoKategoriResource extends Resource
{
    protected static ?string $model = TokoKategori::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Master Kategori Barang';
    protected static ?string $modelLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori Barang';
    protected static ?int $navigationSort = 32;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('nama')->required()->maxLength(255),
                Forms\Components\Select::make('parent_id')->label('Parent Kategori (opsional)')
                    ->options(TokoKategori::pluck('nama', 'id'))->searchable()->placeholder('Top-level'),
                Forms\Components\Toggle::make('aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('parent.nama')->label('Parent')->placeholder('—'),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('aktif')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTokoKategoris::route('/'),
            'create' => Pages\CreateTokoKategori::route('/create'),
            'edit'   => Pages\EditTokoKategori::route('/{record}/edit'),
        ];
    }
}
