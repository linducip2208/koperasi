<?php
namespace App\Filament\Resources\RekonsiliasiBankResource\Pages;
use App\Filament\Resources\RekonsiliasiBankResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditRekonsiliasiBank extends EditRecord {
    protected static string $resource = RekonsiliasiBankResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
