<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Pemeriksaan kesehatan sistem — dipakai command app:health + halaman admin.
 * Setiap check: ['label', 'status' => OK|WARNING|ERROR, 'detail'].
 * TIDAK PERNAH mengembalikan secret.
 */
class SystemHealth
{
    public static function checks(): array
    {
        $checks = [];
        $push = function (string $label, string $status, ?string $detail = null) use (&$checks) {
            $checks[] = compact('label', 'status', 'detail');
        };

        // PHP & framework
        $push('PHP '.PHP_VERSION, version_compare(PHP_VERSION, '8.2.0', '>=') ? 'OK' : 'ERROR');
        $push('Laravel '.\Illuminate\Foundation\Application::VERSION, 'OK');
        $push('App v'.config('product.version').' ('.config('app.env').')',
            config('app.env') === 'production' && config('app.debug') ? 'ERROR' : 'OK',
            config('app.debug') ? 'APP_DEBUG=true' : null);

        foreach (['pdo', 'openssl', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'] as $ext) {
            if (! extension_loaded($ext)) $push("Ekstensi PHP: {$ext}", 'ERROR', 'hilang');
        }
        if (extension_loaded('gd')) $push('Ekstensi PHP: gd', 'OK');

        // Database
        try {
            DB::connection()->getPdo();
            $push('Database ('.config('database.default').')', 'OK');
        } catch (\Throwable $e) {
            $push('Database', 'ERROR', 'tidak terhubung');
        }

        // Storage writable — uji tulis nyata (is_writable() false-negative di sebagian environment).
        foreach (['storage/app' => storage_path('app'), 'storage/logs' => storage_path('logs'), 'bootstrap/cache' => base_path('bootstrap/cache')] as $label => $path) {
            $push("Writable: {$label}", self::isReallyWritable($path) ? 'OK' : 'ERROR', $path);
        }

        // Cache & queue
        try {
            Cache::put('__health', '1', 10);
            $push('Cache ('.config('cache.default').')', Cache::get('__health') === '1' ? 'OK' : 'ERROR');
        } catch (\Throwable) {
            $push('Cache', 'ERROR');
        }
        $push('Queue ('.config('queue.default').')', 'OK', 'failed jobs: '.self::failedJobs());

        // Disk
        $free = @disk_free_space(storage_path());
        $total = @disk_total_space(storage_path());
        if ($free !== false && $total) {
            $pct = round($free / $total * 100, 1);
            $push("Disk bebas {$pct}%", $pct < 10 ? 'ERROR' : ($pct < 20 ? 'WARNING' : 'OK'));
        }

        // Backup freshness
        $latest = self::latestBackup();
        if (! $latest) {
            $push('Backup', 'WARNING', 'belum ada backup');
        } else {
            $age = now()->diffInHours($latest);
            $push('Backup terakhir '.$latest->diffForHumans(), $age > 30 ? 'WARNING' : 'OK');
        }

        // License
        try {
            $domain = strtolower(request()?->getHost() ?? (config('app.url') ? parse_url(config('app.url'), PHP_URL_HOST) : 'localhost'));
            $st = app(\App\Services\LicenseClient::class)->status($domain);
            $push('License: '.$st['status'], in_array($st['status'], ['ACTIVE', 'GRACE_PERIOD'], true) ? ($st['status'] === 'ACTIVE' ? 'OK' : 'WARNING') : 'ERROR',
                $st['offline_since'] ? 'offline sejak '.$st['offline_since'] : null);
        } catch (\Throwable) {
            $push('License', 'WARNING', 'tidak dapat dibaca');
        }

        // Production bypass guard
        if (config('app.env') === 'production' && config('license.dev_bypass')) {
            $push('LICENSE_DEV_BYPASS di production', 'ERROR', 'matikan di .env production!');
        }

        // Mail & integrasi (tanpa secret — hanya konfigurasi)
        $push('Mail ('.config('mail.default').')', config('mail.default') === 'log' ? 'WARNING' : 'OK', 'driver '.config('mail.default'));
        $push('WhatsApp ('.config('services.whatsapp.driver', '?').')',
            (config('services.whatsapp.driver', 'log') === 'log') ? 'WARNING' : 'OK');

        return $checks;
    }

    public static function summary(array $checks): array
    {
        $errors = count(array_filter($checks, fn ($c) => $c['status'] === 'ERROR'));
        $warnings = count(array_filter($checks, fn ($c) => $c['status'] === 'WARNING'));
        return [
            'total' => count($checks),
            'errors' => $errors,
            'warnings' => $warnings,
            'overall' => $errors > 0 ? 'ERROR' : ($warnings > 0 ? 'WARNING' : 'OK'),
        ];
    }

    private static function failedJobs(): int
    {
        try {
            return (int) DB::table('failed_jobs')->count();
        } catch (\Throwable) {
            return 0;
        }
    }

    private static function isReallyWritable(string $dir): bool
    {
        if (! is_dir($dir)) return false;
        $probe = $dir.DIRECTORY_SEPARATOR.'.health-'.getmypid();
        $ok = @file_put_contents($probe, '1') !== false;
        if ($ok) @unlink($probe);
        return $ok;
    }

    private static function latestBackup(): ?\Carbon\Carbon
    {
        try {
            $files = Storage::disk('local')->files('koperasi-backups');
            if (! $files) $files = Storage::disk('local')->files();
            $zips = array_filter($files, fn ($f) => str_ends_with($f, '.zip'));
            if (! $zips) return null;
            $mtimes = array_map(fn ($f) => Storage::disk('local')->lastModified($f), $zips);
            return \Carbon\Carbon::createFromTimestamp(max($mtimes));
        } catch (\Throwable) {
            return null;
        }
    }
}
