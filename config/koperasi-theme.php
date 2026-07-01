<?php

return [
    'default' => env('KOPERASI_THEME', 'stripe'),

    'themes' => [
        'stripe' => [
            'label'       => 'Stripe Style',
            'emoji'       => '🌙',
            'description' => 'Dark navy sidebar + light content + emerald accent. Vibe finansial profesional.',
            'view'        => 'filament.themes.stripe',
        ],
        'linear' => [
            'label'       => 'Linear Dark',
            'emoji'       => '💎',
            'description' => 'Full dark mode + accent ungu-biru. Modern startup tech.',
            'view'        => 'filament.themes.linear',
        ],
        'notion' => [
            'label'       => 'Notion Warm',
            'emoji'       => '🌅',
            'description' => 'Sidebar beige hangat + cream content. Cozy & ramah.',
            'view'        => 'filament.themes.notion',
        ],
        'discord' => [
            'label'       => 'Discord Indigo',
            'emoji'       => '🌊',
            'description' => 'Sidebar indigo gelap + accent neon glow. Playful & fresh.',
            'view'        => 'filament.themes.discord',
        ],
        'emerald' => [
            'label'       => 'Emerald Light (Original)',
            'emoji'       => '🌿',
            'description' => 'Tema asli — hijau emerald all-light dengan glassmorphism halus.',
            'view'        => 'filament.themes.emerald',
        ],
    ],
];
