<?php

namespace App\Services\Ai;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

/**
 * Provider HTTP generik untuk API chat-completion (OpenAI-compatible):
 * endpoint, model, dan key dari settings grup=ai (bukan hardcode vendor).
 * Cocok untuk Ollama / LM Studio lokal maupun API kompatibel lain.
 */
class GenericHttpAiProvider implements AiProviderInterface
{
    public function name(): string
    {
        return 'http-generic';
    }

    public function explain(string $prompt, array $context): string
    {
        $endpoint = Setting::get('ai_endpoint', '', 'ai');
        $model = Setting::get('ai_model', '', 'ai');
        $apiKey = Setting::get('ai_api_key', '', 'ai');

        if ($endpoint === '' || $model === '') {
            throw new \RuntimeException('Provider AI HTTP belum dikonfigurasi (settings grup ai).');
        }

        $system = 'Anda analis koperasi. Jawab ringkas Bahasa Indonesia dari KONTEKS agregat berikut. '
            .'Jangan membuat angka baru. Akhiri dengan satu rekomendasi operasional. '
            .'Konteks: '.json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);

        $headers = ['Content-Type' => 'application/json'];
        if ($apiKey !== '') {
            $headers['Authorization'] = 'Bearer '.$apiKey;
        }

        $resp = Http::withHeaders($headers)
            ->timeout((int) Setting::get('ai_timeout', 60, 'ai'))
            ->post(rtrim($endpoint, '/').'/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.2,
                'max_tokens' => 800,
            ]);

        if (! $resp->successful()) {
            throw new \RuntimeException('AI HTTP error '.$resp->status());
        }

        $text = $resp->json('choices.0.message.content');
        if (! is_string($text) || trim($text) === '') {
            throw new \RuntimeException('Respons AI kosong.');
        }

        return trim($text);
    }
}
