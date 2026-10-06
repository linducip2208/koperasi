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
    use VerifiesWebhookSignature;
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
        $paid = ($callback['status'] ?? '') === 'COMPLETED' || ($callback['transaction_timestamp'] ?? null) !== null;
        $orderId = $callback['external_id'] ?? null;
        $amount = (int) ($callback['transfer_amount'] ?? $callback['amount'] ?? 0);
        $signature = $callback['signature'] ?? $callback['signature_key'] ?? null;
        $status = strtolower($callback['status'] ?? '');

        return [
            'valid'      => $orderId && $this->signatureValid($provider, $orderId, $status, $amount, $signature ? (string) $signature : null, $callback),
            'paid'       => $paid,
            'order_id'   => $orderId,
            'amount'     => $amount,
            'payment_id' => $callback['id'] ?? null,
            'raw'        => $callback,
        ];
    }
}
