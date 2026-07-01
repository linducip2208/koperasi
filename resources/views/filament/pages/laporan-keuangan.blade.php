<x-filament-panels::page>
    <form wire:submit.prevent>
        {{ $this->form }}
    </form>

    @php
        $base = [
            'dari'   => $this->data['dari']   ?? '',
            'sampai' => $this->data['sampai'] ?? now()->toDateString(),
        ];
        if ($this->data['cabang_id']          ?? null) $base['cabang_id']          = $this->data['cabang_id'];
        if ($this->data['produk_simpanan_id'] ?? null) $base['produk_simpanan_id'] = $this->data['produk_simpanan_id'];
        if ($this->data['produk_pinjaman_id'] ?? null) $base['produk_pinjaman_id'] = $this->data['produk_pinjaman_id'];

        $cabangNama  = ($this->data['cabang_id']          ?? null) ? (\App\Models\Cabang::find($this->data['cabang_id'])->nama ?? '-') : null;
        $simpananNm  = ($this->data['produk_simpanan_id'] ?? null) ? (\App\Models\ProdukSimpanan::find($this->data['produk_simpanan_id'])->nama ?? '-') : null;
        $pinjamanNm  = ($this->data['produk_pinjaman_id'] ?? null) ? (\App\Models\ProdukPinjaman::find($this->data['produk_pinjaman_id'])->nama ?? '-') : null;

        $reports = [
            ['name' => 'Neraca',          'icon' => '📊', 'desc' => 'Posisi Aset, Kewajiban, Ekuitas', 'route' => 'laporan.neraca',           'color' => 'emerald'],
            ['name' => 'Laba Rugi',       'icon' => '💰', 'desc' => 'Pendapatan, Beban, SHU',          'route' => 'laporan.laba-rugi',        'color' => 'blue'],
            ['name' => 'Arus Kas',        'icon' => '💵', 'desc' => 'Pergerakan Kas Bersih',           'route' => 'laporan.arus-kas',         'color' => 'amber'],
            ['name' => 'Ringkasan Produk','icon' => '📦', 'desc' => 'Saldo & Outstanding per Produk',  'route' => 'laporan.ringkasan-produk', 'color' => 'violet'],
        ];

        $colorMap = [
            'emerald' => 'bg-emerald-600 hover:bg-emerald-700',
            'blue'    => 'bg-blue-600 hover:bg-blue-700',
            'amber'   => 'bg-amber-600 hover:bg-amber-700',
            'violet'  => 'bg-violet-600 hover:bg-violet-700',
        ];
    @endphp

    @if($cabangNama || $simpananNm || $pinjamanNm)
        <div class="mt-4 px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800 flex flex-wrap items-center gap-3">
            🎯 <strong>Filter aktif:</strong>
            @if($cabangNama)<span>🏢 Cabang: <strong>{{ $cabangNama }}</strong></span>@endif
            @if($simpananNm)<span>💰 Simpanan: <strong>{{ $simpananNm }}</strong></span>@endif
            @if($pinjamanNm)<span>💳 Pinjaman: <strong>{{ $pinjamanNm }}</strong></span>@endif
        </div>
    @endif

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        @foreach($reports as $r)
            <div class="{{ $colorMap[$r['color']] }} text-white p-5 rounded-xl shadow transition">
                <div class="text-2xl mb-1">{{ $r['icon'] }}</div>
                <div class="font-bold text-base">{{ $r['name'] }}</div>
                <div class="text-xs opacity-80 mt-1 mb-3">{{ $r['desc'] }}</div>
                <div class="flex gap-2">
                    <a target="_blank" href="{{ route($r['route'], $base) }}"
                       class="bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-2 py-1.5 rounded transition flex-1 text-center">
                       👁 PDF
                    </a>
                    <a href="{{ route($r['route'], array_merge($base, ['download' => 1])) }}"
                       class="bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-2 py-1.5 rounded transition flex-1 text-center">
                       ⬇ PDF
                    </a>
                    @if($r['route'] !== 'laporan.ringkasan-produk')
                    <a href="{{ route('laporan.excel', array_merge(['laporan' => str_replace('laporan.', '', $r['route'])], $base)) }}"
                       class="bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-2 py-1.5 rounded transition flex-1 text-center">
                       📊 Excel
                    </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Ringkasan inline (preview, tidak di-cetak) --}}
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">📦 Preview Ringkasan per Produk Simpanan</h3>
        @php
            $simpananQ = \App\Models\Simpanan::query()
                ->select('produk_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as jml'), \Illuminate\Support\Facades\DB::raw('SUM(saldo) as total'))
                ->groupBy('produk_id')->with('produk');
            if ($this->data['cabang_id']          ?? null) $simpananQ->where('cabang_id', $this->data['cabang_id']);
            if ($this->data['produk_simpanan_id'] ?? null) $simpananQ->where('produk_id', $this->data['produk_simpanan_id']);
            $simpananStats = $simpananQ->get();
        @endphp
        <table class="w-full text-sm">
            <thead><tr class="text-xs text-gray-500 uppercase border-b"><th class="text-left py-2">Produk</th><th class="text-right">Jml Rekening</th><th class="text-right">Total Saldo</th></tr></thead>
            <tbody>
                @forelse($simpananStats as $s)
                    <tr class="border-b border-gray-100"><td class="py-2 font-semibold">{{ $s->produk->nama ?? '—' }}</td><td class="text-right">{{ number_format($s->jml) }}</td><td class="text-right text-emerald-700 font-bold">Rp {{ number_format($s->total, 0, ',', '.') }}</td></tr>
                @empty
                    <tr><td colspan="3" class="py-3 text-center text-gray-400">Tidak ada data simpanan</td></tr>
                @endforelse
            </tbody>
        </table>

        <h3 class="text-lg font-bold mb-4 mt-6 flex items-center gap-2">💳 Preview Ringkasan per Produk Pinjaman</h3>
        @php
            $pinjamanQ = \App\Models\Pinjaman::query()
                ->select('produk_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as jml'), \Illuminate\Support\Facades\DB::raw('SUM(saldo_pokok) as outstanding'))
                ->where('status', 'aktif')
                ->groupBy('produk_id')->with('produk');
            if ($this->data['cabang_id']          ?? null) $pinjamanQ->where('cabang_id', $this->data['cabang_id']);
            if ($this->data['produk_pinjaman_id'] ?? null) $pinjamanQ->where('produk_id', $this->data['produk_pinjaman_id']);
            $pinjamanStats = $pinjamanQ->get();
        @endphp
        <table class="w-full text-sm">
            <thead><tr class="text-xs text-gray-500 uppercase border-b"><th class="text-left py-2">Produk</th><th class="text-right">Jml Aktif</th><th class="text-right">Outstanding Pokok</th></tr></thead>
            <tbody>
                @forelse($pinjamanStats as $p)
                    <tr class="border-b border-gray-100"><td class="py-2 font-semibold">{{ $p->produk->nama ?? '—' }}</td><td class="text-right">{{ number_format($p->jml) }}</td><td class="text-right text-rose-700 font-bold">Rp {{ number_format($p->outstanding, 0, ',', '.') }}</td></tr>
                @empty
                    <tr><td colspan="3" class="py-3 text-center text-gray-400">Tidak ada data pinjaman</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
