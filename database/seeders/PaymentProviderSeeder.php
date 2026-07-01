<?php

namespace Database\Seeders;

use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class PaymentProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            // ===== Redirect Format (Snap / Hosted Checkout) =====
            [
                'nama'             => 'Midtrans Snap',
                'api_format'       => 'redirect',
                'base_url'         => 'https://app.sandbox.midtrans.com/snap/v1',
                'session_endpoint' => 'transactions',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'Midtrans Snap — redirect ke halaman pembayaran Midtrans. Daftar: https://midtrans.com',
            ],
            [
                'nama'             => 'Xendit Invoice',
                'api_format'       => 'redirect',
                'base_url'         => 'https://api.xendit.co/v2',
                'session_endpoint' => 'invoices',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'Xendit Invoice — redirect ke halaman invoice Xendit. Daftar: https://xendit.co',
            ],
            [
                'nama'             => 'Tripay',
                'api_format'       => 'redirect',
                'base_url'         => 'https://tripay.co.id/api',
                'session_endpoint' => 'transaction/create',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'Tripay — redirect ke halaman pembayaran Tripay. Daftar: https://tripay.co.id',
            ],
            [
                'nama'             => 'DOKU Checkout',
                'api_format'       => 'redirect',
                'base_url'         => 'https://api-sandbox.doku.com',
                'session_endpoint' => 'checkout/v1/payment',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'DOKU — redirect ke halaman checkout DOKU. Daftar: https://doku.com',
            ],
            [
                'nama'             => 'iPaymu',
                'api_format'       => 'redirect',
                'base_url'         => 'https://sandbox.ipaymu.com/api/v2',
                'session_endpoint' => 'payment',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'iPaymu — redirect ke halaman pembayaran iPaymu. Daftar: https://ipaymu.com',
            ],
            [
                'nama'             => 'Faspay',
                'api_format'       => 'redirect',
                'base_url'         => 'https://sandbox.faspay.co.id',
                'session_endpoint' => 'cvr/100011/10',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'Faspay — redirect ke halaman pembayaran Faspay. Daftar: https://faspay.co.id',
            ],

            // ===== QRIS Format =====
            [
                'nama'             => 'LinkAja',
                'api_format'       => 'qris',
                'base_url'         => 'https://api.linkaja.id/v1',
                'session_endpoint' => 'qris/generate',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'LinkAja QRIS — generate QR code pembayaran. Daftar: https://linkaja.id',
            ],
            [
                'nama'             => 'OVO',
                'api_format'       => 'qris',
                'base_url'         => 'https://api.ovo.id/v1',
                'session_endpoint' => 'payment/qr',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'OVO — generate QR code pembayaran. Daftar: https://ovo.id',
            ],
            [
                'nama'             => 'Dana',
                'api_format'       => 'qris',
                'base_url'         => 'https://api.dana.id/v1',
                'session_endpoint' => 'merchant/qr',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'Dana — generate QR code. Daftar: https://dana.id',
            ],
            [
                'nama'             => 'QRIS (NobuPay)',
                'api_format'       => 'qris',
                'base_url'         => 'https://api.nobubank.com/v1',
                'session_endpoint' => 'qris/create',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'NobuPay QRIS — generate QR standar QRIS. Daftar: https://nobubank.com',
            ],

            // ===== Virtual Account Format =====
            [
                'nama'             => 'BCA Virtual Account',
                'api_format'       => 'virtual_account',
                'base_url'         => 'https://api-sandbox.bca.co.id',
                'session_endpoint' => 'va',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'BCA VA — generate virtual account BCA. Daftar: https://bca.co.id',
            ],
            [
                'nama'             => 'Bank Mandiri VA',
                'api_format'       => 'virtual_account',
                'base_url'         => 'https://api-sandbox.bankmandiri.co.id',
                'session_endpoint' => 'va',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'Mandiri VA — generate virtual account Bank Mandiri. Daftar: https://bankmandiri.co.id',
            ],
            [
                'nama'             => 'BRI Virtual Account',
                'api_format'       => 'virtual_account',
                'base_url'         => 'https://api-sandbox.bri.co.id',
                'session_endpoint' => 'va',
                'is_sandbox'       => true,
                'aktif'            => false,
                'catatan'          => 'BRI VA — generate virtual account BRI. Daftar: https://bri.co.id',
            ],
        ];

        foreach ($providers as $p) {
            PaymentProvider::firstOrCreate(
                ['nama' => $p['nama']],
                $p
            );
        }
    }
}
