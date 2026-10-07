<?php

namespace App\Jobs;

use App\Domain\Notifikasi\WhatsAppGateway;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Kirim WhatsApp via queue: retry 3x dengan backoff, gagal tercatat di failed_jobs.
 * Idempotency di level pemanggil (jangan dispatch 2x untuk event yang sama).
 */
class SendWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public function __construct(public string $phone, public string $message) {}

    public function handle(): void
    {
        if (! WhatsAppGateway::send($this->phone, $this->message)) {
            throw new \RuntimeException('WhatsApp send gagal — retry.');
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::warning('SendWhatsApp gagal permanen', [
            'phone' => substr(preg_replace('/\D/', '', $this->phone), -4),
        ]);
    }
}
