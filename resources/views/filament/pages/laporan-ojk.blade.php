<div class="p-6">
    <h1 class="text-2xl font-bold text-stone-900 mb-2">Laporan OJK</h1>
    <p class="text-stone-500 text-sm mb-6">Format Laporan Bulanan Koperasi — Otoritas Jasa Keuangan</p>

    <form wire:submit="loadData" class="mb-6">
        {{ $this->form }}
        <button type="submit" class="mt-4 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition">🔍 Tampilkan</button>
    </form>

    @if($loaded)
        <button wire:click="downloadPdf" class="mb-6 px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-semibold transition">📥 Download PDF</button>

        @php $d = $reportData; @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-stone-200 p-5">
                <div class="text-xs font-bold text-stone-400 uppercase">Anggota</div>
                <div class="text-3xl font-extrabold text-stone-900">{{ number_format($d['total_anggota']) }}</div>
            </div>
            <div class="bg-white rounded-2xl border border-stone-200 p-5">
                <div class="text-xs font-bold text-stone-400 uppercase">Simpanan</div>
                <div class="text-3xl font-extrabold text-emerald-700">Rp {{ number_format($d['total_simpanan'], 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-2xl border border-stone-200 p-5">
                <div class="text-xs font-bold text-stone-400 uppercase">Outstanding Pinjaman</div>
                <div class="text-3xl font-extrabold text-amber-600">Rp {{ number_format($d['total_pinjaman'], 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-2xl border border-stone-200 p-5">
                <div class="text-xs font-bold text-stone-400 uppercase">NPL</div>
                <div class="text-3xl font-extrabold {{ $d['npl'] > 5 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $d['npl'] }}%</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden mb-6">
            <div class="p-5 border-b bg-stone-50"><h3 class="font-bold text-stone-800">Kualitas Pinjaman</h3></div>
            <table class="w-full text-sm"><thead><tr class="border-b bg-stone-50">
                <th class="text-left p-4 text-xs font-bold text-stone-500 uppercase">Kolektabilitas</th><th class="text-right p-4 text-xs font-bold text-stone-500 uppercase">Jumlah</th>
            </tr></thead>
            <tbody>
                <tr class="border-b"><td class="p-4">Lancar</td><td class="p-4 text-right text-emerald-700 font-bold">{{ number_format($d['jumlah_lancar']) }}</td></tr>
                <tr class="border-b"><td class="p-4">Macet</td><td class="p-4 text-right text-rose-700 font-bold">{{ number_format($d['jumlah_macet']) }}</td></tr>
                <tr class="bg-stone-50 font-bold"><td class="p-4">TOTAL</td><td class="p-4 text-right">{{ number_format($d['jumlah_pinjaman']) }}</td></tr>
            </tbody></table>
        </div>

        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="p-5 border-b bg-stone-50"><h3 class="font-bold text-stone-800">Indikator Keuangan</h3></div>
            <table class="w-full text-sm"><thead><tr class="border-b bg-stone-50">
                <th class="text-left p-4 text-xs font-bold text-stone-500 uppercase">Indikator</th><th class="text-right p-4 text-xs font-bold text-stone-500 uppercase">Nilai</th>
            </tr></thead>
            <tbody>
                <tr class="border-b"><td class="p-4">Total Aset</td><td class="p-4 text-right">Rp {{ number_format($d['total_aset'], 0, ',', '.') }}</td></tr>
                <tr class="border-b"><td class="p-4">Ekuitas</td><td class="p-4 text-right">Rp {{ number_format($d['ekuitas'], 0, ',', '.') }}</td></tr>
                <tr><td class="p-4">NPL Ratio</td><td class="p-4 text-right font-bold {{ $d['npl'] > 5 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $d['npl'] }}%</td></tr>
            </tbody></table>
        </div>
    @endif
</div>
