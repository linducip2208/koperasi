<?php

/*
 * Metadata produk tunggal — jangan hardcode nama/versi di puluhan file.
 * Ubah di sini (atau via .env) dan seluruh UI mengikutinya.
 */
return [
    'name' => env('PRODUCT_NAME', 'Koperasi Management System'),
    'slug' => env('PRODUCT_SLUG', 'koperasi'),
    'version' => '1.0.0',
    'vendor' => env('PRODUCT_VENDOR', 'Whitelabel'),
    'support_url' => env('SUPPORT_URL', 'https://whitelabel.co.id/support'),
    'docs_url' => env('DOCS_URL', '/docs'),
    'license_server' => env('LICENSE_SERVER_URL', 'https://whitelabel.co.id'),
];
