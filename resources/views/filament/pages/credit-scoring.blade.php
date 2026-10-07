<x-filament-panels::page>
    <div class="kop-page-head">
        <div>
            <div class="kop-crumb">Operasional <span>/</span> <span>Credit Scoring</span></div>
            <div class="kop-page-title">Credit Scoring</div>
            <div class="kop-page-sub">8 faktor configurable — bobot via Settings grup kredit</div>
        </div>
    </div>

    <form wire:submit.prevent="hitung" class="kop-card" style="padding:1.25rem; margin-bottom:1rem;">
        {{ $this->form }}
        <button type="submit" class="kop-btn mt-3">Hitung Skor</button>
    </form>

    @if($hasil)
        <div class="kop-grid cols-3" style="margin-bottom:1rem;">
            <div class="kop-card kop-stat"><div>
                <div class="kop-stat-label">Skor — {{ $hasil['nama'] }} ({{ $hasil['nomor'] }})</div>
                <div class="kop-stat-value">{{ $hasil['skor'] }}/100</div>
                <div class="kop-stat-sub"><span class="kop-badge {{ $hasil['level'] === 'BAIK' ? 'success' : ($hasil['level'] === 'CUKUP' ? 'warning' : 'danger') }}">{{ $hasil['level'] }}</span></div>
            </div></div>
            <div class="kop-card kop-stat"><div>
                <div class="kop-stat-label">Rekomendasi Plafon</div>
                <div class="kop-stat-value" style="font-size:1.15rem;">Rp {{ number_format($hasil['rekomendasi_plafon'], 0, ',', '.') }}</div>
            </div></div>
            <div class="kop-card kop-stat"><div>
                <div class="kop-stat-label">Rekomendasi Tenor</div>
                <div class="kop-stat-value">maks {{ $hasil['rekomendasi_tenor'] }} bln</div>
            </div></div>
        </div>

        <div class="kop-card" style="padding:1.25rem;">
            <div class="font-bold text-sm mb-2">Rincian Faktor</div>
            <table class="kop-table">
                <thead><tr><th>Faktor</th><th class="text-right">Skor</th></tr></thead>
                <tbody>
                    @foreach($hasil['rincian'] as $k => $v)
                        <tr><td>{{ ucfirst(str_replace('_', ' ', $k)) }}</td><td class="num">{{ $v }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="kop-card kop-empty">
            <div class="kop-empty-icon">⭐</div>
            <h3>Pilih anggota & plafon</h3>
            <p>Hasil scoring tampil di sini.</p>
        </div>
    @endif
</x-filament-panels::page>
