<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\ReportArchive;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ReportArchivePage extends Page implements HasTable
{
    use HasRoleAccess;
    use InteractsWithTable;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $navigationLabel = 'Arsip Report';
    protected static ?string $title = 'Arsip Report';
    protected static ?int $navigationSort = 55;

    protected static string $view = 'filament.pages.report-archive';

    public function table(Table $table): Table
    {
        return $table
            ->query(ReportArchive::query()->orderByDesc('id'))
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('report_key')->label('Report')->badge(),
                Tables\Columns\TextColumn::make('period')->label('Periode'),
                Tables\Columns\TextColumn::make('report_version')->label('Ver'),
                Tables\Columns\IconColumn::make('checksum_ok')->label('Valid')
                    ->getStateUsing(fn ($record) => $record->verify())->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')->icon('heroicon-o-shield-check')
                    ->action(function ($record) {
                        \Filament\Notifications\Notification::make()
                            ->title($record->verify() ? 'Checksum VALID — arsip utuh.' : 'Checksum RUSAK!')
                            ->{ $record->verify() ? 'success' : 'danger' }()->send();
                    }),
            ])
            ->paginated(15);
    }
}
