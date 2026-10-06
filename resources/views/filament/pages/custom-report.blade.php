<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Report Center <span>/</span> <span>Custom Builder</span></div>
            <div class="kop-page-title">Custom Report Builder</div>
            <div class="kop-page-sub">Sumber data whitelist — tanpa SQL mentah. Maks 2000 baris.</div>
        </div>
    </div>

    <div class="kop-grid cols-2">
        <div class="kop-card" style="padding:1.25rem;">
            <form wire:submit.prevent="saveCustom">
                {{ $this->form }}
                <button type="submit" class="kop-btn mt-3">Simpan & Jalankan</button>
            </form>
            <h3 class="font-bold text-sm mt-6 mb-2">Tersimpan</h3>
            <ul class="text-sm space-y-1">
                @forelse($saved as $s)
                    <li><a href="{{ route('filament.admin.pages.custom-report', ['run' => $s->id]) }}" style="color:var(--kop-primary); font-weight:600;">{{ $s->name }}</a>
                        <span class="text-xs" style="color:var(--kop-muted)">· {{ $s->data_source }}</span></li>
                @empty
                    <li class="text-sm" style="color:var(--kop-muted)">Belum ada custom report.</li>
                @endforelse
            </ul>
        </div>

        <div class="kop-card" style="padding:1.25rem; overflow:auto;">
            @if(empty($result))
                <div class="kop-empty">
                    <div class="kop-empty-icon">🛠️</div>
                    <h3>Belum ada hasil</h3>
                    <p>Isi form lalu Simpan & Jalankan.</p>
                </div>
            @else
                <div class="font-bold text-sm mb-2">{{ $result['report_name'] ?? '' }} ({{ count($result['rows']) }} baris)</div>
                <table class="kop-table">
                    <thead><tr>@foreach($result['columns'] as $c)<th>{{ $c['label'] }}</th>@endforeach</tr></thead>
                    <tbody>
                        @foreach($result['rows'] as $row)
                            <tr>@foreach($result['columns'] as $c)<td>{{ is_array($row[$c['key']] ?? '') ? json_encode($row[$c['key']]) : ($row[$c['key']] ?? '') }}</td>@endforeach</tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-filament-panels::page>
