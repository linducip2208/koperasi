<?php

namespace App\Services\Payment\Adapters;

use App\Models\PaymentProvider;
use App\Services\Payment\PaymentAdapter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Adapter untuk gateway dengan flow REDIRECT (user di-redirect ke halaman gateway untuk bayar).
 * Cocok untuk: Midtrans Snap, Xendit, DOKU, iPaymu, Tripay, Faspay, dll.
 *
 * User input di admin: nama provider, base_url (mis. https://app.midtrans.com/snap/v1),
 * api_key, dan extra_headers JSON kalau perlu.
 */
class RedirectFormatAdapter implements PaymentAdapter
{
    public function createSession(PaymentProvider $provider, array $payload): array
    {
        $orderId = $payload['order_id'] ?? Str::uuid()->toString();

        $body = [
            'order_id'        => $orderId,
            'gross_amount'    => (int) $payload['amount'],
            'customer_name'   => $payload['customer_name'] ?? '',
            'customer_phone'  => $payload['customer_phone'] ?? '',
            'description'     => $payload['description'] ?? '',
            'callback_url'    => $payload['callback_url'] ?? '',
            'redirect_url'    => $payload['redirect_url'] ?? '',
        ];

        $headers = $this->headers($provider);
        $url     = rtrim($provider->base_url, '/') . '/' . ltrim($provider->session_endpoint ?? 'transactions', '/');

        $resp = Http::withHeaders($headers)
            ->timeout(20)
            ->post($url, $body);

        if (!$resp->successful()) {
            throw new \RuntimeException("Payment session failed: HTTP {$resp->status()} body={$resp->body()}");
        }

        $data = $resp->json();
        return [
            'payment_url' => $data['redirect_url'] ?? $data['payment_url'] ?? $data['checkout_url'] ?? '',
            'payment_id'  => $data['transaction_id'] ?? $data['token'] ?? $data['id'] ?? $orderId,
            'raw'         => $data,
        ];
    }

    public function verifyCallback(PaymentProvider $provider, array $callback): array
    {
        $signature = $callback['signature_key'] ?? $callback['signature'] ?? null;
        $status    = strtolower($callback['transaction_status'] ?? $callback['status'] ?? '');

        return [
            'valid'      => $signature !== null,
            'paid'       => in_array($status, ['settlement', 'success', 'paid', 'captured', 'completed'], true),
            'order_id'   => $callback['order_id']        ?? null,
            'amount'     => (int) ($callback['gross_amount'] ?? $callback['amount'] ?? 0),
            'payment_id' => $callback['transaction_id']  ?? $callback['payment_id'] ?? null,
            'raw'        => $callback,
        ];
    }

    private function headers(PaymentProvider $provider): array
    {
        $headers = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];
        if ($provider->api_key) {
            $headers['Authorization'] = 'Basic ' . base64_encode($provider->api_key . ':');
        }
        foreach ((array) ($provider->extra_headers ?? []) as $k => $v) {
            $headers[$k] = $v;
        }
        return $headers;
    }
}
