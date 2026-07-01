<?php

namespace App\Filament\Resources\UnitProdusenKomoditiResource\Pages;

use App\Filament\Resources\UnitProdusenKomoditiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnitProdusenKomoditis extends ListRecords
{
    protected static string $resource = UnitProdusenKomoditiResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
