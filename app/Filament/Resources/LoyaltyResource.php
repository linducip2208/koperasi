<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoyaltyResource\Pages;
use App\Models\LoyaltyPoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LoyaltyResource extends Resource
{
    protected static ?string $model = LoyaltyPoint::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Keanggotaan';
    protected static ?string $navigationLabel = 'Poin Loyalitas';
    protected static ?string $modelLabel = 'Poin Loyalitas';
    protected static ?string $pluralModelLabel = 'Poin Loyalitas';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('anggota_id')
                ->label('Anggota')
                ->relationship('anggota', 'nama')
                ->searchable()->preload()->required(),
            Forms\Components\TextInput::make('poin')
                ->label('Poin Saat Ini')
                ->numeric()->disabled(),
            Forms\Components\TextInput::make('total_poin_diterima')
                ->label('Total Poin Diterima')
                ->numeric()->disabled(),
            Forms\Components\TextInput::make('total_poin_ditukar')
                ->label('Total Poin Ditukar')
                ->numeric()->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anggota.nama')
                    ->label('Anggota')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('poin')
                    ->label('Poin')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state)),
                Tables\Columns\TextColumn::make('total_poin_diterima')
                    ->label('Diterima')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state)),
                Tables\Columns\TextColumn::make('total_poin_ditukar')
                    ->label('Ditukar')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state)),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Update')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLoyalties::route('/'),
            'create' => Pages\CreateLoyalty::route('/create'),
            'edit'   => Pages\EditLoyalty::route('/{record}/edit'),
        ];
    }
}
