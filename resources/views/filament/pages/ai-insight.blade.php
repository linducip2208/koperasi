<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Report Center <span>/</span> <span>AI Insights</span></div>
            <div class="kop-page-title">AI Insights</div>
            <div class="kop-page-sub">Hanya agregat anonim yang dianalisis — database tetap sumber kebenaran</div>
        </div>
    </div>

    <form wire:submit.prevent="ask" class="kop-card" style="padding:1.25rem; margin-bottom:1rem;">
        {{ $this->form }}
        <button type="submit" class="kop-btn mt-3">Minta Insight</button>
    </form>

    @if($answer)
        <div class="kop-card" style="padding:1.25rem;">
            <div class="kop-badge info" style="margin-bottom:.5rem;">AI-generated insight</div>
            <div class="text-sm whitespace-pre-line" style="color:var(--kop-text)">{{ $answer }}</div>
        </div>
    @else
        <div class="kop-card kop-empty">
            <div class="kop-empty-icon">🤖</div>
            <h3>Belum ada pertanyaan</h3>
            <p>Contoh: “Jelaskan mengapa tunggakan meningkat.”</p>
        </div>
    @endif
</x-filament-panels::page>
