<?php

namespace App\Filament\Resources\UnitJasaLayananResource\Pages;

use App\Filament\Resources\UnitJasaLayananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnitJasaLayanans extends ListRecords
{
    protected static string $resource = UnitJasaLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
