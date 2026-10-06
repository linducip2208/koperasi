<?php

namespace App\Services\Ai;

/**
 * Kontrak provider AI — JANGAN hardcode vendor tertentu di caller.
 * Provider didaftar di AiManager via config('ai.providers').
 */
interface AiProviderInterface
{
    public function name(): string;

    /** Jawab dari prompt + konteks agregat. Kembalikan teks (sudah dilabeli di caller). */
    public function explain(string $prompt, array $context): string;
}
