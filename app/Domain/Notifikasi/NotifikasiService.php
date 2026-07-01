<?php

namespace App\Domain\Notifikasi;

use App\Models\Anggota;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotifikasiService
{
    public static function reminderAngsuran(Anggota $anggota, array $data): void
    {
        $template = "Yth. {$anggota->nama},\n\n"
            . "Kami ingatkan angsuran pinjaman Anda akan jatuh tempo:\n"
            . "• No. Akad: {$data['nomor_akad']}\n"
            . "• Jumlah: Rp " . number_format($data['jumlah'], 0, ',', '.') . "\n"
            . "• Jatuh Tempo: {$data['jatuh_tempo']}\n\n"
            . "Mohon segera melakukan pembayaran. Terima kasih.";

        if ($anggota->telp) {
            WhatsAppGateway::send($anggota->telp, $template);
        }

        if ($anggota->email) {
            self::sendEmail($anggota->email, 'Reminder Angsuran', $template);
        }
    }

    public static function konfirmasiSetoran(Anggota $anggota, int $jumlah, string $nomorRekening): void
    {
        $msg = "Yth. {$anggota->nama},\n\n"
            . "Setoran simpanan Anda telah berhasil:\n"
            . "• Rek: {$nomorRekening}\n"
            . "• Jumlah: Rp " . number_format($jumlah, 0, ',', '.') . "\n\n"
            . "Terima kasih.";

        if ($anggota->telp) {
            WhatsAppGateway::send($anggota->telp, $msg);
        }
    }

    public static function approvalPinjaman(Anggota $anggota, string $nomorAkad, bool $disetujui, ?string $alasan = null): void
    {
        $status = $disetujui ? 'DISETUJUI' : 'DITOLAK';
        $msg = "Yth. {$anggota->nama},\n\n"
            . "Pengajuan pinjaman Anda nomor {$nomorAkad} telah {$status}.";

        if (! $disetujui && $alasan) {
            $msg .= "\nAlasan: {$alasan}";
        }
        $msg .= "\n\nSilakan menghubungi koperasi untuk informasi lebih lanjut.";

        if ($anggota->telp) {
            WhatsAppGateway::send($anggota->telp, $msg);
        }
    }

    private static function sendEmail(string $to, string $subject, string $body): void
    {
        try {
            Mail::raw($body, function ($mail) use ($to, $subject) {
                $mail->to($to)->subject($subject);
            });
        } catch (\Throwable $e) {
            Log::warning('Email send error: ' . $e->getMessage());
        }
    }
}
