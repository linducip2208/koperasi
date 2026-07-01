<x-filament-panels::page>

    {{-- ANIMATED MESH BACKGROUND --}}
    <style>
        :root {
            --koperasi-mesh: radial-gradient(at 12% 8%, #10b981 0%, transparent 45%),
                radial-gradient(at 88% 12%, #06b6d4 0%, transparent 45%),
                radial-gradient(at 18% 92%, #14b8a6 0%, transparent 45%),
                radial-gradient(at 92% 88%, #0891b2 0%, transparent 45%);
        }
        .kk-card { background: white; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 1px 3px rgba(15,23,42,0.04), 0 8px 24px -8px rgba(15,23,42,0.08); transition: transform .25s cubic-bezier(.16,1,.3,1), box-shadow .25s; }
        .dark .kk-card { background: rgb(15 23 42); border-color: rgba(255,255,255,0.06); }
        .kk-card:hover { transform: translateY(-2px); box-shadow: 0 12px 40px -12px rgba(15,23,42,0.18); }
        .kk-hero {
            position: relative; overflow: hidden;
            background: linear-gradient(135deg, #047857 0%, #0d9488 30%, #06b6d4 70%, #0891b2 100%);
            border-radius: 1.5rem; color: white;
            box-shadow: 0 25px 50px -12px rgba(5,150,105,0.4);
        }
        .kk-hero::before {
            content:''; position: absolute; top: -100px; right: -100px;
            width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            animation: kk-float 12s ease-in-out infinite;
        }
        .kk-hero::after {
            content:''; position: absolute; bottom: -120px; left: -80px;
            width: 320px; height: 320px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            animation: kk-float 10s ease-in-out infinite reverse;
        }
        @keyframes kk-float {
            0%,100% { transform: translate(0,0) scale(1); }
            50% { transform: translate(30px,-20px) scale(1.05); }
        }
        .kk-grid-pattern {
            background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .kk-stat-icon { width: 52px; height: 52px; border-radius: 14px; display:flex; align-items:center; justify-content:center; font-size: 26px; }
        .kk-stat-icon-sm { width: 40px; height: 40px; border-radius: 11px; display:flex; align-items:center; justify-content:center; font-size: 19px; }
        .kk-bg-emerald { background: linear-gradient(135deg,#10b981,#06b6d4); box-shadow: 0 8px 20px -4px rgba(16,185,129,.4); }
        .kk-bg-amber   { background: linear-gradient(135deg,#f59e0b,#f97316); box-shadow: 0 8px 20px -4px rgba(245,158,11,.4); }
        .kk-bg-blue    { background: linear-gradient(135deg,#3b82f6,#06b6d4); box-shadow: 0 8px 20px -4px rgba(59,130,246,.4); }
        .kk-bg-rose    { background: linear-gradient(135deg,#f43f5e,#ec4899); box-shadow: 0 8px 20px -4px rgba(244,63,94,.4); }
        .kk-bg-violet  { background: linear-gradient(135deg,#8b5cf6,#a855f7); box-shadow: 0 8px 20px -4px rgba(139,92,246,.4); }
        .kk-bg-pink    { background: linear-gradient(135deg,#ec4899,#d946ef); box-shadow: 0 8px 20px -4px rgba(236,72,153,.4); }
        .kk-bg-cyan    { background: linear-gradient(135deg,#06b6d4,#0891b2); box-shadow: 0 8px 20px -4px rgba(6,182,212,.4); }
        .kk-bg-slate   { background: linear-gradient(135deg,#475569,#1e293b); box-shadow: 0 8px 20px -4px rgba(71,85,105,.4); }
        @keyframes kk-fadeup { from { opacity:0; transform: translateY(12px); } to { opacity:1; transform: translateY(0); } }
        .kk-anim { animation: kk-fadeup .5s cubic-bezier(.16,1,.3,1) both; }
        .kk-spark { display:flex; align-items:flex-end; gap:2px; height:36px; }
        .kk-spark span { flex:1; background: currentColor; opacity:.6; border-radius:2px; min-height: 4px; }
        .kk-pulse-dot { animation: kk-pulse 2s ease-in-out infinite; }
        @keyframes kk-pulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.7); }
            50%     { box-shadow: 0 0 0 8px rgba(16,185,129,0); }
        }
        .kk-progress-bar { height: 8px; background: rgba(15,23,42,0.05); border-radius: 4px; overflow: hidden; }
        .kk-progress-bar > div { height: 100%; border-radius: 4px; transition: width 1s cubic-bezier(.16,1,.3,1); }
        .kk-divider-mesh { background: linear-gradient(to right, transparent, rgba(15,23,42,0.1), transparent); height: 1px; }
    </style>

    <div class="space-y-6">

        {{-- ============== HERO ============== --}}
        <div class="kk-hero p-7 md:p-9 kk-anim">
            <div class="absolute inset-0 kk-grid-pattern opacity-50"></div>
            <div class="relative grid md:grid-cols-2 gap-6 items-center">
                <div>
                    <span class="inline-block bg-white/20 backdrop-blur-sm text-white text-[10px] font-extrabold uppercase tracking-[0.2em] px-3 py-1 rounded-full mb-3">
                        ⚡ {{ $greet }}
                    </span>
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-1.5">{{ $user->name }}</h1>
                    <p class="text-white/90 text-base font-medium mb-5">{{ now()->translatedFormat('l, d F Y') }} · Dashboard Koperasi App</p>

                    <div class="flex flex-wrap gap-2 text-xs">
                        <a href="/admin/anggotas" class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 backdrop-blur-sm transition px-3 py-2 rounded-lg font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 kk-pulse-dot"></span>
                            {{ number_format($totalAnggota) }} Anggota Aktif
                        </a>
                        <a href="/admin/pinjamen" class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 backdrop-blur-sm transition px-3 py-2 rounded-lg font-semibold">
                            ⚠ {{ $pengajuanCount }} Persetujuan
                        </a>
                        <span class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm px-3 py-2 rounded-lg font-semibold">
                            💰 NPL: {{ $npl }}%
                        </span>
                    </div>
                </div>

                <div class="hidden md:block text-right">
                    <div class="text-white/70 text-xs uppercase tracking-wider font-bold mb-1">Total Aset Koperasi</div>
                    <div class="text-5xl font-extrabold tracking-tight">
                        Rp {{ number_format($totalAset / 1_000_000_000, 2) }}<span class="text-2xl">M</span>
                    </div>
                    <div class="text-white/80 text-xs mt-1">
                        Simpanan: Rp {{ number_format($totalSimpanan / 1_000_000, 0) }}jt · Pinjaman: Rp {{ number_format($totalPinjaman / 1_000_000, 0) }}jt
                    </div>
                </div>
            </div>
        </div>

        {{-- ============== KPI CARDS ============== --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="kk-card p-5 kk-anim" style="animation-delay: .1s">
                <div class="flex items-start justify-between mb-3">
                    <div class="kk-stat-icon kk-bg-emerald text-white">👥</div>
                    @if($simpananGrowth !== 0)
                        <span class="text-[10px] font-extrabold {{ $simpananGrowth > 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-2 py-1 rounded-md">
                            {{ $simpananGrowth > 0 ? '↗' : '↘' }} {{ abs($simpananGrowth) }}%
                        </span>
                    @endif
                </div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Anggota Aktif</div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-0.5">{{ number_format($totalAnggota) }}</div>
                <div class="kk-spark text-emerald-500 mt-3">
                    @foreach([3,5,4,7,8,7,9,11,10,12,11,13] as $h)<span style="height:{{ $h * 7 }}%"></span>@endforeach
                </div>
            </div>

            <div class="kk-card p-5 kk-anim" style="animation-delay: .15s">
                <div class="flex items-start justify-between mb-3">
                    <div class="kk-stat-icon kk-bg-cyan text-white">💰</div>
                    <span class="text-[10px] font-extrabold text-cyan-700 bg-cyan-50 px-2 py-1 rounded-md">+{{ number_format($simpananBulanIni ?? $totalSimpanan / 12 / 1_000_000, 0) }}jt /bln</span>
                </div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Simpanan</div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-0.5">
                    Rp {{ number_format($totalSimpanan / 1_000_000_000, 2) }}<span class="text-base text-slate-500">M</span>
                </div>
                <div class="kk-spark text-cyan-500 mt-3">
                    @foreach([5,6,7,6,8,9,10,9,11,12,11,13] as $h)<span style="height:{{ $h * 7 }}%"></span>@endforeach
                </div>
            </div>

            <div class="kk-card p-5 kk-anim" style="animation-delay: .2s">
                <div class="flex items-start justify-between mb-3">
                    <div class="kk-stat-icon kk-bg-amber text-white">💳</div>
                    <span class="text-[10px] font-extrabold text-amber-700 bg-amber-50 px-2 py-1 rounded-md">NPL {{ $npl }}%</span>
                </div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Pinjaman</div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-0.5">
                    Rp {{ number_format($totalPinjaman / 1_000_000_000, 2) }}<span class="text-base text-slate-500">M</span>
                </div>
                <div class="kk-spark text-amber-500 mt-3">
                    @foreach([4,6,5,7,8,7,9,8,10,9,11,12] as $h)<span style="height:{{ $h * 7 }}%"></span>@endforeach
                </div>
            </div>

            <div class="kk-card p-5 kk-anim" style="animation-delay: .25s">
                <div class="flex items-start justify-between mb-3">
                    <div class="kk-stat-icon kk-bg-violet text-white">⏰</div>
                    @if($pengajuanCount > 0)<span class="text-[10px] font-extrabold text-rose-700 bg-rose-50 px-2 py-1 rounded-md kk-pulse-dot rounded-full">! NEW</span>@endif
                </div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Cicilan 7 Hari</div>
                <div class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-0.5">
                    Rp {{ number_format($cicilanMingguIni / 1_000_000, 1) }}<span class="text-base text-slate-500">jt</span>
                </div>
                <div class="text-xs text-slate-500 mt-3">{{ $pengajuanCount }} pengajuan baru · Rp {{ number_format($pengajuanNilai / 1_000_000, 0) }}jt</div>
            </div>

        </div>

        {{-- ============== ROW 2: CHART + KOL ============== --}}
        <div class="grid lg:grid-cols-3 gap-4">

            {{-- TREND CHART --}}
            <div class="kk-card p-6 lg:col-span-2 kk-anim" style="animation-delay: .3s">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">📈 Tren Setoran Simpanan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">12 bulan terakhir · Rp Juta</p>
                    </div>
                    <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md uppercase tracking-wider">Live</span>
                </div>
                <div class="relative h-48">
                    @php $maxVal = max(collect($trendData)->pluck('value')->max() ?: 1, 1); @endphp
                    <div class="absolute inset-0 flex items-end gap-2">
                        @foreach($trendData as $i => $d)
                            @php $h = max(($d['value'] / $maxVal) * 100, 2); @endphp
                            <div class="flex-1 flex flex-col items-center gap-1.5 group">
                                <div class="w-full bg-gradient-to-t from-emerald-600 to-cyan-400 rounded-t-md hover:from-emerald-700 hover:to-cyan-500 transition relative" style="height: {{ $h }}%; min-height:6px;">
                                    <div class="absolute -top-7 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">
                                        {{ number_format($d['value'] / 1_000_000, 1) }}jt
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500">{{ $d['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- KOLEKTABILITAS --}}
            <div class="kk-card p-6 kk-anim" style="animation-delay: .35s">
                <h3 class="font-extrabold text-lg text-slate-900 dark:text-white mb-5">🎯 Kolektabilitas</h3>
                <div class="space-y-3">
                    @php
                        $kolColors = [
                            'lancar'        => ['bg-emerald-500', 'Lancar'],
                            'dpk'           => ['bg-amber-400', 'DPK'],
                            'kurang_lancar' => ['bg-orange-500', 'Kurang Lancar'],
                            'diragukan'     => ['bg-rose-500', 'Diragukan'],
                            'macet'         => ['bg-red-700', 'Macet'],
                        ];
                    @endphp
                    @foreach($kolMap as $k => $v)
                        @php $pct = round($v / $totalKol * 100, 1); @endphp
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1.5">
                                <span class="text-slate-700 dark:text-slate-300">{{ $kolColors[$k][1] }}</span>
                                <span class="text-slate-900 dark:text-white">{{ $v }} ({{ $pct }}%)</span>
                            </div>
                            <div class="kk-progress-bar">
                                <div class="{{ $kolColors[$k][0] }}" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ============== ROW 3: PENGAJUAN + BIRTHDAY + AKTIVITAS ============== --}}
        <div class="grid lg:grid-cols-3 gap-4">

            {{-- PENGAJUAN PENDING --}}
            <div class="kk-card p-6 kk-anim" style="animation-delay: .4s">
                <div class="flex items-center gap-3 mb-4">
                    <div class="kk-stat-icon-sm kk-bg-amber text-white">⏳</div>
                    <div class="flex-1">
                        <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Persetujuan Pinjaman</h3>
                        <p class="text-[11px] text-slate-500">{{ $pengajuanCount }} pengajuan tertunda</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($pengajuan as $p)
                        <a href="/admin/pinjamen/{{ $p->id }}/edit" class="block p-3 rounded-lg bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/20 dark:hover:bg-amber-950/40 transition group">
                            <div class="flex items-center justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-sm text-slate-900 dark:text-white truncate">{{ $p->anggota?->nama ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">{{ $p->nomor_akad }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-extrabold text-sm text-amber-700">Rp {{ number_format($p->plafon / 1_000_000, 1) }}jt</div>
                                    <div class="text-[10px] text-slate-500">{{ $p->tenor }}bln</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-sm">
                            ✓ Tidak ada pengajuan tertunda
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- BIRTHDAY --}}
            <div class="kk-card p-6 kk-anim" style="animation-delay: .45s">
                <div class="flex items-center gap-3 mb-4">
                    <div class="kk-stat-icon-sm kk-bg-pink text-white">🎂</div>
                    <div class="flex-1">
                        <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Ulang Tahun</h3>
                        <p class="text-[11px] text-slate-500">{{ now()->translatedFormat('F') }}</p>
                    </div>
                    @if($bdayHariIni > 0)<span class="text-[10px] font-extrabold text-pink-700 bg-pink-100 px-2 py-1 rounded-md kk-pulse-dot">{{ $bdayHariIni }} HARI INI</span>@endif
                </div>
                <div class="space-y-2">
                    @forelse($bdayBulan as $a)
                        @php
                            $isToday = \Carbon\Carbon::parse($a->tanggal_lahir)->day === now()->day && \Carbon\Carbon::parse($a->tanggal_lahir)->month === now()->month;
                        @endphp
                        <div class="flex items-center gap-3 p-2.5 rounded-lg {{ $isToday ? 'bg-gradient-to-r from-pink-50 to-fuchsia-50 ring-2 ring-pink-300' : 'bg-slate-50 dark:bg-slate-800/30' }}">
                            <div class="w-8 h-8 rounded-full kk-bg-pink text-white font-extrabold flex items-center justify-center text-xs flex-shrink-0">{{ substr($a->nama, 0, 1) }}</div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-xs text-slate-900 dark:text-white truncate">{{ $a->nama }} {{ $isToday ? '🎉' : '' }}</div>
                                <div class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($a->tanggal_lahir)->translatedFormat('d M') }}</div>
                            </div>
                            @if($isToday && $a->telp)
                                <a href="https://wa.me/62{{ ltrim($a->telp, '0') }}?text=Selamat+Ulang+Tahun" target="_blank" class="text-[10px] font-extrabold text-emerald-700 bg-emerald-100 px-2 py-1 rounded">🎁</a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-sm">Tidak ada bulan ini</div>
                    @endforelse
                </div>
            </div>

            {{-- AKTIVITAS TERBARU --}}
            <div class="kk-card p-6 kk-anim" style="animation-delay: .5s">
                <div class="flex items-center gap-3 mb-4">
                    <div class="kk-stat-icon-sm kk-bg-blue text-white">⚡</div>
                    <div class="flex-1">
                        <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Aktivitas Terbaru</h3>
                        <p class="text-[11px] text-slate-500">Real-time transaksi</p>
                    </div>
                </div>
                <div class="space-y-2.5">
                    @forelse($aktivitas as $t)
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $t['jenis'] === 'setor' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs text-slate-700 dark:text-slate-300 truncate">
                                    <span class="font-bold">{{ $t['nama'] }}</span>
                                    <span class="text-slate-500">{{ $t['jenis'] }}</span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="font-extrabold text-xs {{ $t['jenis'] === 'setor' ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $t['jenis'] === 'setor' ? '+' : '-' }}{{ number_format($t['jumlah'] / 1000) }}k
                                </div>
                                <div class="text-[10px] text-slate-400">{{ $t['tanggal'] }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-sm">Belum ada aktivitas</div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ============== ROW 4: TOP PEMINJAM + QUICK LINKS ============== --}}
        <div class="grid lg:grid-cols-3 gap-4">

            <div class="kk-card p-6 lg:col-span-2 kk-anim" style="animation-delay: .55s">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="kk-stat-icon-sm kk-bg-rose text-white">🏆</div>
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Top Peminjam</h3>
                            <p class="text-[11px] text-slate-500">5 saldo pokok terbesar</p>
                        </div>
                    </div>
                    <a href="/admin/pinjamen" class="text-xs font-extrabold text-emerald-700 hover:text-emerald-800">Lihat semua →</a>
                </div>
                <div class="space-y-2">
                    @forelse($topPeminjam as $i => $p)
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/30">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-extrabold text-xs flex-shrink-0
                                {{ $i === 0 ? 'kk-bg-amber text-white' : ($i === 1 ? 'bg-slate-300 text-slate-700' : ($i === 2 ? 'bg-orange-200 text-orange-900' : 'bg-slate-100 text-slate-500')) }}">
                                #{{ $i + 1 }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-sm text-slate-900 dark:text-white truncate">{{ $p->anggota?->nama ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $p->produk?->nama ?? 'Pinjaman' }} · {{ $p->tenor }}bln</div>
                            </div>
                            <div class="text-right">
                                <div class="font-extrabold text-sm text-rose-700">Rp {{ number_format($p->saldo_pokok / 1_000_000, 1) }}jt</div>
                                <div class="text-[10px] text-slate-500">sisa</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-sm">Belum ada data pinjaman</div>
                    @endforelse
                </div>
            </div>

            <div class="kk-card p-6 kk-anim" style="animation-delay: .6s">
                <div class="flex items-center gap-3 mb-4">
                    <div class="kk-stat-icon-sm kk-bg-violet text-white">🚀</div>
                    <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Quick Actions</h3>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <a href="/admin/anggotas/create" class="flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 hover:from-emerald-100 hover:to-teal-100 dark:from-emerald-950/30 dark:to-teal-950/30 transition group">
                        <span class="text-2xl mb-1.5 group-hover:scale-110 transition">👥</span>
                        <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Tambah Anggota</span>
                    </a>
                    <a href="/admin/pinjamen/create" class="flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 hover:from-amber-100 hover:to-orange-100 dark:from-amber-950/30 dark:to-orange-950/30 transition group">
                        <span class="text-2xl mb-1.5 group-hover:scale-110 transition">💳</span>
                        <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Pinjaman Baru</span>
                    </a>
                    <a href="/admin/simpanans/create" class="flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-cyan-50 to-blue-50 hover:from-cyan-100 hover:to-blue-100 dark:from-cyan-950/30 dark:to-blue-950/30 transition group">
                        <span class="text-2xl mb-1.5 group-hover:scale-110 transition">💰</span>
                        <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Buka Simpanan</span>
                    </a>
                    <a href="/admin/laporan-keuangan" class="flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-violet-50 to-purple-50 hover:from-violet-100 hover:to-purple-100 dark:from-violet-950/30 dark:to-purple-950/30 transition group">
                        <span class="text-2xl mb-1.5 group-hover:scale-110 transition">📊</span>
                        <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Laporan</span>
                    </a>
                    <a href="/admin/jurnals/create" class="flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-rose-50 to-pink-50 hover:from-rose-100 hover:to-pink-100 transition group">
                        <span class="text-2xl mb-1.5 group-hover:scale-110 transition">🧾</span>
                        <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">Buat Jurnal</span>
                    </a>
                    <a href="/admin/users/create" class="flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 hover:from-slate-100 hover:to-slate-200 dark:from-slate-800 dark:to-slate-700 transition group">
                        <span class="text-2xl mb-1.5 group-hover:scale-110 transition">⚙️</span>
                        <span class="text-[11px] font-bold text-slate-700 text-center leading-tight">User Admin</span>
                    </a>
                </div>
            </div>

            {{-- System Health Indicator --}}
            <div class="kk-card p-6 kk-fadeup" style="animation-delay:.55s">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">🩺</span>
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">System Health</h3>
                    <span class="text-xs text-slate-400 ml-auto">Realtime · {{ now()->format('H:i') }}</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                    @foreach($health as $h)
                        <div class="rounded-xl p-3 border {{ $h['ok'] ? 'bg-emerald-50 border-emerald-200 dark:bg-emerald-950/20 dark:border-emerald-800/40' : 'bg-amber-50 border-amber-200 dark:bg-amber-950/20 dark:border-amber-800/40' }}">
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <span class="text-lg">{{ $h['icon'] }}</span>
                                <span class="text-[11px] font-bold uppercase tracking-wider {{ $h['ok'] ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }}">{{ $h['label'] }}</span>
                                <span class="ml-auto inline-block w-2 h-2 rounded-full {{ $h['ok'] ? 'bg-emerald-500' : 'bg-amber-500' }} animate-pulse"></span>
                            </div>
                            <div class="text-[11px] text-slate-600 dark:text-slate-400 leading-snug">{{ $h['detail'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</x-filament-panels::page>
