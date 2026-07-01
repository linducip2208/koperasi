<?php
namespace App\Filament\Resources\TagihanMasterResource\Pages;
use App\Filament\Resources\TagihanMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditTagihanMaster extends EditRecord {
    protected static string $resource = TagihanMasterResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
