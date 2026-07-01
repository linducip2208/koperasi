<?php
namespace App\Filament\Resources\AsuransiProdukResource\Pages;
use App\Filament\Resources\AsuransiProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditAsuransiProduk extends EditRecord {
    protected static string $resource = AsuransiProdukResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
