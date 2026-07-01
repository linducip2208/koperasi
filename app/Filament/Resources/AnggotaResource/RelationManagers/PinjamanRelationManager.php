<?php

namespace App\Filament\Resources\AnggotaResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PinjamanRelationManager extends RelationManager
{
    protected static string $relationship = 'pinjaman';
    protected static ?string $title = 'Riwayat Pinjaman';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_akad')->copyable(),
                Tables\Columns\TextColumn::make('produk.nama')->label('Produk')->badge(),
                Tables\Columns\TextColumn::make('plafon')->money('IDR'),
                Tables\Columns\TextColumn::make('tenor')->suffix(' bln'),
                Tables\Columns\TextColumn::make('saldo_pokok')->label('Sisa')->money('IDR'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('kolektabilitas')->badge(),
            ]);
    }
}
