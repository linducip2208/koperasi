<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class NotificationCenterPage extends Page implements HasTable
{
    use HasRoleAccess;
    use HasTranslatedNav;
    use InteractsWithTable;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navKey = 'Pusat Notifikasi';
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'DASHBOARD';
    protected static ?string $navigationLabel = 'Notifikasi';
    protected static ?string $title = 'Pusat Notifikasi';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.notification-center';

    public string $filter = 'all';

    public function table(Table $table): Table
    {
        $logs = ['pinjaman', 'simpanan', 'rat', 'rat_voting', 'rat_kehadiran', 'rat_suara', 'reports', 'jurnal', 'import', 'ppob', 'collection', 'workflow', 'documents', 'license'];
        return $table
            ->query(Activity::with('causer')->whereIn('log_name', $logs)->orderByDesc('id'))
            ->columns([
                Tables\Columns\TextColumn::make('log_name')->label('Kanal')->badge(),
                Tables\Columns\TextColumn::make('description')->label('Peristiwa')->wrap(),
                Tables\Columns\TextColumn::make('causer.name')->label('Aktor')->placeholder('Sistem'),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')->label('Kanal')->options(array_combine($logs, $logs)),
            ])
            ->paginated(20);
    }
}
