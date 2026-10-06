<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Report Center <span>/</span> <span>Scheduled</span></div>
            <div class="kop-page-title">Scheduled Reports</div>
            <div class="kop-page-sub">Harian · mingguan · bulanan · kuartalan · tahunan — dieksekusi via queue/scheduler, bukan HTTP</div>
        </div>
    </div>
    {{ $this->table }}
</x-filament-panels::page>
