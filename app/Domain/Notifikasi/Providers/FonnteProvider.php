<?php

namespace App\Domain\Notifikasi\Providers;

use App\Domain\Notifikasi\WhatsAppProviderInterface;
use Illuminate\Support\Facades\Http;

class FonnteProvider implements WhatsAppProviderInterface
{
    public function __construct(private string $token) {}

    public function name(): string { return 'fonnte'; }

    public function send(string $phone, string $message): bool
    {
        $resp = Http::timeout((int) config('services.whatsapp.timeout', 15))
            ->withHeaders(['Authorization' => $this->token])
            ->asForm()
            ->post('https://api.fonnte.com/send', ['target' => $phone, 'message' => $message]);

        return $resp->successful();
    }
}
