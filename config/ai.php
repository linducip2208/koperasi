<?php

return [
    // Provider default: local-heuristic (tanpa API eksternal).
    // Tambah provider (OpenAI/Gemini/Ollama/…) sebagai class AiProviderInterface
    // lalu daftarkan di AiManager::provider() — JANGAN hardcode di caller.
    'default' => env('AI_PROVIDER', 'local-heuristic'),
];
