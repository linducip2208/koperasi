<?php
namespace App\Filament\Resources\ProcurementResource\Pages;
use App\Filament\Resources\ProcurementResource;
use Filament\Resources\Pages\CreateRecord;
class CreateProcurement extends CreateRecord {
    protected static string $resource = ProcurementResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['tenant_id'] = \App\Support\CooperativeContext::id();
        $data['created_by'] = auth()->id();
        return $data;
    }
}
