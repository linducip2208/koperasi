<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">System <span>/</span> <span>Webhook Center</span></div>
            <div class="kop-page-title">Webhook Center</div>
            <div class="kop-page-sub">Diterima → terverifikasi → diproses / gagal — idempotent + anti-replay</div>
        </div>
    </div>
    <div class="kop-card" style="overflow:auto;">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
