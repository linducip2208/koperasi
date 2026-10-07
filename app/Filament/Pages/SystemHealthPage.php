<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use App\Support\SystemHealth;
use Filament\Pages\Page;

class SystemHealthPage extends Page
{
    use HasRoleAccess;
    use HasTranslatedNav;

    protected static ?string $permissionModule = 'setting';
    protected static ?string $navKey = 'System Health';
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'SYSTEM';
    protected static ?string $navigationLabel = 'System Health';
    protected static ?string $title = 'System Health';
    protected static ?int $navigationSort = 96;

    protected static string $view = 'filament.pages.system-health';

    public function getViewData(): array
    {
        $checks = SystemHealth::checks();
        return ['checks' => $checks, 'summary' => SystemHealth::summary($checks)];
    }
}
