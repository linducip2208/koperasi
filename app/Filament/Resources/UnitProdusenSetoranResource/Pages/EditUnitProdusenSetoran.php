<?php

namespace App\Filament\Resources\UnitProdusenSetoranResource\Pages;

use App\Filament\Resources\UnitProdusenSetoranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnitProdusenSetoran extends EditRecord
{
    protected static string $resource = UnitProdusenSetoranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
