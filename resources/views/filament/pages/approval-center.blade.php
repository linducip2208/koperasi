<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Operations <span>/</span> <span>Approval Center</span></div>
            <div class="kop-page-title">Approval Center</div>
            <div class="kop-page-sub">Satu pintu persetujuan: pinjaman, pembayaran, pengadaan, calon anggota</div>
        </div>
        <div class="flex gap-2 kop-no-print">
            @foreach(['pinjaman' => 'Pinjaman', 'pembayaran' => 'Pembayaran', 'procurement' => 'Pengadaan', 'anggota' => 'Calon Anggota'] as $k => $l)
                <button wire:click="$set('tab', '{{ $k }}')" class="{{ $tab === $k ? 'kop-btn' : 'kop-btn secondary' }}" style="font-size:.75rem;">{{ $l }}</button>
            @endforeach
        </div>
    </div>
    <div class="kop-card" style="overflow:auto;">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
