<?php

namespace App\Services;

use App\Models\Anggota;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    public static function send(string $to, string $toName, string $subject, string $body): bool
    {
        if (empty($to)) return false;

        try {
            Mail::send('email.notification', [
                'nama'    => $toName,
                'subject' => $subject,
                'body'    => nl2br($body),
                'appName' => config('app.name', 'KoperasiApp'),
                'tahun'   => date('Y'),
            ], function ($message) use ($to, $toName, $subject) {
                $message->to($to, $toName)
                    ->subject("[KoperasiApp] {$subject}");
            });

            Log::info("Email sent to {$to} — {$subject}");
            return true;
        } catch (\Throwable $e) {
            Log::warning("Email failed to {$to}: " . $e->getMessage());
            return false;
        }
    }

    public static function notifySetoran(Anggota $anggota, int $jumlah, string $jenisSimpanan, int $saldoBaru): bool
    {
        if (empty($anggota->email)) return false;

        $subject = "Setoran Berhasil — {$jenisSimpanan}";
        $body = "Halo {$anggota->nama},\n\n"
            . "Setoran simpanan Anda telah diterima:\n"
            . "• Jenis: {$jenisSimpanan}\n"
            . "• Nominal: Rp " . number_format($jumlah, 0, ',', '.') . "\n"
            . "• Saldo terbaru: Rp " . number_format($saldoBaru, 0, ',', '.') . "\n\n"
            . "Terima kasih.\n— " . config('app.name');

        return self::send($anggota->email, $anggota->nama, $subject, $body);
    }

    public static function notifyPencairan(Anggota $anggota, int $plafon, int $tenor): bool
    {
        if (empty($anggota->email)) return false;

        $subject = "Pinjaman Dicairkan";
        $body = "Halo {$anggota->nama},\n\n"
            . "Pinjaman Anda telah dicairkan:\n"
            . "• Plafon: Rp " . number_format($plafon, 0, ',', '.') . "\n"
            . "• Tenor: {$tenor} bulan\n\n"
            . "Cicilan pertama mengikuti jadwal yang tertera di bukti akad.\n"
            . "— " . config('app.name');

        return self::send($anggota->email, $anggota->nama, $subject, $body);
    }

    public static function notifyReminder(Anggota $anggota, string $nomorAkad, int $jumlah, string $jatuhTempo, int $hari): bool
    {
        if (empty($anggota->email)) return false;

        $subject = "Reminder Cicilan — H-{$hari}";
        $body = "Halo {$anggota->nama},\n\n"
            . "Cicilan pinjaman Anda akan jatuh tempo dalam {$hari} hari:\n"
            . "• No. Akad: {$nomorAkad}\n"
            . "• Jumlah: Rp " . number_format($jumlah, 0, ',', '.') . "\n"
            . "• Jatuh Tempo: {$jatuhTempo}\n\n"
            . "Mohon segera melakukan pembayaran.\n"
            . "— " . config('app.name');

        return self::send($anggota->email, $anggota->nama, $subject, $body);
    }

    public static function notifyPengumuman(string $email, string $nama, string $judul, string $isi): bool
    {
        $subject = "Pengumuman: {$judul}";
        $body = "Halo {$nama},\n\n{$isi}\n\n— " . config('app.name');
        return self::send($email, $nama, $subject, $body);
    }
}
