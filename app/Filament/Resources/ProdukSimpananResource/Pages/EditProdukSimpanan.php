<?php

namespace App\Filament\Resources\ProdukSimpananResource\Pages;

use App\Filament\Resources\ProdukSimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProdukSimpanan extends EditRecord
{
    protected static string $resource = ProdukSimpananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
