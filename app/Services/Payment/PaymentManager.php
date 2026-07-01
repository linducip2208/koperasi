<?php

namespace App\Services\Payment;

use App\Models\PaymentProvider;
use App\Services\Payment\Adapters\QrFormatAdapter;
use App\Services\Payment\Adapters\RedirectFormatAdapter;
use App\Services\Payment\Adapters\VirtualAccountFormatAdapter;
use InvalidArgumentException;

class PaymentManager
{
    /**
     * Buat sesi pembayaran via provider yang user pilih.
     *
     * @param array{amount:int, order_id:string, customer_name:string, customer_phone:string, description:string, callback_url:string, redirect_url:string} $payload
     * @return array{payment_url:string, payment_id:string, raw:array}
     */
    public function createSession(PaymentProvider $provider, array $payload): array
    {
        return $this->adapterFor($provider->api_format)->createSession($provider, $payload);
    }

    public function verifyCallback(PaymentProvider $provider, array $callback): array
    {
        return $this->adapterFor($provider->api_format)->verifyCallback($provider, $callback);
    }

    public static function availableFormats(): array
    {
        return [
            'redirect'        => 'Redirect-based (Midtrans Snap, Xendit, DOKU, Tripay, iPaymu, Faspay, …)',
            'qris'            => 'QRIS / Dynamic QR (LinkAja, Dana, OVO QRIS, NobuPay, …)',
            'virtual_account' => 'Virtual Account Bank (BCA/Mandiri/BNI/BRI/Permata via gateway)',
        ];
    }

    private function adapterFor(string $format): PaymentAdapter
    {
        return match ($format) {
            'redirect'        => new RedirectFormatAdapter,
            'qris'            => new QrFormatAdapter,
            'virtual_account' => new VirtualAccountFormatAdapter,
            default => throw new InvalidArgumentException("Format '{$format}' tidak didukung."),
        };
    }
}
