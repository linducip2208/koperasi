<?php
namespace App\Filament\Resources\AsuransiKlaimResource\Pages;
use App\Filament\Resources\AsuransiKlaimResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListAsuransiKlaims extends ListRecords {
    protected static string $resource = AsuransiKlaimResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
