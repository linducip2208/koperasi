<?php
namespace App\Filament\Resources\KasOpnameResource\Pages;
use App\Filament\Resources\KasOpnameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditKasOpname extends EditRecord {
    protected static string $resource = KasOpnameResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
