<?php

namespace App\Filament\Resources\UnitJasaOrderResource\Pages;

use App\Filament\Resources\UnitJasaOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnitJasaOrder extends EditRecord
{
    protected static string $resource = UnitJasaOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
