<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-stone-900">Laporan ODS Kemenkop</h1>
            <p class="text-stone-500 text-sm mt-1">Format Organisasi, Data, Sarana — Per {{ \Carbon\Carbon::parse($this->data['tanggal'] ?? now())->format('d F Y') }}</p>
        </div>
        <button wire:click="downloadPdf"
                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-lg shadow-emerald-500/20 transition">
            📥 Download PDF
        </button>
    </div>

    <form wire:submit="downloadPdf" class="mb-6">
        {{ $this->form }}
    </form>

    @php $d = $this->getOdsData(); @endphp

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-stone-200 p-5">
            <div class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-1">Anggota Aktif</div>
            <div class="text-3xl font-extrabold text-stone-900">{{ number_format($d['total_anggota']) }}</div>
            <div class="text-xs text-stone-400 mt-1">L: {{ number_format($d['total_anggota_l']) }} | P: {{ number_format($d['total_anggota_p']) }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-stone-200 p-5">
            <div class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-1">Total Simpanan</div>
            <div class="text-3xl font-extrabold text-emerald-700">Rp {{ number_format($d['total_simpanan'], 0, ',', '.') }}</div>
            <div class="text-xs text-stone-400 mt-1">{{ number_format($d['jumlah_rekening']) }} rekening</div>
        </div>
        <div class="bg-white rounded-2xl border border-stone-200 p-5">
            <div class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-1">Outstanding Pinjaman</div>
            <div class="text-3xl font-extrabold text-amber-600">Rp {{ number_format($d['total_pinjaman'], 0, ',', '.') }}</div>
            <div class="text-xs text-stone-400 mt-1">{{ number_format($d['jumlah_pinjaman']) }} pinjaman aktif</div>
        </div>
        <div class="bg-white rounded-2xl border border-stone-200 p-5">
            <div class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-1">NPL</div>
            <div class="text-3xl font-extrabold {{ $d['npl'] > 5 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $d['npl'] }}%</div>
            <div class="text-xs text-stone-400 mt-1">{{ $d['npl'] > 5 ? '⚠ Di atas batas 5%' : '✅ Dalam batas aman' }}</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
        <div class="p-5 border-b border-stone-200 bg-stone-50">
            <h3 class="font-bold text-stone-800">📊 Detail Simpanan</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-stone-200 bg-stone-50">
                    <th class="text-left p-4 font-bold text-stone-500 uppercase text-xs">Jenis Simpanan</th>
                    <th class="text-right p-4 font-bold text-stone-500 uppercase text-xs">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-stone-100"><td class="p-4 font-medium">Simpanan Pokok</td><td class="p-4 text-right">Rp {{ number_format($d['total_simpanan_pokok'], 0, ',', '.') }}</td></tr>
                <tr class="border-b border-stone-100"><td class="p-4 font-medium">Simpanan Wajib</td><td class="p-4 text-right">Rp {{ number_format($d['total_simpanan_wajib'], 0, ',', '.') }}</td></tr>
                <tr class="border-b border-stone-100"><td class="p-4 font-medium">Simpanan Sukarela</td><td class="p-4 text-right">Rp {{ number_format($d['total_simpanan_sukarela'], 0, ',', '.') }}</td></tr>
                <tr class="bg-emerald-50 font-extrabold"><td class="p-4">TOTAL</td><td class="p-4 text-right text-emerald-700">Rp {{ number_format($d['total_simpanan'], 0, ',', '.') }}</td></tr>
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden mt-6">
        <div class="p-5 border-b border-stone-200 bg-stone-50">
            <h3 class="font-bold text-stone-800">📊 Indikator Keuangan</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-stone-200 bg-stone-50">
                    <th class="text-left p-4 font-bold text-stone-500 uppercase text-xs">Indikator</th>
                    <th class="text-right p-4 font-bold text-stone-500 uppercase text-xs">Nilai</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-stone-100"><td class="p-4 font-medium">Total Aset</td><td class="p-4 text-right">Rp {{ number_format($d['total_asset'], 0, ',', '.') }}</td></tr>
                <tr class="border-b border-stone-100"><td class="p-4 font-medium">Volume Usaha</td><td class="p-4 text-right">Rp {{ number_format($d['total_volume_usaha'], 0, ',', '.') }}</td></tr>
                <tr class="border-b border-stone-100"><td class="p-4 font-medium">Jumlah Anggota</td><td class="p-4 text-right">{{ number_format($d['total_anggota']) }} orang</td></tr>
                <tr class="border-b border-stone-100"><td class="p-4 font-medium">NPL Ratio</td><td class="p-4 text-right font-bold {{ $d['npl'] > 5 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $d['npl'] }}%</td></tr>
            </tbody>
        </table>
    </div>
</div>
