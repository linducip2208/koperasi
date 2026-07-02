<?php

namespace App\Filament\Resources\SurveyResource\RelationManagers;

use App\Models\SurveyJawaban;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class JawabanRelationManager extends RelationManager
{
    protected static string $relationship = 'jawaban';
    protected static ?string $title = 'Jawaban Survey';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anggota.nama')->label('Anggota')->searchable(),
                Tables\Columns\TextColumn::make('jawaban')->label('Jawaban')
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_UNESCAPED_UNICODE) : $state)
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')->label('Dijawab')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
