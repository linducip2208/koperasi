<?php
namespace App\Filament\Resources\NotifikasiTemplateResource\Pages;
use App\Filament\Resources\NotifikasiTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListNotifikasiTemplates extends ListRecords {
    protected static string $resource = NotifikasiTemplateResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
