<?php

namespace App\Filament\Resources\AnggotaResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SimpananRelationManager extends RelationManager
{
    protected static string $relationship = 'simpanan';
    protected static ?string $title = 'Rekening Simpanan';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_rekening')->copyable(),
                Tables\Columns\TextColumn::make('produk.nama')->label('Produk')->badge(),
                Tables\Columns\TextColumn::make('saldo')->money('IDR'),
                Tables\Columns\TextColumn::make('tanggal_buka')->date('d M Y'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ]);
    }
}
