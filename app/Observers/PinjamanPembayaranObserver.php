<?php

namespace App\Observers;

use App\Models\PinjamanPembayaran;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class PinjamanPembayaranObserver
{
    public function __construct(private WhatsAppService $wa) {}

    public function created(PinjamanPembayaran $bayar): void
    {
        $pinjaman = $bayar->pinjaman()->with('anggota')->first();
        if (!$pinjaman || !$pinjaman->anggota || empty($pinjaman->anggota->telp)) {
            return;
        }

        $sisaPokok = (int) ($pinjaman->saldo_pokok ?? 0);
        $msg = "*✅ Pembayaran Diterima*\n\n"
            . "Halo {$pinjaman->anggota->nama},\n\n"
            . "Pembayaran cicilan Anda sudah kami terima:\n"
            . "• No Akad: {$pinjaman->nomor_akad}\n"
            . "• Tgl Bayar: " . optional($bayar->tanggal)->format('d M Y') . "\n"
            . "• Total: *Rp " . number_format((int) $bayar->total_bayar, 0, ',', '.') . "*\n"
            . "  - Pokok: Rp " . number_format((int) $bayar->alokasi_pokok, 0, ',', '.') . "\n"
            . "  - Margin/Bunga: Rp " . number_format((int) $bayar->alokasi_margin, 0, ',', '.') . "\n"
            . ((int) $bayar->alokasi_denda > 0 ? "  - Denda: Rp " . number_format((int) $bayar->alokasi_denda, 0, ',', '.') . "\n" : '')
            . "• Sisa Pokok: *Rp " . number_format($sisaPokok, 0, ',', '.') . "*\n\n"
            . "Terima kasih.\n_" . config('app.name') . "_";

        try {
            $this->wa->send($pinjaman->anggota->telp, $msg);
        } catch (\Throwable $e) {
            Log::warning('[WhatsApp Notif Pembayaran] gagal: ' . $e->getMessage());
        }
    }
}
