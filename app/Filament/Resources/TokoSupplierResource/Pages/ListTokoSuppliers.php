<?php
namespace App\Filament\Resources\TokoSupplierResource\Pages;
use App\Filament\Resources\TokoSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListTokoSuppliers extends ListRecords {
    protected static string $resource = TokoSupplierResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
