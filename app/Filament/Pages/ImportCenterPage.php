<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use App\Imports\ImportDefinition;
use App\Models\ImportBatch;
use Filament\Pages\Page;

class ImportCenterPage extends Page
{
    use HasRoleAccess;
    use HasTranslatedNav;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navKey = 'Import Center';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $navigationLabel = 'Import Center';
    protected static ?string $title = 'Import Center';
    protected static ?int $navigationSort = 53;

    protected static string $view = 'filament.pages.import-center';

    public ?int $previewBatch = null;

    public function mount(): void
    {
        $this->previewBatch = request()->query('batch') ? (int) request()->query('batch') : null;
    }

    public function getViewData(): array
    {
        return [
            'types' => ImportDefinition::types(),
            'preview' => $this->previewBatch ? ImportBatch::find($this->previewBatch) : null,
            'history' => ImportBatch::orderByDesc('id')->limit(20)->get(),
        ];
    }
}
