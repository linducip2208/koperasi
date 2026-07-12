<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KasTransaksiResource\Pages;
use App\Models\KasTransaksi;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class KasTransaksiResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'kas';
    protected static ?string $model = KasTransaksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationGroup = '🧮 Akuntansi';
    protected static ?string $navigationLabel = 'Kas Transaksi';
    protected static ?string $modelLabel = 'Kas Transaksi';
    protected static ?string $pluralModelLabel = 'Kas Transaksi';
    protected static ?int $navigationSort = 41;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('kas.nama')->label('Kas')->searchable(),
                Tables\Columns\TextColumn::make('jenis')->badge()->color(fn (string $state) => match ($state) {
                    'masuk' => 'success', 'setor_bank' => 'info', 'transfer_masuk' => 'info',
                    'keluar' => 'danger', 'tarik_bank' => 'warning', 'transfer_keluar' => 'warning',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('jumlah')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('coa.nama')->label('COA')->toggleable(),
                Tables\Columns\TextColumn::make('kasTujuan.nama')->label('Kas Tujuan')->placeholder('—'),
                Tables\Columns\TextColumn::make('keterangan')->limit(40)->toggleable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')->options([
                    'masuk' => 'Masuk', 'keluar' => 'Keluar',
                    'transfer_masuk' => 'Transfer Masuk', 'transfer_keluar' => 'Transfer Keluar',
                    'setor_bank' => 'Setor Bank', 'tarik_bank' => 'Tarik Bank',
                ]),
                Tables\Filters\SelectFilter::make('kas_id')->label('Kas')
                    ->relationship('kas', 'nama'),
            ])
            ->defaultSort('tanggal', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKasTransaksis::route('/'),
        ];
    }
}
