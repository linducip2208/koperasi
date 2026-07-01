<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp gateway service.
 *
 * Mendukung dua mode (toggle via WHATSAPP_DRIVER di .env):
 *  - "log"    : Hanya log message ke storage/logs/laravel.log (default, untuk dev)
 *  - "fonnte" : Kirim real via Fonnte API (https://fonnte.com)
 *
 * Pemakaian:
 *   $wa->send('081234567890', 'Halo, ini test pesan');
 *   $wa->sendTemplate($anggota, 'setoran', ['nominal' => 500000]);
 */
class WhatsAppService
{
    public function __construct(
        private string $driver = '',
        private string $token = '',
        private string $logoUrl = ''
    ) {
        $this->driver  = $driver  ?: (string) config('services.whatsapp.driver', 'log');
        $this->token   = $token   ?: (string) config('services.whatsapp.token', '');
        $this->logoUrl = $logoUrl ?: (string) config('services.whatsapp.logo_url', '');
    }

    /**
     * Kirim pesan ke nomor tujuan.
     *
     * @param string $phone  Nomor HP (contoh: 081234567890 atau +6281234567890)
     * @param string $message  Isi pesan (max 4096 char)
     * @return bool true jika sukses kirim/log, false jika gagal API
     */
    public function send(string $phone, string $message): bool
    {
        $phone = $this->normalizePhone($phone);
        if (empty($phone)) {
            return false;
        }

        return match ($this->driver) {
            'fonnte' => $this->sendViaFonnte($phone, $message),
            default  => $this->sendViaLog($phone, $message),
        };
    }

    /**
     * Kirim notifikasi setoran simpanan.
     */
    public function notifySetoran(string $phone, string $namaAnggota, int $jumlah, string $jenisSimpanan, int $saldoBaru): bool
    {
        $msg = "*✅ Setoran Berhasil*\n\n"
            . "Halo {$namaAnggota},\n\n"
            . "Setoran simpanan Anda telah diterima:\n"
            . "• Jenis: {$jenisSimpanan}\n"
            . "• Nominal: Rp " . number_format($jumlah, 0, ',', '.') . "\n"
            . "• Saldo terbaru: *Rp " . number_format($saldoBaru, 0, ',', '.') . "*\n\n"
            . "Terima kasih.\n_" . config('app.name') . "_";

        return $this->send($phone, $msg);
    }

    /**
     * Kirim reminder cicilan jatuh tempo.
     */
    public function notifyJatuhTempo(string $phone, string $namaAnggota, int $jumlah, string $tanggalJatuhTempo, int $angsuranKe): bool
    {
        $msg = "*⏰ Reminder Cicilan*\n\n"
            . "Halo {$namaAnggota},\n\n"
            . "Cicilan ke-{$angsuranKe} akan jatuh tempo pada *{$tanggalJatuhTempo}*.\n"
            . "Jumlah: *Rp " . number_format($jumlah, 0, ',', '.') . "*\n\n"
            . "Mohon lakukan pembayaran sebelum tanggal tersebut untuk menghindari denda.\n\n"
            . "_" . config('app.name') . "_";

        return $this->send($phone, $msg);
    }

    /**
     * Kirim notifikasi pencairan pinjaman.
     */
    public function notifyPencairan(string $phone, string $namaAnggota, int $plafon, int $tenor): bool
    {
        $msg = "*🎉 Pinjaman Cair*\n\n"
            . "Halo {$namaAnggota},\n\n"
            . "Pinjaman Anda telah dicairkan:\n"
            . "• Plafon: Rp " . number_format($plafon, 0, ',', '.') . "\n"
            . "• Tenor: {$tenor} bulan\n\n"
            . "Cicilan pertama mengikuti jadwal yang tertera di bukti akad.\n\n"
            . "_" . config('app.name') . "_";

        return $this->send($phone, $msg);
    }

    /**
     * Broadcast undangan RAT ke banyak anggota sekaligus.
     *
     * @param array<int, array{phone:string, nama:string}> $recipients
     */
    public function broadcastRat(array $recipients, string $tanggal, string $tempat): array
    {
        $sent = 0;
        $failed = 0;
        foreach ($recipients as $r) {
            $msg = "*📅 Undangan RAT*\n\n"
                . "Yth. {$r['nama']},\n\n"
                . "Dengan hormat, mengundang Bapak/Ibu untuk hadir pada Rapat Anggota Tahunan koperasi:\n"
                . "• Tanggal: *{$tanggal}*\n"
                . "• Tempat: {$tempat}\n\n"
                . "Mohon konfirmasi kehadiran. Terima kasih.\n\n"
                . "_" . config('app.name') . "_";

            if ($this->send($r['phone'], $msg)) $sent++; else $failed++;
        }
        return ['sent' => $sent, 'failed' => $failed];
    }

    private function sendViaLog(string $phone, string $message): bool
    {
        Log::channel('single')->info("[WhatsApp DEV] To: {$phone}", ['message' => $message]);
        return true;
    }

    private function sendViaFonnte(string $phone, string $message): bool
    {
        if (empty($this->token)) {
            Log::warning('[WhatsApp Fonnte] Token kosong, fallback ke log');
            return $this->sendViaLog($phone, $message);
        }

        try {
            $response = Http::withHeaders(['Authorization' => $this->token])
                ->timeout(15)
                ->asForm()
                ->post('https://api.fonnte.com/send', [
                    'target'  => $phone,
                    'message' => $message,
                ]);

            if ($response->successful()) {
                $body = $response->json();
                return ($body['status'] ?? false) === true;
            }

            Log::error('[WhatsApp Fonnte] Failed', ['status' => $response->status(), 'body' => $response->body()]);
            return false;
        } catch (\Throwable $e) {
            Log::error('[WhatsApp Fonnte] Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Normalisasi format nomor: 081234... → 6281234...
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }
        return $phone;
    }
}
