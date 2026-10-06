<?php

namespace App\Console\Commands;

use App\Support\SystemHealth;
use Illuminate\Console\Command;

class AppHealth extends Command
{
    protected $signature = 'app:health';
    protected $description = 'Tampilkan status kesehatan sistem (PHP, DB, storage, backup, license)';

    public function handle(): int
    {
        $checks = SystemHealth::checks();
        $summary = SystemHealth::summary($checks);

        $rows = array_map(fn ($c) => [$c['label'], $c['status'], $c['detail'] ?? '-'], $checks);
        $this->table(['Check', 'Status', 'Detail'], $rows);
        $this->info("Overall: {$summary['overall']} ({$summary['errors']} error, {$summary['warnings']} warning dari {$summary['total']} check)");

        return $summary['errors'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
