<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Report Center <span>/</span> <span>Data Quality</span></div>
            <div class="kop-page-title">Data Quality Center</div>
            <div class="kop-page-sub"><span class="kop-badge danger">{{ $critical }} critical</span> <span class="kop-badge warning">{{ $warning }} warning</span></div>
        </div>
    </div>

    <div class="kop-grid cols-2">
        @foreach($issues as $i)
            <div class="kop-card" style="padding:1rem 1.1rem; {{ $i['level'] === 'critical' && $i['count'] > 0 ? 'border-color:#fecdd3;' : '' }}">
                <div class="flex items-center justify-between">
                    <span class="kop-badge {{ $i['level'] === 'critical' ? 'danger' : ($i['level'] === 'warning' ? 'warning' : 'info') }}">{{ strtoupper($i['level']) }}</span>
                    <span class="kop-stat-value" style="font-size:1.3rem;">{{ number_format($i['count']) }}</span>
                </div>
                <div class="font-bold text-sm mt-1">{{ $i['label'] }}</div>
                <div class="text-xs" style="color:var(--kop-muted)">{{ $i['detail'] }}</div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
