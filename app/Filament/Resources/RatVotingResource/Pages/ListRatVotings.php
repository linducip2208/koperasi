<?php
namespace App\Filament\Resources\RatVotingResource\Pages;
use App\Filament\Resources\RatVotingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListRatVotings extends ListRecords {
    protected static string $resource = RatVotingResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
