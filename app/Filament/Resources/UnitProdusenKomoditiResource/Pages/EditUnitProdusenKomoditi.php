<?php

namespace App\Filament\Resources\UnitProdusenKomoditiResource\Pages;

use App\Filament\Resources\UnitProdusenKomoditiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnitProdusenKomoditi extends EditRecord
{
    protected static string $resource = UnitProdusenKomoditiResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
