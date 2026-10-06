<?php

/*
 * Kontak support/penjualan PRODUK (bukan koperasi) — satu-satunya sumber.
 * Kontak koperasi (WA/telp/alamat) milik Tenant, bukan di sini.
 */
return [
    'whatsapp' => env('SUPPORT_WA', '6281296052010'),
    'whatsapp_display' => env('SUPPORT_WA_DISPLAY', '0812-9605-2010'),
    'email' => env('SUPPORT_EMAIL', 'support@whitelabel.co.id'),
    'phone' => env('SUPPORT_PHONE', ''),
];
