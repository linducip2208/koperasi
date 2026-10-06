<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\ScheduledReport;
use App\Reports\ReportRegistry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ScheduledReportPage extends Page implements HasTable
{
    use HasRoleAccess;
    use InteractsWithTable;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $navigationLabel = 'Scheduled Reports';
    protected static ?string $title = 'Scheduled Reports';
    protected static ?int $navigationSort = 57;

    protected static string $view = 'filament.pages.scheduled-report';

    public function table(Table $table): Table
    {
        $options = collect(ReportRegistry::all())->mapWithKeys(fn ($d) => [$d->key() => $d->name()])->all();
        return $table
            ->query(ScheduledReport::query()->orderByDesc('id'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('report_key')->label('Report')->badge(),
                Tables\Columns\TextColumn::make('frequency')->label('Frekuensi')->badge(),
                Tables\Columns\TextColumn::make('action')->label('Aksi'),
                Tables\Columns\IconColumn::make('aktif')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('next_run_at')->label('Berikutnya')->dateTime('d M H:i'),
                Tables\Columns\TextColumn::make('last_run_at')->label('Terakhir')->dateTime('d M H:i')->placeholder('-'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->model(ScheduledReport::class)
                    ->form([
                        TextInput::make('name')->label('Nama')->required()->maxLength(100),
                        Select::make('report_key')->label('Report')->options($options)->required()->searchable(),
                        Select::make('frequency')->label('Frekuensi')->options([
                            'daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan',
                            'quarterly' => 'Kuartalan', 'yearly' => 'Tahunan',
                        ])->required(),
                        Select::make('action')->label('Aksi')->options(['archive' => 'Arsipkan', 'email' => 'Arsip + Email'])->default('archive'),
                        TextInput::make('email_to')->label('Email tujuan (bila Email)')->email(),
                        Toggle::make('aktif')->label('Aktif')->default(true),
                    ])
                    ->mutateFormDataUsing(fn ($data) => $data + [
                        'params' => [], 'created_by' => auth()->id(),
                        'next_run_at' => ScheduledReport::nextRun($data['frequency']),
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('run_now')
                    ->label('Jalankan')->icon('heroicon-o-play')->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['next_run_at' => now()]);
                        \Illuminate\Support\Facades\Artisan::call('reports:scheduled');
                        \Filament\Notifications\Notification::make()->title('Scheduler dijalankan.')->success()->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->paginated(15);
    }
}
