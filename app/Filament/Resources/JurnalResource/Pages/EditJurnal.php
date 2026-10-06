<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJurnal extends EditRecord
{
    protected static string $resource = JurnalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn ($record) => ! $record->is_posted),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->is_posted) {
            throw new \RuntimeException('Jurnal posted tidak boleh diedit. Gunakan reversal.');
        }

        if (\App\Domain\Akuntansi\JurnalService::isPeriodClosed($data['tanggal'] ?? $this->record->tanggal)) {
            throw new \RuntimeException('Periode akuntansi sudah di-close (locked).');
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // Sinkronkan total dari baris detail + validasi balance.
        $totals = $this->record->details()->selectRaw('COALESCE(SUM(debit),0) d, COALESCE(SUM(kredit),0) k')->first();
        if ((int) $totals->d !== (int) $totals->k) {
            throw new \RuntimeException("Jurnal tidak balance setelah edit: debit {$totals->d} vs kredit {$totals->k}.");
        }
        $this->record->updateQuietly(['total_debit' => (int) $totals->d, 'total_kredit' => (int) $totals->k]);
    }
}
