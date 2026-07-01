<?php
namespace App\Filament\Resources\TokoPembelianResource\Pages;
use App\Filament\Resources\TokoPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditTokoPembelian extends EditRecord {
    protected static string $resource = TokoPembelianResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
