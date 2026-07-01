@php
    $totalFmt = $totalSimpanan >= 1_000_000_000
        ? 'Rp ' . number_format($totalSimpanan / 1_000_000_000, 2, ',', '.') . ' M'
        : ($totalSimpanan >= 1_000_000 ? 'Rp ' . number_format($totalSimpanan / 1_000_000, 1, ',', '.') . ' jt' : 'Rp ' . number_format($totalSimpanan));
@endphp
<x-filament-widgets::widget>
    <div class="koperasi-hero-banner relative overflow-hidden rounded-2xl text-white p-6 md:p-8"
         style="background:
            radial-gradient(at 12% 8%, #10b981 0%, transparent 45%),
            radial-gradient(at 88% 12%, #06b6d4 0%, transparent 45%),
            radial-gradient(at 18% 92%, #14b8a6 0%, transparent 45%),
            radial-gradient(at 92% 88%, #0891b2 0%, transparent 45%),
            linear-gradient(135deg, #047857 0%, #0d9488 50%, #0891b2 100%);
            box-shadow: 0 16px 40px -16px rgba(5,150,105,0.45);">

        {{-- Grid pattern --}}
        <div class="absolute inset-0 opacity-25 pointer-events-none"
             style="background-image:
                linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px);
                background-size: 32px 32px;"></div>

        {{-- Animated blobs --}}
        <div class="absolute -top-20 -right-12 w-64 h-64 rounded-full opacity-50 pointer-events-none"
             style="background: #10b981; filter: blur(60px); mix-blend-mode: screen; animation: hero-blob 14s ease-in-out infinite;"></div>
        <div class="absolute -bottom-32 left-1/3 w-56 h-56 rounded-full opacity-50 pointer-events-none"
             style="background: #06b6d4; filter: blur(60px); mix-blend-mode: screen; animation: hero-blob 14s ease-in-out infinite -5s;"></div>

        <div class="relative grid md:grid-cols-2 gap-6 items-center">
            <div>
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur border border-white/20 px-3 py-1 rounded-full text-[11px] font-bold mb-4 uppercase tracking-wider">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-300 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-300"></span>
                    </span>
                    {{ $date }}
                </div>
                <p class="text-sm opacity-85 mb-1 font-medium">{{ $greeting }},</p>
                <h2 class="text-3xl md:text-[34px] font-extrabold tracking-tight leading-[1.1] mb-2">
                    {{ $name }}
                </h2>
                <p class="text-sm opacity-90 max-w-md leading-relaxed">
                    Berikut ringkasan operasional koperasi Anda hari ini. Kelola anggota, simpanan, dan pinjaman dari satu dashboard.
                </p>
                <div class="flex flex-wrap gap-2 mt-5">
                    <a href="{{ route('filament.admin.resources.anggotas.index') ?? '#' }}"
                       class="inline-flex items-center gap-2 bg-white text-emerald-700 px-4 py-2 rounded-xl text-[13px] font-extrabold hover:shadow-2xl hover:shadow-black/20 hover:-translate-y-0.5 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
                        Tambah Anggota
                    </a>
                    <a href="{{ route('filament.admin.pages.laporan-keuangan') ?? '#' }}"
                       class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/30 text-white px-4 py-2 rounded-xl text-[13px] font-extrabold hover:bg-white/20 hover:-translate-y-0.5 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/></svg>
                        Lihat Laporan
                    </a>
                </div>
            </div>

            {{-- Right: KPI summary --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-4 hover:bg-white/15 transition-all">
                    <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z"/></svg>
                    </div>
                    <p class="text-[10px] opacity-80 font-bold uppercase tracking-wider">Anggota</p>
                    <p class="text-xl font-extrabold tracking-tight leading-none mt-1">{{ number_format($anggotaAktif) }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-4 hover:bg-white/15 transition-all">
                    <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25"/></svg>
                    </div>
                    <p class="text-[10px] opacity-80 font-bold uppercase tracking-wider">Simpanan</p>
                    <p class="text-xl font-extrabold tracking-tight leading-none mt-1">{{ $totalFmt }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-4 hover:bg-white/15 transition-all">
                    <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3"/></svg>
                    </div>
                    <p class="text-[10px] opacity-80 font-bold uppercase tracking-wider">Pinjaman</p>
                    <p class="text-xl font-extrabold tracking-tight leading-none mt-1">{{ number_format($pinjamanAktif) }}</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes hero-blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, -30px) scale(1.1); }
            66% { transform: translate(-30px, 20px) scale(0.95); }
        }
    </style>
</x-filament-widgets::widget>
