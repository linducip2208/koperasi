<?php
namespace App\Filament\Resources\AsuransiProdukResource\Pages;
use App\Filament\Resources\AsuransiProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListAsuransiProduks extends ListRecords {
    protected static string $resource = AsuransiProdukResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
