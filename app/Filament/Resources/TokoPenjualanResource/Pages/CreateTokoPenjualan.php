<?php

namespace App\Filament\Resources\TokoPenjualanResource\Pages;

use App\Domain\Numbering\NumberingService;
use App\Domain\Toko\PosService;
use App\Filament\Resources\TokoPenjualanResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateTokoPenjualan extends CreateRecord
{
    protected static string $resource = TokoPenjualanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['nomor'])) {
            $data['nomor'] = NumberingService::next('toko_penjualan', 'INV-', '{prefix}{ymd}-{seq:5}');
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        try {
            PosService::proses($this->record);
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Penjualan tersimpan, tapi proses stok/jurnal gagal')
                ->body($e->getMessage())
                ->warning()
                ->persistent()
                ->send();
        }
    }
}
