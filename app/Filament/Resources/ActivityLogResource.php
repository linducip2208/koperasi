<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Audit Trail';
    protected static ?string $modelLabel = 'Activity Log';
    protected static ?string $pluralModelLabel = 'Activity Logs';
    protected static ?int $navigationSort = 99;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->size('sm'),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu')
                    ->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('log_name')->label('Modul')->badge()
                    ->color(fn ($state) => match ($state) {
                        'anggota' => 'success', 'pinjaman' => 'warning', 'simpanan' => 'info', default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('event')->label('Event')->badge(),
                Tables\Columns\TextColumn::make('description')->label('Deskripsi')->limit(60)->wrap(),
                Tables\Columns\TextColumn::make('subject_type')->label('Subject')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : '-')->size('sm'),
                Tables\Columns\TextColumn::make('subject_id')->label('ID')->size('sm'),
                Tables\Columns\TextColumn::make('causer.name')->label('Oleh')->placeholder('Sistem')->size('sm'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')->label('Modul')
                    ->options(['anggota' => 'Anggota', 'pinjaman' => 'Pinjaman', 'simpanan' => 'Simpanan']),
                Tables\Filters\SelectFilter::make('event')
                    ->options(['created' => 'Created', 'updated' => 'Updated', 'deleted' => 'Deleted']),
                Tables\Filters\Filter::make('today')->label('Hari Ini')
                    ->query(fn ($q) => $q->whereDate('created_at', today())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->modalHeading('Detail Activity')
                    ->modalContent(fn (Activity $r) => view('filament.activity-log-detail', ['log' => $r])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }

    public static function canCreate(): bool { return false; }
}
