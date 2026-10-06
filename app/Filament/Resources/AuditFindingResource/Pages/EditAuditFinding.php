<?php
namespace App\Filament\Resources\AuditFindingResource\Pages;
use App\Filament\Resources\AuditFindingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditAuditFinding extends EditRecord {
    protected static string $resource = AuditFindingResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
