<?php

namespace App\Services\Payment\Adapters;

use App\Models\PaymentProvider;
use App\Services\Payment\PaymentAdapter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Adapter untuk gateway dengan flow QRIS / dynamic QR code.
 * Cocok untuk: Linkaja, OVO, Dana QRIS, NobuPay, dll.
 * User scan QR pakai e-wallet, gateway POST callback ke kita saat sukses.
 */
class QrFormatAdapter implements PaymentAdapter
{
    public function createSession(PaymentProvider $provider, array $payload): array
    {
        $orderId = $payload['order_id'] ?? Str::uuid()->toString();

        $body = [
            'partner_reference_no' => $orderId,
            'amount' => ['value' => (string) $payload['amount'], 'currency' => 'IDR'],
            'merchant_id'  => $provider->merchant_id ?? null,
            'callback_url' => $payload['callback_url'] ?? '',
            'description'  => $payload['description'] ?? '',
        ];

        $headers = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];
        if ($provider->api_key) $headers['Authorization'] = 'Bearer ' . $provider->api_key;
        foreach ((array) ($provider->extra_headers ?? []) as $k => $v) $headers[$k] = $v;

        $url = rtrim($provider->base_url, '/') . '/' . ltrim($provider->session_endpoint ?? 'qris/generate', '/');
        $resp = Http::withHeaders($headers)->timeout(20)->post($url, $body);

        if (!$resp->successful()) {
            throw new \RuntimeException("QRIS session failed: HTTP {$resp->status()}");
        }

        $data = $resp->json();
        return [
            'payment_url' => $data['qr_code_url'] ?? $data['qr_url'] ?? '',
            'payment_id'  => $data['reference_no'] ?? $data['transaction_id'] ?? $orderId,
            'raw'         => $data,
        ];
    }

    public function verifyCallback(PaymentProvider $provider, array $callback): array
    {
        $status = strtolower($callback['status'] ?? '');
        return [
            'valid'      => true,
            'paid'       => in_array($status, ['paid', 'success', 'completed'], true),
            'order_id'   => $callback['partner_reference_no'] ?? $callback['order_id'] ?? null,
            'amount'     => (int) ($callback['amount']['value'] ?? $callback['amount'] ?? 0),
            'payment_id' => $callback['reference_no'] ?? $callback['transaction_id'] ?? null,
            'raw'        => $callback,
        ];
    }
}
