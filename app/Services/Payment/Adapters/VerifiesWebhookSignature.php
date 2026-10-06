<?php

namespace App\Services\Payment\Adapters;

use App\Models\PaymentProvider;
use Illuminate\Support\Facades\Log;

/**
 * Verifikasi signature webhook generik berbasis HMAC.
 *
 * Secret diambil dari (urutan prioritas):
 *  1. extra_headers['webhook_secret'] (diset admin per provider)
 *  2. api_key terdekripsi (fallback)
 *
 * Pola yang diterima:
 *  - Midtrans: SHA512(order_id . status_code . gross_amount . serverKey)
 *  - Generik: HMAC-SHA512(order_id . status . amount, secret)
 *
 * Jika provider TIDAK punya secret dan BUKAN sandbox → callback DITOLAK.
 */
trait VerifiesWebhookSignature
{
    protected function webhookSecret(PaymentProvider $provider): ?string
    {
        $extra = (array) ($provider->extra_headers ?? []);
        if (! empty($extra['webhook_secret'])) return (string) $extra['webhook_secret'];
        return $provider->api_key ?: null;
    }

    protected function signatureValid(PaymentProvider $provider, string $orderId, string $status, int $amount, ?string $given, array $callback): bool
    {
        if ($given === null || $given === '') return false;

        $secret = $this->webhookSecret($provider);
        if (! $secret) {
            // Tanpa secret, hanya sandbox yang boleh lewat (itupun dicatat).
            if ($provider->is_sandbox) {
                Log::warning("Webhook tanpa secret diterima (sandbox): provider={$provider->id}");
                return true;
            }
            Log::warning("Webhook ditolak: provider {$provider->id} belum set webhook_secret dan bukan sandbox");
            return false;
        }

        $candidates = [
            hash_hmac('sha512', $orderId.$status.$amount, $secret),
            hash_hmac('sha256', $orderId.$status.$amount, $secret),
        ];

        // Pola Midtrans: order_id + status_code + gross_amount + serverKey
        if (isset($callback['status_code']) && isset($callback['gross_amount'])) {
            $candidates[] = hash('sha512', $orderId.$callback['status_code'].$callback['gross_amount'].$secret);
        }

        foreach ($candidates as $expected) {
            if (hash_equals($expected, strtolower($given))) return true;
        }

        return false;
    }
}
