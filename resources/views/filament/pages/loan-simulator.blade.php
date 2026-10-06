<x-filament-panels::page>
    <form wire:submit.prevent="hitung" class="kop-card" style="padding:1.25rem; margin-bottom:1rem;">
        {{ $this->form }}
        <button type="submit" class="kop-btn mt-3">Hitung Simulasi</button>
    </form>

    @if(! empty($this->hasil))
        <div class="kop-grid cols-3" style="margin-bottom:1rem;">
            <div class="kop-card kop-stat"><div><div class="kop-stat-label">Total Pokok</div><div class="kop-stat-value" style="font-size:1.1rem;">Rp {{ number_format($this->hasil['total_pokok'] ?? 0, 0, ',', '.') }}</div></div></div>
            <div class="kop-card kop-stat"><div><div class="kop-stat-label">Total Margin/Bunga</div><div class="kop-stat-value" style="font-size:1.1rem;">Rp {{ number_format($this->hasil['total_margin'] ?? 0, 0, ',', '.') }}</div></div></div>
            <div class="kop-card kop-stat"><div><div class="kop-stat-label">Total Bayar</div><div class="kop-stat-value" style="font-size:1.1rem;">Rp {{ number_format($this->hasil['total_bayar'] ?? 0, 0, ',', '.') }}</div></div></div>
        </div>

        <div class="kop-card" style="overflow:auto;">
            <table class="kop-table">
                <thead><tr><th class="text-right">#</th><th class="text-right">Pokok</th><th class="text-right">Margin</th><th class="text-right">Total</th><th class="text-right">Sisa Pokok</th></tr></thead>
                <tbody>
                    @foreach($this->hasil['schedule'] ?? [] as $s)
                        <tr>
                            <td class="num">{{ $s['angsuran_ke'] }}</td>
                            <td class="num">{{ number_format($s['pokok'], 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($s['margin'], 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($s['total'], 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($s['saldo_pokok'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-filament-panels::page>
