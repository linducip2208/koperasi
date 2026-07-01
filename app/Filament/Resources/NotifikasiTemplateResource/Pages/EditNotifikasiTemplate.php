<?php
namespace App\Filament\Resources\NotifikasiTemplateResource\Pages;
use App\Filament\Resources\NotifikasiTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditNotifikasiTemplate extends EditRecord {
    protected static string $resource = NotifikasiTemplateResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
