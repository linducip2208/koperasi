<?php

namespace App\Filament\Resources\TokoPenjualanResource\Pages;

use App\Filament\Resources\TokoPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTokoPenjualans extends ListRecords
{
    protected static string $resource = TokoPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
