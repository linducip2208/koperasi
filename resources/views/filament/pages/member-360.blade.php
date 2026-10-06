<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Report Center <span>/</span> <span>Anggota 360</span></div>
            <div class="kop-page-title">Anggota 360°</div>
            <div class="kop-page-sub">Profil, simpanan, pinjaman, SHU, dokumen, voting & kehadiran RAT</div>
        </div>
    </div>

    <form wire:submit.prevent="$refresh" class="kop-card kop-no-print" style="padding:1rem 1.25rem; margin-bottom:1rem;">
        {{ $this->form }}
        <button type="submit" class="kop-btn mt-3">Tampilkan</button>
    </form>

    @if(! $anggota)
        <div class="kop-card kop-empty">
            <div class="kop-empty-icon">👤</div>
            <h3>Pilih anggota</h3>
            <p>Profil 360° akan tampil di sini: simpanan, pinjaman, angsuran, tunggakan, SHU, voting & RAT.</p>
        </div>
    @else
        @php $a = $anggota['model']; @endphp
        <div class="kop-grid cols-4" style="margin-bottom:1rem;">
            <div class="kop-card kop-stat"><div><div class="kop-stat-label">Simpanan</div><div class="kop-stat-value" style="font-size:1.1rem;">Rp {{ number_format($anggota['simpanan_saldo'], 0, ',', '.') }}</div></div></div>
            <div class="kop-card kop-stat"><div><div class="kop-stat-label">Outstanding</div><div class="kop-stat-value" style="font-size:1.1rem;">Rp {{ number_format($anggota['pinjaman_outstanding'], 0, ',', '.') }}</div></div></div>
            <div class="kop-card kop-stat"><div><div class="kop-stat-label">Tunggakan</div><div class="kop-stat-value" style="font-size:1.1rem;">Rp {{ number_format($anggota['tunggakan'], 0, ',', '.') }}</div></div></div>
            <div class="kop-card kop-stat"><div><div class="kop-stat-label">SHU Diterima</div><div class="kop-stat-value" style="font-size:1.1rem;">Rp {{ number_format($anggota['shu'], 0, ',', '.') }}</div></div></div>
        </div>

        <div class="kop-grid cols-2">
            <div class="kop-card" style="padding:1rem 1.1rem;">
                <div class="font-bold text-sm mb-1">Profil</div>
                <table class="kop-table">
                    <tr><td>Nomor</td><td class="text-right font-bold">{{ $a->nomor_anggota }}</td></tr>
                    <tr><td>Nama</td><td class="text-right font-bold">{{ $a->nama }}</td></tr>
                    <tr><td>Status</td><td class="text-right"><span class="kop-badge {{ $a->status === 'aktif' ? 'success' : 'warning' }}">{{ $a->status }}</span></td></tr>
                    <tr><td>Masuk</td><td class="text-right">{{ $a->tanggal_masuk?->format('d M Y') }}</td></tr>
                    <tr><td>Telp / Email</td><td class="text-right">{{ $a->telp }} / {{ $a->email }}</td></tr>
                    <tr><td>Ahli waris</td><td class="text-right">{{ $a->ahliWaris->count() }} orang</td></tr>
                    <tr><td>Hadir RAT</td><td class="text-right">{{ $anggota['hadir_rat'] }}x · Voting {{ $anggota['voting'] }}x</td></tr>
                </table>
            </div>
            <div class="kop-card" style="padding:1rem 1.1rem;">
                <div class="font-bold text-sm mb-1">Rekening Simpanan</div>
                <table class="kop-table">
                    <thead><tr><th>Produk</th><th>No. Rek</th><th class="text-right">Saldo</th></tr></thead>
                    <tbody>
                        @forelse($a->simpanan as $s)
                            <tr><td>{{ $s->produk->nama ?? '-' }}</td><td>{{ $s->nomor_rekening }}</td><td class="num">{{ number_format($s->saldo, 0, ',', '.') }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center"><em>Tidak ada rekening.</em></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="kop-card" style="padding:1rem 1.1rem;">
                <div class="font-bold text-sm mb-1">Pinjaman</div>
                <table class="kop-table">
                    <thead><tr><th>Akad</th><th class="text-right">Outstanding</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($a->pinjaman as $p)
                            <tr><td>{{ $p->nomor_akad }}<br><span class="text-xs text-gray-500">{{ $p->produk->nama ?? '' }}</span></td><td class="num">{{ number_format($p->saldo_pokok, 0, ',', '.') }}</td><td><span class="kop-badge info">{{ $p->status }}</span></td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center"><em>Tidak ada pinjaman.</em></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="kop-card" style="padding:1rem 1.1rem;">
                <div class="font-bold text-sm mb-1">10 Transaksi Terakhir</div>
                <table class="kop-table">
                    <thead><tr><th>Tanggal</th><th>Jenis</th><th class="text-right">Jumlah</th></tr></thead>
                    <tbody>
                        @php $trx = $a->simpanan->flatMap->transaksi->sortByDesc('tanggal')->take(10); @endphp
                        @forelse($trx as $t)
                            <tr><td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d M y') }}</td><td>{{ $t->jenis }}</td><td class="num">{{ number_format($t->jumlah, 0, ',', '.') }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center"><em>Belum ada transaksi.</em></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-filament-panels::page>
