<?php

namespace App\Filament\Resources\PinjamanResource\RelationManagers;

use App\Models\PinjamanPembayaran;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PembayaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pembayaran';
    protected static ?string $title = 'Riwayat Pembayaran';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->copyable(),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y'),
                Tables\Columns\TextColumn::make('jenis')->badge(),
                Tables\Columns\TextColumn::make('total_bayar')->money('IDR')->weight('bold'),
                Tables\Columns\TextColumn::make('alokasi_pokok')->label('Pokok')->money('IDR')->color('success'),
                Tables\Columns\TextColumn::make('alokasi_margin')->label('Margin')->money('IDR')->color('info'),
                Tables\Columns\TextColumn::make('alokasi_denda')->label('Denda')->money('IDR')->color('danger'),
                Tables\Columns\TextColumn::make('alokasi_titipan')->label('Titipan')->money('IDR')->color('warning'),
                Tables\Columns\TextColumn::make('metode_bayar')->badge(),
            ])
            ->actions([
                Tables\Actions\Action::make('slip')->label('Slip PDF')
                    ->icon('heroicon-o-printer')->color('info')
                    ->url(fn (PinjamanPembayaran $r) => route('dokumen.slip', $r->id), shouldOpenInNewTab: true),
            ])
            ->defaultSort('tanggal', 'desc');
    }
}
