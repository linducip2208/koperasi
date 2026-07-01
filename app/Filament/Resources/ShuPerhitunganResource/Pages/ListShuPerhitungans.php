<?php

namespace App\Filament\Resources\ShuPerhitunganResource\Pages;

use App\Filament\Resources\ShuPerhitunganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShuPerhitungans extends ListRecords
{
    protected static string $resource = ShuPerhitunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
