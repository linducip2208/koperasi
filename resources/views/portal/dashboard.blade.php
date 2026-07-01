@extends('portal.layout')

@section('title', 'Dashboard Anggota')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang kembali, ' . explode(' ', $anggota->nama)[0] . '!')

@section('content')
<div class="space-y-6">

    @php
        $hour = (int) now()->format('H');
        $greet = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));
        $totalSimpanan = $simpanan->sum('saldo');
        $totalPinjaman = $pinjaman->sum('saldo_pokok');
        $kekayaan = $totalSimpanan - $totalPinjaman;
        $hasUlangTahun = $anggota->tanggal_lahir
            && \Carbon\Carbon::parse($anggota->tanggal_lahir)->month === now()->month
            && \Carbon\Carbon::parse($anggota->tanggal_lahir)->day === now()->day;
    @endphp

    {{-- ============= HERO BANNER ============= --}}
    <div class="card-gradient p-6 md:p-8 relative overflow-hidden">
        <div class="blob bg-emerald-300 w-72 h-72 -top-20 -right-20 animate-blob"></div>
        <div class="blob bg-cyan-300 w-64 h-64 -bottom-32 -left-20 animate-blob" style="animation-delay: 4s"></div>
        <div class="absolute inset-0 grid-pattern opacity-15"></div>

        <div class="relative grid md:grid-cols-3 gap-6 items-center">
            <div class="md:col-span-2">
                @if($hasUlangTahun)
                    <span class="inline-block bg-pink-400/30 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-3 animate-pulse">
                        🎂 SELAMAT ULANG TAHUN!
                    </span>
                @else
                    <span class="inline-block bg-white/20 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                        ✨ {{ $greet }}
                    </span>
                @endif
                <h2 class="font-extrabold text-3xl md:text-4xl tracking-tight mb-2">{{ $anggota->nama }}</h2>
                <div class="flex flex-wrap gap-3 text-sm text-emerald-50 mb-4">
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/></svg>{{ $anggota->nomor_anggota }}</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/></svg>Bergabung {{ $anggota->tanggal_masuk ? \Carbon\Carbon::parse($anggota->tanggal_masuk)->translatedFormat('M Y') : '-' }}</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>Status: <span class="font-bold uppercase">{{ $anggota->status }}</span></span>
                </div>

                <div class="grid grid-cols-3 gap-3 max-w-md">
                    <div>
                        <div class="text-[10px] uppercase tracking-wider text-emerald-100 font-bold">Total Simpanan</div>
                        <div class="font-extrabold text-lg leading-tight mt-0.5">Rp {{ number_format($totalSimpanan / 1_000_000, 1) }}<span class="text-xs">jt</span></div>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase tracking-wider text-emerald-100 font-bold">Sisa Pinjaman</div>
                        <div class="font-extrabold text-lg leading-tight mt-0.5">Rp {{ number_format($totalPinjaman / 1_000_000, 1) }}<span class="text-xs">jt</span></div>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase tracking-wider text-emerald-100 font-bold">Posisi Bersih</div>
                        <div class="font-extrabold text-lg leading-tight mt-0.5 {{ $kekayaan >= 0 ? '' : 'text-rose-200' }}">Rp {{ number_format(abs($kekayaan) / 1_000_000, 1) }}<span class="text-xs">jt</span></div>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex justify-center">
                <div class="relative w-48 h-48">
                    <div class="absolute inset-0 rounded-full bg-white/20 backdrop-blur-xl"></div>
                    <div class="absolute inset-3 rounded-full bg-gradient-to-br from-white/30 to-white/5 backdrop-blur-2xl"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-7xl">{{ $hasUlangTahun ? '🎂' : '🏆' }}</span>
                        <span class="text-xs font-bold text-white/90 uppercase tracking-wider mt-2">{{ $hasUlangTahun ? 'Hari Spesial' : 'Anggota ' . str_pad((string) $anggota->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============= QUICK ACTIONS ============= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('portal.setoran') }}" class="card p-4 group hover:border-emerald-400 transition">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            </div>
            <div class="font-bold text-sm text-slate-900">Setor Simpanan</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Tambah saldo tabungan</div>
        </a>
        <a href="{{ route('portal.pengajuan-pinjaman') }}" class="card p-4 group hover:border-amber-400 transition">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375"/></svg>
            </div>
            <div class="font-bold text-sm text-slate-900">Ajukan Pinjaman</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Pengajuan online cepat</div>
        </a>
        <a href="{{ route('portal.transaksi') }}" class="card p-4 group hover:border-blue-400 transition">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25"/></svg>
            </div>
            <div class="font-bold text-sm text-slate-900">Riwayat Transaksi</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Semua aktivitas Anda</div>
        </a>
        <a href="https://wa.me/6281296052010?text=Halo,%20saya%20butuh%20bantuan" target="_blank" class="card p-4 group hover:border-green-400 transition">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-green-500/30 group-hover:scale-110 transition mb-3">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
            </div>
            <div class="font-bold text-sm text-slate-900">Bantuan WA</div>
            <div class="text-[11px] text-slate-500 mt-0.5">0812-9605-2010</div>
        </a>
    </div>

    {{-- ============= MAIN CONTENT 2-COL ============= --}}
    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Left: Simpanan Detail --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="card p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-extrabold text-lg text-slate-900">💰 Simpanan Anda</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $simpanan->count() }} rekening aktif</p>
                    </div>
                    <a href="{{ route('portal.simpanan') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">Lihat Semua →</a>
                </div>

                @if($simpanan->count())
                    <div class="space-y-3">
                        @foreach($simpanan->take(3) as $s)
                            <div class="flex items-center gap-4 p-3 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 hover:from-emerald-100 hover:to-teal-100 transition">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-sm text-slate-900 truncate">{{ $s->produk?->nama ?? 'Simpanan' }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $s->nomor_rekening }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-extrabold text-emerald-700">Rp {{ number_format($s->saldo, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400 text-sm">Belum ada rekening simpanan</div>
                @endif
            </div>

            <div class="card p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-extrabold text-lg text-slate-900">💳 Pinjaman Aktif</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $pinjaman->count() }} pinjaman berjalan</p>
                    </div>
                    <a href="{{ route('portal.pinjaman') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">Lihat Semua →</a>
                </div>

                @if($pinjaman->count())
                    <div class="space-y-4">
                        @foreach($pinjaman->take(2) as $p)
                            @php
                                $progress = $p->plafon > 0 ? round((($p->plafon - $p->saldo_pokok) / $p->plafon) * 100, 1) : 0;
                            @endphp
                            <div class="p-4 rounded-xl bg-gradient-to-r from-amber-50 to-orange-50">
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="min-w-0">
                                        <div class="font-bold text-sm text-slate-900">{{ $p->produk?->nama ?? 'Pinjaman' }}</div>
                                        <div class="text-[11px] text-slate-500 font-mono">{{ $p->nomor_akad }}</div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <div class="text-[10px] text-slate-500 uppercase">Sisa Pokok</div>
                                        <div class="font-extrabold text-amber-700">Rp {{ number_format($p->saldo_pokok, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="relative h-2 bg-amber-200 rounded-full overflow-hidden">
                                    <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-amber-500 to-emerald-500 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="flex justify-between text-[11px] mt-1.5">
                                    <span class="text-slate-600">{{ $progress }}% terbayar</span>
                                    <span class="text-slate-500">Plafon: Rp {{ number_format($p->plafon / 1_000_000, 1) }}jt · {{ $p->tenor }}bln</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400 text-sm">Belum ada pinjaman aktif</div>
                @endif
            </div>
        </div>

        {{-- Right: Notifications & Tips --}}
        <div class="space-y-6">
            <div class="card p-5 bg-gradient-to-br from-blue-50 to-cyan-50 border-blue-200">
                <div class="flex items-start gap-3 mb-3">
                    <span class="text-2xl">💡</span>
                    <h3 class="font-extrabold text-base text-slate-900">Tips Hari Ini</h3>
                </div>
                <p class="text-sm text-slate-700 leading-relaxed">
                    Setor simpanan rutin tiap bulan untuk meningkatkan SHU akhir tahun. Semakin besar simpanan, semakin besar bagian SHU yang Anda terima!
                </p>
            </div>

            <div class="card p-5">
                <h3 class="font-extrabold text-base text-slate-900 mb-3">📊 Estimasi SHU</h3>
                <p class="text-xs text-slate-500 mb-4">Berdasarkan simpanan & transaksi Anda saat ini.</p>
                @php
                    $shuEstimasi = (int) round($totalSimpanan * 0.08); // estimasi 8% per tahun
                @endphp
                <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl p-4 text-white">
                    <div class="text-xs font-bold uppercase tracking-wider opacity-90">Proyeksi Tahun Ini</div>
                    <div class="font-extrabold text-2xl mt-1">Rp {{ number_format($shuEstimasi, 0, ',', '.') }}</div>
                    <div class="text-xs mt-1 opacity-80">≈ 8% jasa modal × simpanan Anda</div>
                </div>
            </div>

            <div class="card p-5 bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-xl shadow-emerald-500/30">
                <div class="flex items-start gap-3 mb-3">
                    <span class="text-2xl">🎯</span>
                    <h3 class="font-extrabold text-base">Hubungi Kami</h3>
                </div>
                <p class="text-sm leading-relaxed mb-4 text-emerald-50">
                    Ada pertanyaan? Kontak admin via WhatsApp untuk bantuan cepat.
                </p>
                <a href="https://wa.me/6281296052010?text=Halo,%20saya%20{{ urlencode($anggota->nama) }}%20butuh%20bantuan" target="_blank" class="inline-flex items-center gap-2 bg-white text-emerald-700 font-bold text-sm px-4 py-2 rounded-lg hover:scale-105 transition">
                    📱 Chat 0812-9605-2010
                </a>
            </div>
        </div>

    </div>

    {{-- Pengumuman --}}
    @php
        try {
            $pengumuman = \App\Models\Pengumuman::published()->latest('published_at')->limit(3)->get();
        } catch (\Throwable $e) {
            $pengumuman = collect();
        }
    @endphp
    @if($pengumuman->isNotEmpty())
        <div class="card p-5">
            <h3 class="font-extrabold text-sm text-slate-800 mb-3">📢 Pengumuman</h3>
            <div class="space-y-3">
                @foreach($pengumuman as $p)
                    <div class="p-3 rounded-xl {{ $p->kategori === 'urgent' ? 'bg-rose-50 border border-rose-200' : 'bg-stone-50 border border-stone-200' }}">
                        <div class="flex items-center gap-2 mb-1">
                            @if($p->kategori === 'urgent')<span class="text-xs bg-rose-500 text-white px-2 py-0.5 rounded-full font-bold">URGENT</span>@endif
                            <span class="text-xs text-stone-400">{{ \Carbon\Carbon::parse($p->published_at)->format('d M Y') }}</span>
                        </div>
                        <h4 class="font-bold text-sm text-stone-800">{{ $p->judul }}</h4>
                        <p class="text-xs text-stone-600 mt-1 line-clamp-2">{{ strip_tags($p->isi) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
