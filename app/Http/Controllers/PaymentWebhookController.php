<?php

namespace App\Http\Controllers;

use App\Models\PaymentProvider;
use App\Models\WebhookEvent;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request, string $providerCode)
    {
        $provider = PaymentProvider::where('kode', $providerCode)
            ->orWhere('id', $providerCode)
            ->where('aktif', true)
            ->first();

        if (! $provider) {
            return response()->json(['status' => 'unknown_provider'], 404);
        }

        $callback = $request->all();

        // Jangan log raw payload (bisa berisi PII/token) — hanya ringkasan.
        Log::info('Payment callback received', [
            'provider' => $provider->kode ?? $provider->id,
            'order_id' => $callback['order_id'] ?? $callback['external_id'] ?? $callback['partner_reference_no'] ?? null,
            'status' => $callback['status'] ?? $callback['transaction_status'] ?? null,
        ]);

        try {
            $result = (new PaymentManager)->verifyCallback($provider, $callback);

            if (! ($result['valid'] ?? false)) {
                Log::warning('Payment callback invalid signature', ['provider' => $provider->id]);
                return response()->json(['status' => 'invalid_signature'], 400);
            }

            $amount = (int) ($result['amount'] ?? 0);
            if ($amount <= 0) {
                return response()->json(['status' => 'invalid_amount'], 422);
            }

            $paymentId = (string) ($result['payment_id'] ?? '');
            if ($paymentId === '') {
                return response()->json(['status' => 'missing_payment_id'], 422);
            }

            // Idempotency: retry gateway dengan payment_id sama tidak diproses ulang.
            $event = WebhookEvent::firstOrCreate(
                ['payment_provider_id' => $provider->id, 'payment_id' => $paymentId],
                [
                    'order_id' => $result['order_id'],
                    'amount' => $amount,
                    'status' => 'received',
                    'raw' => $this->maskedRaw($callback),
                ]
            );

            if ($event->processed_at) {
                return response()->json(['status' => 'ok', 'duplicate' => true]);
            }

            if ($result['paid'] ?? false) {
                DB::transaction(function () use ($result, $event) {
                    $this->processPayment($result);
                    $event->update(['status' => 'processed', 'processed_at' => now()]);
                });
                Log::info('Payment confirmed', ['order' => $result['order_id'], 'amount' => $amount]);
            }

            return response()->json(['status' => 'ok']);
        } catch (\Throwable $e) {
            // Pesan exception hanya ke server log (tidak dikembalikan ke gateway).
            Log::error('Payment webhook error', ['provider' => $provider->id, 'error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /** Simpan raw ter-masking: buang field sensitif sebelum persist/log. */
    protected function maskedRaw(array $callback): array
    {
        foreach (['signature', 'signature_key', 'token', 'api_key', 'card_number', 'phone', 'email'] as $k) {
            if (array_key_exists($k, $callback)) $callback[$k] = '***';
        }
        return $callback;
    }

    protected function processPayment(array $result): void
    {
        $orderId = $result['order_id'] ?? null;
        if (! $orderId) return;

        if (str_starts_with($orderId, 'INV-')) {
            $this->processInvoicePayment($orderId, $result);
        } elseif (str_starts_with($orderId, 'PINJ-')) {
            $this->processPinjamanPayment($orderId, $result);
        } elseif (str_starts_with($orderId, 'SIMP-')) {
            $this->processSimpananPayment($orderId, $result);
        }
    }

    protected function processInvoicePayment(string $orderId, array $result): void
    {
        $simpanan = \App\Models\Simpanan::find((int) str_replace('INV-', '', $orderId));
        if (! $simpanan) return;

        $this->setorOnline($simpanan, (int) ($result['amount'] ?? 0), (string) ($result['payment_id'] ?? ''));
    }

    protected function processPinjamanPayment(string $orderId, array $result): void
    {
        $parts = explode('-', $orderId);
        $pinjaman = \App\Models\Pinjaman::find((int) ($parts[1] ?? 0));
        if (! $pinjaman) return;

        // Uang sudah diterima gateway → catat + verifikasi otomatis (bukan manual).
        $bayar = \App\Domain\Pinjaman\PinjamanService::bayar(
            $pinjaman, (int) ($result['amount'] ?? 0), $this->onlineKasId(),
            now(), 'online', null
        );
        \App\Domain\Pinjaman\PinjamanService::verifikasiPembayaran(
            $bayar, true, 'Auto-verifikasi webhook: '.substr((string) ($result['payment_id'] ?? ''), 0, 60)
        );
    }

    protected function processSimpananPayment(string $orderId, array $result): void
    {
        $simpanan = \App\Models\Simpanan::find((int) str_replace('SIMP-', '', $orderId));
        if (! $simpanan) return;

        $this->setorOnline($simpanan, (int) ($result['amount'] ?? 0), (string) ($result['payment_id'] ?? ''));
    }

    /** Setoran online lewat service resmi: nomor unik + saldo + jurnal. */
    protected function setorOnline(\App\Models\Simpanan $simpanan, int $amount, string $paymentId): void
    {
        \App\Domain\Simpanan\SimpananService::setor(
            $simpanan, $amount, $this->onlineKasId(), now(), 'online',
            'Pembayaran online: '.substr($paymentId, 0, 60)
        );
    }

    /** Kas transit online: rekening bank aktif pertama, fallback kas aktif pertama. */
    protected function onlineKasId(): int
    {
        $kas = \App\Models\Kas::where('aktif', true)->where('tipe', 'bank')->first()
            ?? \App\Models\Kas::where('aktif', true)->firstOrFail();
        return $kas->id;
    }
}
