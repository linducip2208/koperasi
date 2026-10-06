<?php
namespace App\Filament\Resources\AuditFindingResource\Pages;
use App\Filament\Resources\AuditFindingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListAuditFindings extends ListRecords {
    protected static string $resource = AuditFindingResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
