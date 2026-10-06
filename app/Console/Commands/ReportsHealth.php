<?php

namespace App\Console\Commands;

use App\Reports\ReportRegistry;
use Illuminate\Console\Command;

class ReportsHealth extends Command
{
    protected $signature = 'reports:health';
    protected $description = 'Cek semua report bisa jalan dengan filter default';

    public function handle(): int
    {
        $fail = 0;
        foreach (ReportRegistry::all() as $key => $def) {
            try {
                $params = [];
                foreach ($def->filters() as $f) $params[$f['name']] = $f['default'] ?? null;
                if ($key === 'anggota-statement') $params['anggota_id'] = \App\Models\Anggota::query()->value('id');
                if ($key === 'shu') $params['tahun'] = (int) date('Y');
                $r = $def->run($params);
                $this->line("OK   {$key} (".count($r->rows).' rows)');
            } catch (\Throwable $e) {
                $fail++;
                $this->error("FAIL {$key}: ".substr($e->getMessage(), 0, 100));
            }
        }
        $this->info($fail === 0 ? 'Semua report OK.' : "{$fail} report gagal.");
        return $fail === 0 ? self::SUCCESS : self::FAILURE;
    }
}
