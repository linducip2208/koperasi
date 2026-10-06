<?php

namespace App\Jobs;

use App\Imports\ImportEngine;
use App\Models\ImportBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/** Import besar via queue: baca file tersimpan → validasi → import atomic. */
class ProcessImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 3600;

    public function __construct(public int $batchId) {}

    public function handle(): void
    {
        $batch = ImportBatch::findOrFail($this->batchId);
        $batch->update(['status' => 'importing']);

        try {
            $rows = ImportEngine::parse(Storage::disk('local')->path($batch->file_path));
            $result = ImportEngine::validate($batch->tipe, $rows);
            ImportEngine::import($batch, $result['valid']);
        } catch (\Throwable $e) {
            $batch->update(['status' => 'failed', 'summary' => substr($e->getMessage(), 0, 500)]);
            Log::error('Import queued gagal', ['batch' => $batch->id, 'error' => $e->getMessage()]);
        }
    }
}
