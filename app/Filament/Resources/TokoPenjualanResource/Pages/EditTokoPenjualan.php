<?php

namespace App\Filament\Resources\TokoPenjualanResource\Pages;

use App\Filament\Resources\TokoPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTokoPenjualan extends EditRecord
{
    protected static string $resource = TokoPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
