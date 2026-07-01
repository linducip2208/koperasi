<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Widgets\DashboardWidgetFilter;
use Filament\Widgets\TableWidget as BaseWidget;

class TopAnggotaTable extends BaseWidget
{
    use DashboardWidgetFilter;
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 1;
    protected static ?string $heading = '🏆 Top 10 Anggota — Saldo Terbesar';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Anggota::query()
                    ->where('status', 'aktif')
                    ->withSum(['simpanan as total_simpanan' => fn ($q) => $q->where('status', 'aktif')], 'saldo')
                    ->orderByDesc('total_simpanan')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nomor_anggota')
                    ->label('No.')
                    ->size('xs')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Anggota')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_simpanan')
                    ->label('Total Simpanan')
                    ->money('IDR')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
