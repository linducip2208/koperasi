<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Dashboard <span>/</span> <span>Cari</span></div>
            <div class="kop-page-title">Pencarian Universal</div>
            <div class="kop-page-sub">Hasil mengikuti izin Anda — min. 3 karakter</div>
        </div>
    </div>

    <form wire:submit.prevent="$refresh" class="kop-card" style="padding:1.25rem; margin-bottom:1rem;">
        {{ $this->form }}
        <button type="submit" class="kop-btn mt-3">Cari</button>
    </form>

    @if($q && strlen($q) >= 3)
        @forelse($groups as $g => $items)
            <h3 class="font-bold text-sm mb-2 mt-4">{{ $g }} ({{ count($items) }})</h3>
            <div class="kop-card" style="padding:.5rem 1rem; margin-bottom:.5rem;">
                @foreach($items as $it)
                    <div class="py-2 border-b border-gray-100 last:border-0 text-sm">
                        @if($it['url'])<a href="{{ $it['url'] }}" style="color:var(--kop-primary); font-weight:600;">{{ $it['label'] }}</a>
                        @else{{ $it['label'] }}@endif
                    </div>
                @endforeach
            </div>
        @empty
            <div class="kop-card kop-empty"><div class="kop-empty-icon">🔍</div><h3>Tidak ditemukan</h3><p>Coba kata kunci lain.</p></div>
        @endforelse
    @endif
</x-filament-panels::page>
