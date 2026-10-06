<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Accounting <span>/</span> <span>Tutup Tahun Buku</span></div>
            <div class="kop-page-title">Tutup Tahun Buku {{ $this->tahun }}</div>
            <div class="kop-page-sub">{{ $simpananAktif }} simpanan aktif · {{ $pinjamanAktif }} pinjaman aktif</div>
        </div>
        <div class="flex gap-2 kop-no-print">
            <button wire:click="runJob('app:backup')" class="kop-btn secondary" style="font-size:.75rem;">1. Backup Dulu</button>
            <button wire:click="runJob('koperasi:update-kolektabilitas')" class="kop-btn secondary" style="font-size:.75rem;">2. Kolektabilitas</button>
            <button wire:click="runJob('koperasi:penyusutan-aset')" class="kop-btn secondary" style="font-size:.75rem;">3. Penyusutan</button>
        </div>
    </div>

    <div class="kop-card" style="padding:1.25rem; margin-bottom:1rem;">
        <div class="font-bold text-sm mb-2">Checklist pra-tutup</div>
        <table class="kop-table">
            @foreach($checks as $c)
                <tr><td>{{ $c['label'] }}</td><td>{{ $c['detail'] }}</td><td class="text-right">{{ $c['ok'] ? '✅' : '⚠️' }}</td></tr>
            @endforeach
        </table>
    </div>

    <div class="kop-card" style="padding:1.25rem;">
        <div class="font-bold text-sm mb-2">Periode {{ $this->tahun }}</div>
        @if($periodes->isEmpty())
            <div class="kop-empty"><div class="kop-empty-icon">📅</div><h3>Belum ada periode {{ $this->tahun }}</h3><p>Buat 12 periode di Akuntansi → Periode Akuntansi.</p></div>
        @else
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($periodes as $p)
                    <span class="kop-badge {{ $p->status === 'closed' ? 'danger' : 'success' }}">{{ $p->bulan }}/{{ $p->tahun }}: {{ $p->status }}</span>
                @endforeach
            </div>
            @if($allClosed)
                <p class="kop-badge danger">Tahun buku TERKUNCI — jurnal periode ini ditolak service.</p>
            @else
                <button wire:click="lockYear" onclick="return confirm('Kunci seluruh periode {{ $this->tahun }}? Pastikan backup sudah dibuat.')" class="kop-btn">🔒 Kunci Tahun {{ $this->tahun }}</button>
            @endif
        @endif
    </div>
</x-filament-panels::page>
