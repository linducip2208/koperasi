<?php

namespace App\Services\Payment\Adapters;

use App\Models\PaymentProvider;
use App\Services\Payment\PaymentAdapter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Adapter untuk gateway VA Bank (BCA, Mandiri, BNI, BRI, Permata).
 * Anggota dapat nomor VA → transfer dari mobile banking → gateway konfirmasi via callback.
 */
class VirtualAccountFormatAdapter implements PaymentAdapter
{
    public function createSession(PaymentProvider $provider, array $payload): array
    {
        $orderId = $payload['order_id'] ?? Str::uuid()->toString();

        $body = [
            'external_id'   => $orderId,
            'bank_code'     => $payload['bank_code']     ?? 'BCA',
            'name'          => $payload['customer_name'] ?? 'Anggota',
            'expected_amount' => (int) $payload['amount'],
            'is_closed'     => true,
            'expiration_date' => now()->addDays(2)->toIso8601String(),
        ];

        $headers = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];
        if ($provider->api_key) {
            $headers['Authorization'] = 'Basic ' . base64_encode($provider->api_key . ':');
        }

        $url = rtrim($provider->base_url, '/') . '/' . ltrim($provider->session_endpoint ?? 'callback_virtual_accounts', '/');
        $resp = Http::withHeaders($headers)->timeout(20)->post($url, $body);

        if (!$resp->successful()) {
            throw new \RuntimeException("VA session failed: HTTP {$resp->status()}");
        }

        $data = $resp->json();
        return [
            'payment_url' => $data['account_number'] ?? '',
            'payment_id'  => $data['id'] ?? $orderId,
            'raw'         => $data,
        ];
    }

    public function verifyCallback(PaymentProvider $provider, array $callback): array
    {
        return [
            'valid'      => true,
            'paid'       => ($callback['status'] ?? '') === 'COMPLETED' || ($callback['transaction_timestamp'] ?? null) !== null,
            'order_id'   => $callback['external_id'] ?? null,
            'amount'     => (int) ($callback['transfer_amount'] ?? $callback['amount'] ?? 0),
            'payment_id' => $callback['id'] ?? null,
            'raw'        => $callback,
        ];
    }
}
