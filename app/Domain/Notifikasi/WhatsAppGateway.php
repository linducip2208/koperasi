<?php

namespace App\Domain\Notifikasi;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Generic WhatsApp Gateway — support Fonnte, WAblas, atau API Cloud.
 * Konfigurasi disimpan di tabel settings (group=notifikasi).
 */
class WhatsAppGateway
{
    public static function send(string $phone, string $message): bool
    {
        $provider = Setting::get('wa_provider', 'fonnte', 'notifikasi');
        $apiKey   = Setting::get('wa_api_key', '', 'notifikasi');
        $apiUrl   = Setting::get('wa_api_url', '', 'notifikasi');

        if (empty($apiKey)) {
            Log::warning('WhatsApp API key kosong — skip kirim');
            return false;
        }

        $phone = self::normalize($phone);

        try {
            return match ($provider) {
                'fonnte' => self::sendFonnte($phone, $message, $apiKey),
                'wablas' => self::sendWablas($phone, $message, $apiKey, $apiUrl),
                default  => false,
            };
        } catch (\Throwable $e) {
            Log::error('WA send error: ' . $e->getMessage());
            return false;
        }
    }

    private static function sendFonnte(string $phone, string $message, string $token): bool
    {
        $resp = Http::timeout(10)
            ->withHeaders(['Authorization' => $token])
            ->asForm()
            ->post('https://api.fonnte.com/send', [
                'target'  => $phone,
                'message' => $message,
            ]);

        return $resp->successful();
    }

    private static function sendWablas(string $phone, string $message, string $token, string $apiUrl): bool
    {
        $resp = Http::timeout(10)
            ->withHeaders(['Authorization' => $token])
            ->post(rtrim($apiUrl, '/') . '/api/send-message', [
                'phone'   => $phone,
                'message' => $message,
            ]);

        return $resp->successful();
    }

    public static function normalize(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }
        if (! str_starts_with($phone, '62')) {
            return '62' . $phone;
        }
        return $phone;
    }
}
