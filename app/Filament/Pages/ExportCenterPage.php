<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Reports\ReportRegistry;
use Filament\Pages\Page;

class ExportCenterPage extends Page
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $navigationLabel = 'Export Center';
    protected static ?string $title = 'Export Center';
    protected static ?int $navigationSort = 54;

    protected static string $view = 'filament.pages.export-center';

    public function getViewData(): array
    {
        return ['defs' => ReportRegistry::all(), 'categories' => ReportRegistry::categories()];
    }
}
