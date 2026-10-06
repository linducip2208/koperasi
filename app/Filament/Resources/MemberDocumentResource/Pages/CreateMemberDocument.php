<?php
namespace App\Filament\Resources\MemberDocumentResource\Pages;
use App\Filament\Resources\MemberDocumentResource;
use Filament\Resources\Pages\CreateRecord;
class CreateMemberDocument extends CreateRecord {
    protected static string $resource = MemberDocumentResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['tenant_id'] = \App\Support\CooperativeContext::id();
        $data['uploaded_by'] = auth()->id();
        $f = $this->data['file_path'] ?? null;
        return $data;
    }
    protected function afterCreate(): void {
        $file = storage_path('app/' . $this->record->file_path);
        if (is_file($file)) {
            $this->record->updateQuietly([
                'mime' => mime_content_type($file) ?: null,
                'ukuran' => filesize($file) ?: 0,
            ]);
        }
    }
}
