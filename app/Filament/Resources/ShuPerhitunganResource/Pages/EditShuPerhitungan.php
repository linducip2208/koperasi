<?php

namespace App\Filament\Resources\ShuPerhitunganResource\Pages;

use App\Filament\Resources\ShuPerhitunganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShuPerhitungan extends EditRecord
{
    protected static string $resource = ShuPerhitunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
