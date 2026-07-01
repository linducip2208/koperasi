<?php
namespace App\Filament\Resources\PeriodeAkuntansiResource\Pages;
use App\Filament\Resources\PeriodeAkuntansiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditPeriodeAkuntansi extends EditRecord {
    protected static string $resource = PeriodeAkuntansiResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
