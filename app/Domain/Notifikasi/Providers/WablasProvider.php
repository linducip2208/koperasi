<?php

namespace App\Domain\Notifikasi\Providers;

use App\Domain\Notifikasi\WhatsAppProviderInterface;
use Illuminate\Support\Facades\Http;

class WablasProvider implements WhatsAppProviderInterface
{
    public function __construct(private string $token, private string $apiUrl) {}

    public function name(): string { return 'wablas'; }

    public function send(string $phone, string $message): bool
    {
        $resp = Http::timeout((int) config('services.whatsapp.timeout', 15))
            ->withHeaders(['Authorization' => $this->token])
            ->post(rtrim($this->apiUrl, '/').'/api/send-message', ['phone' => $phone, 'message' => $message]);

        return $resp->successful();
    }
}
