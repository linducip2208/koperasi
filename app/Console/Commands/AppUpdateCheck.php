<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AppUpdateCheck extends Command
{
    protected $signature = 'app:update-check';
    protected $description = 'Cek versi terbaru ke license server (tanpa mengubah apapun)';

    public function handle(): int
    {
        $current = config('product.version');
        $this->info("Versi terpasang: v{$current}");

        try {
            $resp = Http::timeout(10)->acceptJson()->post(
                rtrim(config('license.server_url'), '/').'/api/version/check',
                ['product' => config('product.slug'), 'version' => $current]
            );
        } catch (\Throwable $e) {
            $this->warn('Server update tidak terjangkau: '.$e->getMessage());
            return self::SUCCESS;
        }

        if (! $resp->successful()) {
            $this->warn('Check gagal (HTTP '.$resp->status().').');
            return self::SUCCESS;
        }

        $body = $resp->json();
        Cache::put('update:last-check', [
            'at' => now()->toDateTimeString(),
            'latest' => $body['latest_version'] ?? null,
            'notes' => $body['release_notes'] ?? null,
            'download' => $body['download_url'] ?? null,
        ], now()->addDay());

        $latest = $body['latest_version'] ?? null;
        if (! $latest) {
            $this->info('Tidak ada info versi baru.');
            return self::SUCCESS;
        }

        if (version_compare($latest, $current, '>')) {
            $this->warn("Update tersedia: v{$latest}");
            if (! empty($body['release_notes'])) $this->line($body['release_notes']);
            $this->line('Alur aman: php artisan app:backup → maintenance mode → update files → migrate → app:health.');
        } else {
            $this->info('Sudah versi terbaru.');
        }

        return self::SUCCESS;
    }
}
