<?php

namespace App\Services\Payment;

use App\Models\PaymentProvider;

interface PaymentAdapter
{
    /**
     * Buat payment session — return array berisi payment_url + payment_id (untuk callback).
     *
     * @param array{
     *   amount:int, order_id:string, customer_name:string, customer_phone:string,
     *   description:string, callback_url:string, redirect_url:string
     * } $payload
     *
     * @return array{payment_url:string, payment_id:string, raw:array}
     */
    public function createSession(PaymentProvider $provider, array $payload): array;

    /**
     * Verifikasi callback signature & status pembayaran.
     */
    public function verifyCallback(PaymentProvider $provider, array $callback): array;
}
