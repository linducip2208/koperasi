<?php

namespace App\Filament\Resources\RatResource\Pages;

use App\Filament\Resources\RatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRat extends EditRecord
{
    protected static string $resource = RatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
