<?php

namespace App\Observers;

use App\Models\Pinjaman;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class PinjamanObserver
{
    public function __construct(private WhatsAppService $wa) {}

    public function updated(Pinjaman $pinjaman): void
    {
        if (!$pinjaman->wasChanged('tanggal_pencairan') || empty($pinjaman->tanggal_pencairan)) {
            return;
        }

        $anggota = $pinjaman->anggota;
        if (!$anggota || empty($anggota->telp)) {
            return;
        }

        try {
            $this->wa->notifyPencairan(
                $anggota->telp,
                $anggota->nama,
                (int) $pinjaman->plafon,
                (int) $pinjaman->tenor,
            );
        } catch (\Throwable $e) {
            Log::warning('[WhatsApp Notif Pencairan] gagal: ' . $e->getMessage());
        }
    }
}
