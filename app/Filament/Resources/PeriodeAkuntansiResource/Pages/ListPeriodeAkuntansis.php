<?php
namespace App\Filament\Resources\PeriodeAkuntansiResource\Pages;
use App\Filament\Resources\PeriodeAkuntansiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListPeriodeAkuntansis extends ListRecords {
    protected static string $resource = PeriodeAkuntansiResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
