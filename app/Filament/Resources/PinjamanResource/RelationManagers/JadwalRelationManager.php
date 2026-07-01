<?php

namespace App\Filament\Resources\PinjamanResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class JadwalRelationManager extends RelationManager
{
    protected static string $relationship = 'jadwal';
    protected static ?string $title = 'Jadwal Angsuran';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('angsuran_ke')->label('#')->alignCenter(),
                Tables\Columns\TextColumn::make('tanggal_jatuh_tempo')->label('Jatuh Tempo')->date('d M Y'),
                Tables\Columns\TextColumn::make('pokok')->money('IDR'),
                Tables\Columns\TextColumn::make('margin')->label('Bunga/Margin')->money('IDR'),
                Tables\Columns\TextColumn::make('total_angsuran')->money('IDR')->weight('bold'),
                Tables\Columns\TextColumn::make('terbayar_pokok')->label('Bayar Pokok')->money('IDR')->color('success'),
                Tables\Columns\TextColumn::make('terbayar_margin')->label('Bayar Margin')->money('IDR')->color('success'),
                Tables\Columns\TextColumn::make('denda')->money('IDR')->color('danger'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'lunas' => 'success', 'telat' => 'danger', 'jatuh_tempo' => 'warning', default => 'gray',
                }),
            ])
            ->paginated(false);
    }
}
