<?php

namespace App\Http\Controllers;

use App\Models\PaymentProvider;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request, string $providerCode)
    {
        $provider = PaymentProvider::where('kode', $providerCode)->where('aktif', true)->firstOrFail();

        $callback = $request->all();
        Log::info("Payment callback received from {$providerCode}", $callback);

        try {
            $result = (new PaymentManager)->verifyCallback($provider, $callback);

            if (!($result['valid'] ?? false)) {
                Log::warning("Payment callback invalid signature from {$providerCode}");
                return response()->json(['status' => 'invalid_signature'], 400);
            }

            if ($result['paid'] ?? false) {
                $this->processPayment($result);
                Log::info("Payment confirmed: order={$result['order_id']}, amount={$result['amount']}");
            }

            return response()->json(['status' => 'ok']);
        } catch (\Throwable $e) {
            Log::error("Payment webhook error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    protected function processPayment(array $result): void
    {
        $orderId = $result['order_id'] ?? null;
        if (!$orderId) return;

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
        $invoiceId = (int) str_replace('INV-', '', $orderId);

        \App\Models\SimpananTransaksi::create([
            'tenant_id'  => 1,
            'simpanan_id' => $invoiceId,
            'tanggal'    => now(),
            'jenis'      => 'setor',
            'jumlah'     => (int) ($result['amount'] ?? 0),
            'keterangan' => "Pembayaran online: {$result['payment_id']}",
        ]);
    }

    protected function processPinjamanPayment(string $orderId, array $result): void
    {
        $parts = explode('-', $orderId);
        $pinjamanId = (int) ($parts[1] ?? 0);
        if (!$pinjamanId) return;

        \App\Models\PinjamanPembayaran::create([
            'tenant_id'   => 1,
            'pinjaman_id' => $pinjamanId,
            'tanggal'     => now(),
            'total_bayar' => (int) ($result['amount'] ?? 0),
            'alokasi_pokok'  => (int) ($result['amount'] ?? 0),
            'alokasi_margin' => 0,
            'alokasi_denda'  => 0,
            'metode_bayar'   => 'online',
            'keterangan'     => "Pembayaran online: {$result['payment_id']}",
        ]);
    }

    protected function processSimpananPayment(string $orderId, array $result): void
    {
        $simpananId = (int) str_replace('SIMP-', '', $orderId);

        \App\Models\SimpananTransaksi::create([
            'tenant_id'   => 1,
            'simpanan_id' => $simpananId,
            'tanggal'     => now(),
            'jenis'       => 'setor',
            'jumlah'      => (int) ($result['amount'] ?? 0),
            'keterangan'  => "Setor online: {$result['payment_id']}",
        ]);
    }
}
