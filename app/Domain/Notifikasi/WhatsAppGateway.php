<?php

namespace App\Domain\Notifikasi;

use App\Domain\Notifikasi\Providers\FonnteProvider;
use App\Domain\Notifikasi\Providers\LogProvider;
use App\Domain\Notifikasi\Providers\WablasProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

/**
 * Generic WhatsApp Gateway — driver via WhatsAppProviderInterface.
 * Konfigurasi disimpan di tabel settings (group=notifikasi).
 */
class WhatsAppGateway
{
    public static function driver(?string $provider = null): WhatsAppProviderInterface
    {
        $provider ??= Setting::get('wa_provider', 'fonnte', 'notifikasi');
        $apiKey = Setting::get('wa_api_key', '', 'notifikasi');
        $apiUrl = Setting::get('wa_api_url', '', 'notifikasi');

        return match ($provider) {
            'fonnte' => new FonnteProvider($apiKey),
            'wablas' => new WablasProvider($apiKey, $apiUrl),
            default => new LogProvider(),
        };
    }

    public static function send(string $phone, string $message): bool
    {
        $apiKey = Setting::get('wa_api_key', '', 'notifikasi');
        if (empty($apiKey)) {
            Log::warning('WhatsApp API key kosong — skip kirim');
            return false;
        }

        $phone = self::normalize($phone);

        try {
            return self::driver()->send($phone, $message);
        } catch (\Throwable $e) {
            Log::error('WA send error: '.class_basename($e));
            return false;
        }
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
