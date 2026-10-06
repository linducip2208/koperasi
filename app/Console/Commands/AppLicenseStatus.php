<?php

namespace App\Console\Commands;

use App\Services\LicenseClient;
use Illuminate\Console\Command;

class AppLicenseStatus extends Command
{
    protected $signature = 'app:license-status';
    protected $description = 'Tampilkan status lisensi instalasi ini';

    public function handle(LicenseClient $client): int
    {
        $domain = strtolower(request()?->getHost() ?? (parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost'));
        $st = $client->status($domain);
        $d = $st['data'] ?? [];

        $this->table(['Field', 'Value'], [
            ['Product', config('product.name').' v'.config('product.version')],
            ['License ID', $d['license_id'] ?? $d['id'] ?? '-'],
            ['Customer', $d['customer'] ?? $d['cooperative'] ?? '-'],
            ['Status', $st['status']],
            ['Plan', $d['plan'] ?? '-'],
            ['Issued', $d['issued_at'] ?? '-'],
            ['Expires', $d['expires_at'] ?? '-'],
            ['Last heartbeat', $st['last_heartbeat'] ?? '-'],
            ['Offline since', $st['offline_since'] ?? '-'],
            ['Grace deadline', $st['grace_deadline'] ?? '-'],
            ['Installation ID', $st['installation_id']],
            ['Features', is_array($d['features'] ?? null) ? implode(', ', $d['features']) : ($d['features'] ?? '-')],
        ]);

        return in_array($st['status'], ['ACTIVE', 'GRACE_PERIOD'], true) ? self::SUCCESS : self::FAILURE;
    }
}
