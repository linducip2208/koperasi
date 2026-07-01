<?php
namespace App\Filament\Resources\TokoSatuanResource\Pages;
use App\Filament\Resources\TokoSatuanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditTokoSatuan extends EditRecord {
    protected static string $resource = TokoSatuanResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
