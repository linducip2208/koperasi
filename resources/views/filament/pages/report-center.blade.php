<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Dashboard <span>/</span> <span>Report Center</span></div>
            <div class="kop-page-title">Report Center</div>
            <div class="kop-page-sub">{{ count($defs) }} report · semua dari data live aplikasi</div>
        </div>
    </div>

    <div class="kop-card kop-no-print" style="padding:1rem 1.25rem; margin-bottom:1rem;">
        <div class="kop-filters">
            <div class="kop-field">
                <label>Cari report</label>
                <input type="search" wire:model.live.debounce.300ms="q" placeholder="nama, deskripsi, key…">
            </div>
            <div class="kop-field">
                <label>Kategori</label>
                <select wire:model.live="cat">
                    <option value="">Semua kategori</option>
                    @foreach($categories as $k => $label)
                        <option value="{{ $k }}">{{ $label }} ({{ $counts[$k] ?? 0 }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if($recent->isNotEmpty())
        <h3 class="font-bold text-sm mb-2" style="color:var(--kop-text)">Terakhir dibuka</h3>
        <div class="kop-grid cols-4" style="margin-bottom:1.25rem;">
            @foreach($recent as $r)
                <a href="{{ route('filament.admin.pages.report-viewer', ['report' => $r->report_key]) }}" class="kop-card" style="padding:.8rem 1rem; text-decoration:none; display:block;">
                    <div class="font-bold text-sm" style="color:var(--kop-text)">🕘 {{ $r->name }}</div>
                    <div class="text-xs" style="color:var(--kop-muted)">{{ $r->last_run_at?->diffForHumans() }}</div>
                </a>
            @endforeach
        </div>
    @endif

    @forelse($categories as $ck => $clabel)
        @php $inCat = array_filter($defs, fn ($d) => $d->category() === $ck); @endphp
        @if(! empty($inCat))
            <h3 class="font-bold text-sm mb-2 mt-4" style="color:var(--kop-text)">{{ $clabel }}</h3>
            <div class="kop-grid cols-3" style="margin-bottom:.5rem;">
                @foreach($inCat as $d)
                    <div class="kop-card" style="padding:1rem 1.1rem;">
                        <div class="flex items-start justify-between gap-2">
                            <div class="font-bold text-sm" style="color:var(--kop-text)">{{ $d->name() }}</div>
                            <button wire:click="toggleFavorite('{{ $d->key() }}')" title="Favorit" class="text-lg leading-none">{{ in_array($d->key(), $favorites) ? '⭐' : '☆' }}</button>
                        </div>
                        <p class="text-xs mt-1" style="color:var(--kop-muted)">{{ $d->description() }}</p>
                        <div class="flex gap-2 mt-3">
                            <a class="kop-btn" style="font-size:.75rem; padding:.4rem .8rem; text-decoration:none;" href="{{ route('filament.admin.pages.report-viewer', ['report' => $d->key()]) }}">Buka →</a>
                            <span class="kop-badge neutral">{{ $d->key() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @empty
        <div class="kop-card kop-empty">
            <div class="kop-empty-icon">📊</div>
            <h3>Tidak ada report cocok</h3>
            <p>Ubah kata kunci atau kategori.</p>
        </div>
    @endforelse
</x-filament-panels::page>
