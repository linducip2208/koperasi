<?php
namespace App\Filament\Resources\PinjamanRestrukturisasiResource\Pages;
use App\Filament\Resources\PinjamanRestrukturisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditPinjamanRestrukturisasi extends EditRecord {
    protected static string $resource = PinjamanRestrukturisasiResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
