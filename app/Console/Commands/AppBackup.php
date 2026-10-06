<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AppBackup extends Command
{
    protected $signature = 'app:backup {--full : Backup penuh (DB + files), default hanya DB}';
    protected $description = 'Backup manual aman (dipakai sebelum update / operasi destruktif)';

    public function handle(): int
    {
        $this->info('Memulai backup...');
        $code = Artisan::call(
            'backup:run',
            $this->option('full') ? [] : ['--only-db' => true],
            $this->output
        );

        if ($code !== 0) {
            $this->error('Backup GAGAL. Jangan lanjutkan update.');
            return self::FAILURE;
        }

        $this->info('Backup selesai OK.');
        return self::SUCCESS;
    }
}
