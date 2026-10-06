<?php

namespace App\Domain\Notifikasi\Providers;

use App\Domain\Notifikasi\WhatsAppProviderInterface;
use Illuminate\Support\Facades\Log;

class LogProvider implements WhatsAppProviderInterface
{
    public function name(): string { return 'log'; }

    public function send(string $phone, string $message): bool
    {
        Log::channel('single')->info("[WhatsApp:{$phone}] ".mb_substr($message, 0, 200));
        return true;
    }
}
