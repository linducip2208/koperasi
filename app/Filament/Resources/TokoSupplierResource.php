<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokoSupplierResource\Pages;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\TokoSupplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TokoSupplierResource extends Resource
{
    use HasRoleAccess;
    protected static ?string $permissionModule = 'pos';

    protected static ?string $model = TokoSupplier::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Master Supplier';
    protected static ?string $modelLabel = 'Supplier';
    protected static ?string $pluralModelLabel = 'Supplier';
    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('kode')->required()->maxLength(20)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nama')->required()->maxLength(255),
                Forms\Components\TextInput::make('telp')->tel()->maxLength(255),
                Forms\Components\TextInput::make('email')->email()->maxLength(255),
                Forms\Components\TextInput::make('npwp')->maxLength(25),
                Forms\Components\TextInput::make('saldo_hutang')->prefix('Rp')->numeric()->default(0),
                Forms\Components\Toggle::make('aktif')->default(true),
            ]),
            Forms\Components\Textarea::make('alamat')->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->copyable()->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('telp')->placeholder('—'),
                Tables\Columns\TextColumn::make('email')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('saldo_hutang')->money('IDR')->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('aktif')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTokoSuppliers::route('/'),
            'create' => Pages\CreateTokoSupplier::route('/create'),
            'edit'   => Pages\EditTokoSupplier::route('/{record}/edit'),
        ];
    }
}
