<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'whatsapp' => [
        // Driver: "log" (dev) | "fonnte" | "wablas" (production).
        // Catatan: kredensial operasional harian dibaca dari tabel settings
        // (group=notifikasi: wa_provider/wa_api_key/wa_api_url) agar bisa
        // diubah dari admin. Env di bawah adalah default/fallback.
        'driver'   => env('WHATSAPP_DRIVER', 'log'),
        'token'    => env('WHATSAPP_TOKEN', env('FONNTE_API_KEY', '')),
        'sender'   => env('WHATSAPP_SENDER', env('FONNTE_SENDER', '')),
        'wablas_key' => env('WABLAS_API_KEY', ''),
        'wablas_url' => env('WABLAS_URL', ''),
        'timeout'  => env('WHATSAPP_TIMEOUT', 15),
        // URL logo untuk header notifikasi (opsional)
        'logo_url' => env('WHATSAPP_LOGO_URL', ''),
    ],

];
