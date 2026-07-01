<?php
namespace App\Filament\Resources\TokoSatuanResource\Pages;
use App\Filament\Resources\TokoSatuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListTokoSatuans extends ListRecords {
    protected static string $resource = TokoSatuanResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
