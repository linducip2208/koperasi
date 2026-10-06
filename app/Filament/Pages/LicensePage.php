<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Services\LicenseClient;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class LicensePage extends Page
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'license';
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'SYSTEM';
    protected static ?string $navigationLabel = 'License';
    protected static ?string $title = 'License';
    protected static ?int $navigationSort = 97;

    protected static string $view = 'filament.pages.license';

    public function getViewData(): array
    {
        $client = app(LicenseClient::class);
        $domain = strtolower(request()->getHost());
        return ['status' => $client->status($domain), 'domain' => $domain];
    }

    public function recheck(): void
    {
        $client = app(LicenseClient::class);
        $client->recheck(strtolower(request()->getHost()));
        Notification::make()->title('License di-recheck ke server.')->success()->send();
    }
}
