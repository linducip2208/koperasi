<?php

namespace App\Services\Ai;

/**
 * Provider heuristik lokal (tanpa API eksternal): menjelaskan report dari
 * agregat + tren deterministik. Fallback aman bila provider AI luar mati.
 */
class LocalHeuristicProvider implements AiProviderInterface
{
    public function name(): string
    {
        return 'local-heuristic';
    }

    public function explain(string $prompt, array $context): string
    {
        $lines = [];
        $lines[] = 'Ringkasan: '.($context['summary'] ?? '—');
        foreach ((array) ($context['deltas'] ?? []) as $d) {
            $lines[] = "- {$d['label']}: {$d['from']} → {$d['to']} ({$d['change']})";
        }
        foreach ((array) ($context['top_drivers'] ?? []) as $t) {
            $lines[] = "- Pendorong: {$t}";
        }
        if (! empty($context['recommendation'])) {
            $lines[] = 'Rekomendasi: '.$context['recommendation'];
        }
        $lines[] = 'Catatan: angka akuntansi/database tetap sumber kebenaran.';

        return implode("\n", $lines);
    }
}
