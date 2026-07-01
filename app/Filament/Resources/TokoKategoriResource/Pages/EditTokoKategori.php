<?php
namespace App\Filament\Resources\TokoKategoriResource\Pages;
use App\Filament\Resources\TokoKategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditTokoKategori extends EditRecord {
    protected static string $resource = TokoKategoriResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
