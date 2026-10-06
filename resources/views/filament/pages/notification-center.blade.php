<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Dashboard <span>/</span> <span>Notifikasi</span></div>
            <div class="kop-page-title">Pusat Notifikasi</div>
            <div class="kop-page-sub">Peristiwa penting: pinjaman, simpanan, RAT, report, import, lisensi, workflow</div>
        </div>
    </div>
    <div class="kop-card" style="overflow:auto;">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
