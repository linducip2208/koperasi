<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShuDistribusiResource\Pages;
use App\Models\ShuDistribusi;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class ShuDistribusiResource extends Resource
{
    use HasRoleAccess;
    protected static ?string $permissionModule = 'shu';
    protected static ?string $model = ShuDistribusi::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square';
    protected static ?string $navigationGroup = '🎂 SHU & RAT';
    protected static ?string $navigationLabel = 'Distribusi SHU';
    protected static ?string $modelLabel = 'Distribusi SHU';
    protected static ?string $pluralModelLabel = 'Distribusi SHU';
    protected static ?int $navigationSort = 73;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anggota.nama')->label('Anggota')->searchable(),
                Tables\Columns\TextColumn::make('shuPerhitungan.tahun')->label('Tahun SHU')->sortable(),
                Tables\Columns\TextColumn::make('total_simpanan')->label('Total Simpanan')->money('IDR'),
                Tables\Columns\TextColumn::make('total_transaksi')->label('Total Transaksi')->money('IDR'),
                Tables\Columns\TextColumn::make('jasa_modal')->label('Jasa Modal')->money('IDR'),
                Tables\Columns\TextColumn::make('jasa_anggota')->label('Jasa Anggota')->money('IDR'),
                Tables\Columns\TextColumn::make('total_shu')->label('Total SHU')->money('IDR')->weight('bold'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'dibayar' => 'success', 'pending' => 'warning', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('distributed_at')->label('Tgl Distribusi')->date('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['pending' => 'Pending', 'dibayar' => 'Dibayar']),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShuDistribusis::route('/'),
        ];
    }
}
