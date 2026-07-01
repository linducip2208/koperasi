<?php
namespace App\Filament\Resources\SimpananBlokirResource\Pages;
use App\Filament\Resources\SimpananBlokirResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListSimpananBlokirs extends ListRecords {
    protected static string $resource = SimpananBlokirResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
