<?php
namespace App\Filament\Resources\AsuransiKlaimResource\Pages;
use App\Filament\Resources\AsuransiKlaimResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditAsuransiKlaim extends EditRecord {
    protected static string $resource = AsuransiKlaimResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
