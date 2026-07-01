<?php

namespace App\Filament\Resources\ProdukSimpananResource\Pages;

use App\Filament\Resources\ProdukSimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProdukSimpanans extends ListRecords
{
    protected static string $resource = ProdukSimpananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
