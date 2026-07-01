<?php

namespace App\Filament\Resources\TokoBarangResource\Pages;

use App\Filament\Resources\TokoBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTokoBarangs extends ListRecords
{
    protected static string $resource = TokoBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
