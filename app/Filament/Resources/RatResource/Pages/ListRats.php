<?php

namespace App\Filament\Resources\RatResource\Pages;

use App\Filament\Resources\RatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRats extends ListRecords
{
    protected static string $resource = RatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
