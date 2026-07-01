<?php
namespace App\Filament\Resources\TokoPembelianResource\Pages;
use App\Filament\Resources\TokoPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListTokoPembelians extends ListRecords {
    protected static string $resource = TokoPembelianResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
