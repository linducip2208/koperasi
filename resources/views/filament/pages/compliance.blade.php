<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Governance <span>/</span> <span>Compliance</span></div>
            <div class="kop-page-title">Compliance Center</div>
            <div class="kop-page-sub">Checklist verifikasi manual — {{ $wajibOk }}/{{ $wajibTotal }} item wajib OK</div>
        </div>
    </div>

    <div class="kop-progress" style="margin-bottom:1rem;">
        <span style="width: {{ $wajibTotal > 0 ? round($wajibOk / $wajibTotal * 100) : 0 }}%;"></span>
    </div>

    <form wire:submit.prevent="save" class="kop-card" style="padding:1.25rem;">
        {{ $this->form }}
        <button type="submit" class="kop-btn mt-3">Simpan Checklist</button>
    </form>

    <p class="text-xs mt-3" style="color:var(--kop-muted)">Checklist ini alat bantu internal dan tidak menggantikan audit/pemeriksaan regulator.</p>
</x-filament-panels::page>
