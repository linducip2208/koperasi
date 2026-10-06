<?php

namespace App\Console\Commands;

use App\Services\LicenseClient;
use Illuminate\Console\Command;

class LicenseHeartbeat extends Command
{
    protected $signature = 'koperasi:license-heartbeat';
    protected $description = 'Heartbeat lisensi terjadwal (dengan grace 7 hari bila server unreachable)';

    public function handle(LicenseClient $client): int
    {
        $domain = strtolower(parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost');
        $st = $client->recheck($domain);
        $this->info("License {$domain}: {$st['status']}");
        return in_array($st['status'], ['ACTIVE', 'GRACE_PERIOD', 'UNPAIRED'], true) ? self::SUCCESS : self::FAILURE;
    }
}
