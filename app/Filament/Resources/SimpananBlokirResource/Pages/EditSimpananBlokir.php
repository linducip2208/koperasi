<?php
namespace App\Filament\Resources\SimpananBlokirResource\Pages;
use App\Filament\Resources\SimpananBlokirResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditSimpananBlokir extends EditRecord {
    protected static string $resource = SimpananBlokirResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
