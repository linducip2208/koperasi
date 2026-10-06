<x-filament-panels::page>
    @vite('resources/js/reports.js')
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Report Center <span>/</span> <span>Executive Dashboard</span></div>
            <div class="kop-page-title">Executive Dashboard <span class="kop-badge info">{{ $label }}</span></div>
            <div class="kop-page-sub">Semua angka dari database live — bukan dummy</div>
        </div>
        <div class="flex gap-2 kop-no-print">
            @foreach(['today' => 'Hari', 'week' => 'Minggu', 'month' => 'Bulan', 'quarter' => 'Kuartal', 'year' => 'Tahun', 'last_year' => 'Thn Lalu'] as $k => $l)
                <button wire:click="$set('periode', '{{ $k }}')" class="{{ $periode === $k ? 'kop-btn' : 'kop-btn secondary' }}" style="font-size:.75rem;">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    <div class="kop-grid cols-3" style="margin-bottom:1rem;">
        @foreach($kpi as $k)
            <div class="kop-card kop-stat">
                <div>
                    <div class="kop-stat-label">{{ $k['label'] }}</div>
                    <div class="kop-stat-value" style="font-size:1.2rem;">{{ $k['value'] }}</div>
                    <div class="kop-stat-sub">{{ $k['sub'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="kop-grid cols-3">
        @foreach($charts as $ch)
            <div class="kop-card" style="padding:1rem 1.1rem;">
                <div class="font-bold text-sm mb-2" style="color:var(--kop-text)">{{ $ch['title'] }}</div>
                <div style="height:230px;"><canvas data-chart='@json($ch)'></canvas></div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
