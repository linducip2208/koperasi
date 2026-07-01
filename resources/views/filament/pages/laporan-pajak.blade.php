<div class="p-6">
    <h1 class="text-2xl font-bold text-stone-900 mb-2">Laporan Pajak</h1>
    <p class="text-stone-500 text-sm mb-6">PPh 21 Karyawan & PPh 4(2) Bunga Simpanan</p>

    <form wire:submit="loadData" class="mb-6">
        {{ $this->form }}
        <button type="submit" class="mt-4 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition">
            🔍 Tampilkan Laporan
        </button>
    </form>

    @if($loaded)
        <button wire:click="downloadPdf" class="mb-6 px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-semibold transition">
            📥 Download PDF
        </button>

        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden mb-6">
            <div class="p-5 border-b bg-stone-50"><h3 class="font-bold text-stone-800">📋 PPh 21 — Karyawan</h3></div>
            <table class="w-full text-sm"><thead><tr class="border-b bg-stone-50">
                <th class="text-left p-4 font-bold text-stone-500 uppercase text-xs">NIP</th>
                <th class="text-left p-4 font-bold text-stone-500 uppercase text-xs">Nama</th>
                <th class="text-right p-4 font-bold text-stone-500 uppercase text-xs">Gaji Bruto</th>
                <th class="text-right p-4 font-bold text-stone-500 uppercase text-xs">PPh 21</th>
                <th class="text-right p-4 font-bold text-stone-500 uppercase text-xs">Gaji Neto</th>
            </tr></thead>
            <tbody>
                @foreach($pph21 as $r)
                    <tr class="border-b border-stone-100">
                        <td class="p-4 font-mono text-xs">{{ $r['nip'] }}</td>
                        <td class="p-4">{{ $r['nama'] }}</td>
                        <td class="p-4 text-right">Rp {{ number_format($r['gaji_bruto'], 0, ',', '.') }}</td>
                        <td class="p-4 text-right text-rose-600">Rp {{ number_format($r['pph21'], 0, ',', '.') }}</td>
                        <td class="p-4 text-right font-semibold">Rp {{ number_format($r['gaji_neto'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(empty($pph21)) <div class="p-6 text-center text-stone-400">Tidak ada data karyawan</div> @endif
        </div>

        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="p-5 border-b bg-stone-50"><h3 class="font-bold text-stone-800">🏦 PPh 4(2) — Bunga Simpanan</h3></div>
            <table class="w-full text-sm"><thead><tr class="border-b bg-stone-50">
                <th class="text-left p-4 font-bold text-stone-500 uppercase text-xs">Nama</th>
                <th class="text-right p-4 font-bold text-stone-500 uppercase text-xs">Bunga</th>
                <th class="text-right p-4 font-bold text-stone-500 uppercase text-xs">PPh 10%</th>
            </tr></thead>
            <tbody>
                @foreach($pphBunga['detail'] as $r)
                    <tr class="border-b border-stone-100">
                        <td class="p-4">{{ $r['nama'] }}</td>
                        <td class="p-4 text-right">Rp {{ number_format($r['bunga'], 0, ',', '.') }}</td>
                        <td class="p-4 text-right text-rose-600">Rp {{ number_format($r['pph'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="bg-stone-50 font-bold">
                    <td class="p-4">TOTAL</td>
                    <td class="p-4 text-right">Rp {{ number_format($pphBunga['total_bunga'], 0, ',', '.') }}</td>
                    <td class="p-4 text-right text-rose-600">Rp {{ number_format($pphBunga['total_pph'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        @if(empty($pphBunga['detail'])) <div class="p-6 text-center text-stone-400">Tidak ada transaksi bunga bulan ini</div> @endif
        </div>
    @endif
</div>
