<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Report Center <span>/</span> <span>Fraud Alerts</span></div>
            <div class="kop-page-title">Deteksi Anomali</div>
            <div class="kop-page-sub">Rule-based — AI boleh menjelaskan, database tetap sumber kebenaran</div>
        </div>
    </div>

    @forelse($alerts as $a)
        <div class="kop-card" style="padding:1.1rem 1.25rem; margin-bottom:1rem;">
            <div class="flex items-center gap-2 mb-2">
                <span class="kop-badge {{ strtolower($a['severity']) === 'high' ? 'danger' : (strtolower($a['severity']) === 'medium' ? 'warning' : 'info') }}">{{ $a['severity'] }}</span>
                <strong>{{ $a['judul'] }}</strong>
            </div>
            <ul class="kop-timeline">
                @foreach($a['items'] as $it)
                    <li><span class="kop-dot"></span>
                        <div class="text-sm">{{ $it['info'] }}</div>
                        @if($it['waktu'])<div class="text-xs" style="color:var(--kop-muted)">{{ \Carbon\Carbon::parse($it['waktu'])->format('d M Y H:i') }}</div>@endif
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <div class="kop-card kop-empty">
            <div class="kop-empty-icon">🛡️</div>
            <h3>Tidak ada anomali terdeteksi</h3>
            <p>Semua rule bersih pada periode pantau.</p>
        </div>
    @endforelse
</x-filament-panels::page>
