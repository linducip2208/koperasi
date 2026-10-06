<?php
namespace App\Filament\Resources\MemberDocumentResource\Pages;
use App\Filament\Resources\MemberDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditMemberDocument extends EditRecord {
    protected static string $resource = MemberDocumentResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
