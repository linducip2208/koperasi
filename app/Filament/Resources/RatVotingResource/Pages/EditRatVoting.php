<?php
namespace App\Filament\Resources\RatVotingResource\Pages;
use App\Filament\Resources\RatVotingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditRatVoting extends EditRecord {
    protected static string $resource = RatVotingResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
