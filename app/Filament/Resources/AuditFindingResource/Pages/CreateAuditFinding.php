<?php
namespace App\Filament\Resources\AuditFindingResource\Pages;
use App\Filament\Resources\AuditFindingResource;
use Filament\Resources\Pages\CreateRecord;
class CreateAuditFinding extends CreateRecord {
    protected static string $resource = AuditFindingResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['tenant_id'] = \App\Support\CooperativeContext::id();
        $data['created_by'] = auth()->id();
        return $data;
    }
}
