<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class UpdateCenterPage extends Page
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'setting';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-circle';
    protected static ?string $navigationGroup = '⚙️ Pengaturan';
    protected static ?string $navigationLabel = 'Update Center';
    protected static ?string $title = 'Update Center';
    protected static ?int $navigationSort = 98;

    protected static string $view = 'filament.pages.update-center';

    public function getViewData(): array
    {
        return [
            'current' => config('product.version'),
            'lastCheck' => Cache::get('update:last-check'),
        ];
    }

    public function checkNow(): void
    {
        Artisan::call('app:update-check');
        $this->dispatch('refresh');
    }

    public function backupNow(): void
    {
        Artisan::call('app:backup');
    }
}
