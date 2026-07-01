<?php

namespace App\Filament\Resources\PinjamanResource\Pages;

use App\Domain\Pinjaman\PinjamanService;
use App\Filament\Resources\PinjamanResource;
use App\Models\Pinjaman;
use Filament\Resources\Pages\CreateRecord;

class CreatePinjaman extends CreateRecord
{
    protected static string $resource = PinjamanResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return PinjamanService::ajukan([
            'anggota_id'        => $data['anggota_id'],
            'produk_id'         => $data['produk_id'],
            'plafon'            => $data['plafon'],
            'tenor'             => $data['tenor'],
            'tanggal_pengajuan' => $data['tanggal_pengajuan'] ?? now()->toDateString(),
            'tujuan'            => $data['tujuan'] ?? null,
        ]);
    }
}
