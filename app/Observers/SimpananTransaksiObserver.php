<?php

namespace App\Observers;

use App\Models\SimpananTransaksi;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class SimpananTransaksiObserver
{
    public function __construct(private WhatsAppService $wa) {}

    public function created(SimpananTransaksi $tx): void
    {
        if (!in_array($tx->jenis, ['setor', 'setoran'], true) || ($tx->jumlah ?? 0) <= 0) {
            return;
        }

        $simpanan = $tx->simpanan()->with(['anggota', 'produk'])->first();
        if (!$simpanan || !$simpanan->anggota || empty($simpanan->anggota->telp)) {
            return;
        }

        try {
            $this->wa->notifySetoran(
                $simpanan->anggota->telp,
                $simpanan->anggota->nama,
                (int) $tx->jumlah,
                $simpanan->produk->nama ?? 'Simpanan',
                (int) ($tx->saldo_sesudah ?? $simpanan->saldo),
            );
        } catch (\Throwable $e) {
            Log::warning('[WhatsApp Notif Setoran] gagal: ' . $e->getMessage());
        }
    }
}
