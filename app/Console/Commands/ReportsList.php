<?php

namespace App\Console\Commands;

use App\Reports\ReportArchiver;
use App\Reports\ReportRegistry;
use Illuminate\Console\Command;

class ReportsList extends Command
{
    protected $signature = 'reports:list';
    protected $description = 'Daftar semua report terdaftar';

    public function handle(): int
    {
        $rows = [];
        foreach (ReportRegistry::all() as $def) {
            $rows[] = [$def->key(), $def->name(), $def->category(), implode(',', $def->exports())];
        }
        $this->table(['Key', 'Nama', 'Kategori', 'Export'], $rows);
        return self::SUCCESS;
    }
}
