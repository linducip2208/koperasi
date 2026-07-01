<?php

namespace App\Filament\Resources\SimpananResource\RelationManagers;

use App\Models\SimpananTransaksi;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TransaksiRelationManager extends RelationManager
{
    protected static string $relationship = 'transaksi';
    protected static ?string $title = 'Mutasi Transaksi';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y H:i'),
                Tables\Columns\TextColumn::make('nomor')->copyable(),
                Tables\Columns\TextColumn::make('jenis')->badge()->color(fn ($state) => match ($state) {
                    'setor', 'bunga', 'bagi_hasil' => 'success',
                    'tarik', 'pajak', 'denda', 'admin' => 'warning',
                    'blokir', 'tutup' => 'danger',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('jumlah')->money('IDR')->weight('bold'),
                Tables\Columns\TextColumn::make('saldo_sesudah')->label('Saldo Setelah')->money('IDR'),
                Tables\Columns\TextColumn::make('keterangan')->limit(40),
            ])
            ->actions([
                Tables\Actions\Action::make('bukti')->label('Bukti PDF')
                    ->icon('heroicon-o-printer')->color('info')
                    ->visible(fn (SimpananTransaksi $r) => in_array($r->jenis, ['setor', 'tarik', 'setoran', 'penarikan'], true))
                    ->url(fn (SimpananTransaksi $r) => route('dokumen.kuitansi', $r->id), shouldOpenInNewTab: true),
            ])
            ->defaultSort('tanggal', 'desc');
    }
}
