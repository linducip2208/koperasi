<?php

namespace App\Filament\Resources\UnitJasaOrderResource\Pages;

use App\Filament\Resources\UnitJasaOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnitJasaOrders extends ListRecords
{
    protected static string $resource = UnitJasaOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
