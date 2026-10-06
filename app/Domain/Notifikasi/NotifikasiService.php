<?php

namespace App\Domain\Notifikasi;

use App\Jobs\SendWhatsApp;
use App\Models\Anggota;
use App\Models\NotifikasiTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Notifikasi terpusat: template dari DB (NotifikasiTemplate, variabel {nama} dsb),
 * fallback ke teks bawaan bila template belum dikonfigurasi.
 * WhatsApp dikirim via queue (SendWhatsApp); email via Mail.
 */
class NotifikasiService
{
    public static function reminderAngsuran(Anggota $anggota, array $data): void
    {
        $vars = [
            'nama' => $anggota->nama,
            'nomor_akad' => $data['nomor_akad'] ?? '-',
            'nominal' => 'Rp '.number_format($data['jumlah'] ?? 0, 0, ',', '.'),
            'jatuh_tempo' => $data['jatuh_tempo'] ?? '-',
            'koperasi' => \App\Models\Tenant::current()?->displayName() ?? config('app.name'),
        ];
        $fallback = "Yth. {nama},\n\nKami ingatkan angsuran pinjaman Anda akan jatuh tempo:\n"
            ."• No. Akad: {nomor_akad}\n• Jumlah: {nominal}\n• Jatuh Tempo: {jatuh_tempo}\n\nMohon segera melakukan pembayaran. Terima kasih.";

        self::kirim($anggota, 'cicilan_jatuh_tempo', $vars, $fallback, 'Reminder Angsuran');
    }

    public static function konfirmasiSetoran(Anggota $anggota, int $jumlah, string $nomorRekening): void
    {
        $vars = [
            'nama' => $anggota->nama,
            'rekening' => $nomorRekening,
            'nominal' => 'Rp '.number_format($jumlah, 0, ',', '.'),
            'koperasi' => \App\Models\Tenant::current()?->displayName() ?? config('app.name'),
        ];
        $fallback = "Yth. {nama},\n\nSetoran simpanan Anda telah berhasil:\n• Rek: {rekening}\n• Jumlah: {nominal}\n\nTerima kasih.";

        self::kirim($anggota, 'setoran_simpanan', $vars, $fallback, 'Konfirmasi Setoran');
    }

    public static function approvalPinjaman(Anggota $anggota, string $nomorAkad, bool $disetujui, ?string $alasan = null): void
    {
        $vars = [
            'nama' => $anggota->nama,
            'nomor_akad' => $nomorAkad,
            'status' => $disetujui ? 'DISETUJUI' : 'DITOLAK',
            'alasan' => $alasan ? "\nAlasan: {$alasan}" : '',
            'koperasi' => \App\Models\Tenant::current()?->displayName() ?? config('app.name'),
        ];
        $fallback = "Yth. {nama},\n\nPengajuan pinjaman Anda nomor {nomor_akad} telah {status}.{alasan}\n\nSilakan menghubungi koperasi untuk informasi lebih lanjut.";

        self::kirim($anggota, 'pencairan_pinjaman', $vars, $fallback, 'Status Pengajuan Pinjaman');
    }

    protected static function kirim(Anggota $anggota, string $event, array $vars, string $fallback, string $subjectDefault): void
    {
        $render = fn (?string $tpl) => self::render($tpl ?? $fallback, $vars);

        $wa = NotifikasiTemplate::where('event', $event)->where('channel', 'whatsapp')->where('aktif', true)->first();
        if ($anggota->telp) {
            SendWhatsApp::dispatch(WhatsAppGateway::normalize($anggota->telp), $render($wa?->body));
        }

        $email = NotifikasiTemplate::where('event', $event)->where('channel', 'email')->where('aktif', true)->first();
        if ($anggota->email && $email) {
            self::sendEmail($anggota->email, $email->subject ?: $subjectDefault, $render($email->body));
        } elseif ($anggota->email && $event === 'cicilan_jatuh_tempo') {
            self::sendEmail($anggota->email, $subjectDefault, $render($fallback));
        }
    }

    protected static function render(string $template, array $vars): string
    {
        foreach ($vars as $k => $v) {
            $template = str_replace('{'.$k.'}', (string) $v, $template);
        }
        return $template;
    }

    protected static function sendEmail(string $to, string $subject, string $body): void
    {
        try {
            $tenant = \App\Models\Tenant::current();
            Mail::raw($body, function ($mail) use ($to, $subject, $tenant) {
                $mail->to($to)->subject($subject);
                if ($tenant?->email) $mail->from($tenant->email, $tenant->displayName());
            });
        } catch (\Throwable $e) {
            Log::warning('Email send error: '.class_basename($e));
        }
    }
}
