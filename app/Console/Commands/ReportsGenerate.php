<?php

namespace App\Console\Commands;

use App\Reports\ReportArchiver;
use Illuminate\Console\Command;

class ReportsGenerate extends Command
{
    protected $signature = 'reports:generate {key} {--params=} {--title=}';
    protected $description = 'Generate + arsipkan report (params JSON, mis. --params=\'{"dari":"2026-01-01"}\')';

    public function handle(): int
    {
        $params = $this->option('params') ? json_decode($this->option('params'), true) : [];
        if (! is_array($params)) {
            $this->error('Params bukan JSON valid.');
            return self::FAILURE;
        }

        try {
            $archive = ReportArchiver::archive($this->argument('key'), $params, $this->option('title'));
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->info("Arsip #{$archive->id} dibuat. Checksum OK: ".($archive->verify() ? 'ya' : 'TIDAK'));
        return self::SUCCESS;
    }
}
