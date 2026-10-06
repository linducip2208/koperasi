<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Operations <span>/</span> <span>Collection Center</span></div>
            <div class="kop-page-title">Collection Center</div>
            <div class="kop-page-sub">Antrian bucket, assign kolektor, follow-up + janji bayar, reminder WA</div>
        </div>
    </div>

    @if(! empty($performance))
        <div class="kop-grid cols-4" style="margin-bottom:1rem;">
            @foreach($performance as $p)
                <div class="kop-card kop-stat"><div>
                    <div class="kop-stat-label">{{ $p['nama'] }}</div>
                    <div class="kop-stat-value" style="font-size:1.1rem;">{{ $p['followup'] }} FU</div>
                    <div class="kop-stat-sub">{{ $p['assigned'] }} akad dipegang</div>
                </div></div>
            @endforeach
        </div>
    @endif

    <div class="kop-card" style="overflow:auto;">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
