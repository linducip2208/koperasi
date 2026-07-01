<?php
namespace App\Filament\Resources\PinjamanRestrukturisasiResource\Pages;
use App\Filament\Resources\PinjamanRestrukturisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListPinjamanRestrukturisasis extends ListRecords {
    protected static string $resource = PinjamanRestrukturisasiResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
