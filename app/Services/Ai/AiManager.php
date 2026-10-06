<?php

namespace App\Services\Ai;

/**
 * AiManager: registry provider + sanitasi konteks + label output.
 *
 * - Hanya agregat/sanitized context yang diteruskan ke provider.
 * - NIK penuh, password, API key, private key TIDAK PERNAH dikirim.
 * - Output selalu diawali label "AI-generated insight".
 */
class AiManager
{
    public static function provider(?string $name = null): AiProviderInterface
    {
        $name ??= config('ai.default', 'local-heuristic');
        return match ($name) {
            default => new LocalHeuristicProvider(),
        };
    }

    public static function sanitize(array $context): array
    {
        $drop = ['password', 'api_key', 'api_secret', 'private_key', 'token', 'nik', 'ktp', 'pin'];
        array_walk_recursive($context, function (&$v, $k) use ($drop) {
            if (in_array(strtolower((string) $k), $drop, true)) $v = '***';
        });
        // Masking NIK parsial bila terlanjur ada (tampil 4 digit akhir saja).
        if (isset($context['nik_preview'])) {
            $context['nik_preview'] = '•••• '.substr((string) $context['nik_preview'], -4);
        }
        return $context;
    }

    public static function explain(string $prompt, array $context, ?string $provider = null): string
    {
        $out = self::provider($provider)->explain($prompt, self::sanitize($context));

        activity('reports')->causedBy(auth()->user())->withProperties([
            'prompt' => mb_substr($prompt, 0, 200),
        ])->log('ai_report_requested');

        return "🤖 AI-generated insight (bukan kebenaran akuntansi):\n".$out;
    }

    /** Konteks tunggakan: agregat aman untuk pertanyaan "mengapa tunggakan naik". */
    public static function overdueContext(string $dari, string $sampai): array
    {
        $prevDari = \Carbon\Carbon::parse($dari)->subMonth()->toDateString();
        $prevSampai = \Carbon\Carbon::parse($dari)->subDay()->toDateString();

        $cur = (int) \App\Models\Pinjaman::whereIn('status', ['aktif', 'macet'])->where('tunggakan_hari', '>', 0)->sum('saldo_pokok');
        $byProduct = \App\Models\Pinjaman::with('produk')->whereIn('status', ['aktif', 'macet'])
            ->where('tunggakan_hari', '>', 0)->get()->groupBy(fn ($p) => $p->produk->nama ?? '-')
            ->map(fn ($g) => $g->sum('saldo_pokok'))->sortDesc()->take(3);

        $jt = (int) \App\Models\PinjamanJadwal::whereBetween('tanggal_jatuh_tempo', [$dari, $sampai])->sum('total_angsuran');
        $terkumpul = (int) \App\Models\PinjamanPembayaran::where('status', 'disetujui')->whereDate('tanggal', '>=', $dari)->whereDate('tanggal', '<=', $sampai)->sum('total_bayar');

        $drivers = [];
        foreach ($byProduct as $prod => $val) $drivers[] = "{$prod} Rp ".number_format($val, 0, ',', '.');

        return [
            'summary' => 'Tunggakan berjalan Rp '.number_format($cur, 0, ',', '.').' pada periode '.$dari.' s/d '.$sampai,
            'deltas' => [[
                'label' => 'Collection periode', 'from' => 'Rp '.number_format($jt, 0, ',', '.'),
                'to' => 'Rp '.number_format($terkumpul, 0, ',', '.'),
                'change' => $jt > 0 ? round($terkumpul / $jt * 100, 1).'% terkumpul' : 'n/a',
            ]],
            'top_drivers' => $drivers,
            'recommendation' => 'Fokus penagihan pada 3 produk di atas; review kolektabilitas >30 hari.',
        ];
    }
}
