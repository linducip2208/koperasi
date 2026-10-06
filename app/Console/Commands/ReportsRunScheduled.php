<?php

namespace App\Console\Commands;

use App\Models\ScheduledReport;
use App\Reports\ReportArchiver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReportsRunScheduled extends Command
{
    protected $signature = 'reports:scheduled';
    protected $description = 'Jalankan scheduled reports yang jatuh tempo (archive / email)';

    public function handle(): int
    {
        $due = ScheduledReport::where('aktif', true)
            ->where(function ($q) {
                $q->whereNull('next_run_at')->orWhere('next_run_at', '<=', now());
            })->get();

        if ($due->isEmpty()) {
            $this->info('Tidak ada scheduled report jatuh tempo.');
            return self::SUCCESS;
        }

        foreach ($due as $s) {
            try {
                $archive = ReportArchiver::archive($s->report_key, (array) $s->params, $s->name.' — '.now()->format('d M Y'), $s->created_by);

                if ($s->action === 'email' && $s->email_to) {
                    Mail::raw(
                        "Scheduled report '{$s->name}' periode ".now()->format('d M Y')." telah dibuat.\nArsip #{$archive->id} — unduh di admin Report Center.",
                        fn ($m) => $m->to($s->email_to)->subject("[Koperasi] Scheduled Report: {$s->name}")
                    );
                }

                $s->update([
                    'last_run_at' => now(),
                    'next_run_at' => ScheduledReport::nextRun($s->frequency),
                ]);
                $this->info("OK: {$s->name} → arsip #{$archive->id}");
            } catch (\Throwable $e) {
                Log::error('Scheduled report gagal', ['id' => $s->id, 'error' => $e->getMessage()]);
                $this->error("Gagal: {$s->name} — {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}
