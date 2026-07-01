<?php

namespace App\Filament\Resources\TokoBarangResource\Pages;

use App\Filament\Resources\TokoBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTokoBarang extends EditRecord
{
    protected static string $resource = TokoBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
