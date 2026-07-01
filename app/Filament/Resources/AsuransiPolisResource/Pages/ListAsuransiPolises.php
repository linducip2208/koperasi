<?php
namespace App\Filament\Resources\AsuransiPolisResource\Pages;
use App\Filament\Resources\AsuransiPolisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListAsuransiPolises extends ListRecords {
    protected static string $resource = AsuransiPolisResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
