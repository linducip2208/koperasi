<?php
namespace App\Filament\Resources\TokoSupplierResource\Pages;
use App\Filament\Resources\TokoSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditTokoSupplier extends EditRecord {
    protected static string $resource = TokoSupplierResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
