<?php

namespace App\Filament\Resources\TokoPenjualanResource\Pages;

use App\Domain\Toko\PosService;
use App\Filament\Resources\TokoPenjualanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditTokoPenjualan extends EditRecord
{
    protected static string $resource = TokoPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->jurnal_id || $this->record->status === 'batal') {
            return;
        }

        try {
            PosService::proses($this->record);
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Perubahan tersimpan, tapi proses stok/jurnal gagal')
                ->body($e->getMessage())
                ->warning()
                ->persistent()
                ->send();
        }
    }
}
