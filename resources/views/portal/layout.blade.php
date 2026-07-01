<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('portal_dark') === '1' }" x-init="$watch('darkMode', v => { localStorage.setItem('portal_dark', v ? '1' : '0'); document.documentElement.classList.toggle('dark', v); }); if (darkMode) document.documentElement.classList.add('dark')">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Anggota') — {{ config('app.name') }}</title>
        <meta name="theme-color" content="#059669">

    {{-- PWA — installable di home screen Android/iOS --}}
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="apple-touch-icon" href="/icons/icon.svg">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="KoperasiApp">
    <meta name="mobile-web-app-capable" content="yes">
    <script>
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", () => {
                navigator.serviceWorker.register("/service-worker.js", { scope: "/portal" })
                    .catch(err => console.warn("SW register failed:", err));
            });
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], mono: ['JetBrains Mono', 'monospace'] },
                    animation: {
                        'fade-in': 'fadeIn .5s cubic-bezier(.16,1,.3,1)',
                        'slide-up': 'slideUp .5s cubic-bezier(.16,1,.3,1)',
                        'blob': 'blob 14s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 2.5s ease-in-out infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'shimmer': 'shimmer 3s linear infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': {opacity:0}, '100%': {opacity:1} },
                        slideUp: { '0%': {opacity:0, transform:'translateY(16px)'}, '100%': {opacity:1, transform:'translateY(0)'} },
                        blob: {
                            '0%,100%': { transform: 'translate(0,0) scale(1)' },
                            '33%': { transform: 'translate(40px,-30px) scale(1.1)' },
                            '66%': { transform: 'translate(-30px,20px) scale(0.95)' },
                        },
                        pulseGlow: { '0%,100%': { boxShadow: '0 0 0 0 rgba(16,185,129,.45)' }, '50%': { boxShadow: '0 0 0 14px rgba(16,185,129,0)' } },
                        float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-8px)' } },
                        shimmer: { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
                    },
                },
            },
        };
    </script>
    <style>
        :root {
            --shadow-soft: 0 1px 2px rgba(15,23,42,.04), 0 4px 12px rgba(15,23,42,.04);
            --shadow-card: 0 1px 3px rgba(15,23,42,.04), 0 8px 24px -8px rgba(15,23,42,.08);
            --shadow-pop:  0 12px 40px -12px rgba(15,23,42,.18);
            --shadow-glow: 0 8px 32px -8px rgba(5,150,105,.45);
            --shadow-glow-purple: 0 8px 32px -8px rgba(168,85,247,.4);
        }
        * { font-feature-settings: "cv02","cv03","cv04","cv11"; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-font-smoothing: antialiased; }
        body { background:
            radial-gradient(at 5% 5%, rgba(16,185,129,.08), transparent 45%),
            radial-gradient(at 95% 95%, rgba(8,145,178,.06), transparent 45%),
            radial-gradient(at 95% 5%, rgba(168,85,247,.04), transparent 50%),
            #f8fafc;
            min-height: 100vh;
        }
        .gradient-bg { background: linear-gradient(135deg, #047857 0%, #0d9488 50%, #0891b2 100%); }
        .gradient-bg-2 { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%); }
        .gradient-bg-3 { background: linear-gradient(135deg, #f59e0b 0%, #f97316 50%, #ef4444 100%); }
        .gradient-mesh {
            background:
                radial-gradient(at 12% 8%, #10b981 0%, transparent 45%),
                radial-gradient(at 88% 12%, #06b6d4 0%, transparent 45%),
                radial-gradient(at 18% 92%, #14b8a6 0%, transparent 45%),
                radial-gradient(at 92% 88%, #0891b2 0%, transparent 45%),
                linear-gradient(135deg, #047857 0%, #0d9488 50%, #0891b2 100%);
        }
        .gradient-text { background: linear-gradient(135deg, #047857, #0891b2); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .glass {
            background: rgba(255,255,255,0.72);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
        }
        .glass-dark {
            background: rgba(15,23,42,0.7);
            backdrop-filter: blur(20px) saturate(180%);
        }
        .card {
            background: white; border: 1px solid rgb(241 245 249);
            border-radius: 1rem; box-shadow: var(--shadow-card);
            transition: all .3s cubic-bezier(.16,1,.3,1);
        }
        .dark body { background: #0f172a; }
        .dark .card { background: #1e293b; border-color: #334155; }
        .dark .glass { background: rgba(30,41,59,.72); }
        .dark .text-slate-900, .dark .text-stone-900 { color: #f1f5f9 !important; }
        .dark .text-slate-600, .dark .text-stone-600, .dark .text-slate-500 { color: #94a3b8 !important; }
        .dark .text-slate-400, .dark .text-stone-400 { color: #64748b !important; }
        }
        .card:hover { box-shadow: var(--shadow-pop); border-color: rgb(226 232 240); transform: translateY(-2px); }
        .card-gradient {
            position: relative; overflow: hidden;
            background: linear-gradient(135deg, #047857 0%, #0d9488 50%, #0891b2 100%);
            color: white; border-radius: 1.25rem;
            box-shadow: var(--shadow-glow);
        }
        .card-glass {
            position: relative; overflow: hidden;
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.8);
            border-radius: 1rem;
            box-shadow: 0 8px 32px -8px rgba(15,23,42,0.15);
        }
        .nav-item {
            position: relative;
            transition: all .25s cubic-bezier(.16,1,.3,1);
        }
        .nav-item.active {
            background: linear-gradient(135deg, rgba(16,185,129,.14), rgba(6,182,212,.1));
            color: rgb(4 120 87);
        }
        .nav-item.active::before {
            content:''; position: absolute; left: 0; top: 18%; bottom: 18%; width: 3px;
            background: linear-gradient(180deg, #10b981, #06b6d4);
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 8px rgba(16,185,129,.4);
        }
        .nav-item.active .nav-icon {
            background: linear-gradient(135deg, #10b981, #06b6d4);
            color: white;
            box-shadow: 0 4px 12px -3px rgba(5,150,105,.5);
        }
        .nav-icon {
            width: 36px; height: 36px; border-radius: .75rem;
            display: flex; align-items: center; justify-content: center;
            background: rgb(241 245 249); color: rgb(100 116 139);
            transition: all .2s;
            font-size: 18px;
        }
        .nav-item:hover:not(.active) .nav-icon { background: rgb(226 232 240); color: rgb(15 23 42); transform: scale(1.05); }
        .blob { position: absolute; border-radius: 50%; filter: blur(60px); opacity: .55; mix-blend-mode: screen; pointer-events: none; }
        .grid-pattern {
            background-image:
                linear-gradient(rgba(15,23,42,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15,23,42,.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .icon-box { display: inline-flex; align-items: center; justify-content: center; border-radius: .875rem; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgb(203 213 225); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgb(148 163 184); }
        [x-cloak] { display: none !important; }

        /* Tablet — 1024px: collapsed icon sidebar */
        @media (max-width: 1023px) {
            .sidebar-collapsible { width: 4.5rem; }
            .sidebar-collapsible .sidebar-label,
            .sidebar-collapsible .sidebar-badge,
            .sidebar-collapsible .sidebar-sublabel,
            .sidebar-collapsible .sidebar-divider-text,
            .sidebar-collapsible .sidebar-app-info,
            .sidebar-collapsible .sidebar-footer-text,
            .sidebar-collapsible .sidebar-promo-text { display: none; }
            .sidebar-collapsible .nav-item { justify-content: center; padding-left: 0; padding-right: 0; }
            .sidebar-collapsible .nav-icon { margin-right: 0; }
            .sidebar-collapsible .sidebar-brand-text { display: none; }
        }

        /* Mobile — 640px: reduced padding & font sizes */
        @media (max-width: 640px) {
            .card { border-radius: 10px; }
            .card-gradient { border-radius: 14px; }
            h2 { font-size: 1.5rem; }
            h3 { font-size: 1.1rem; }
            .btn-mobile-full { width: 100%; justify-content: center; }
        }

        /* Touch target WCAG 2.5.5 */
        @media (pointer: coarse) {
            .nav-item, button, a.card, input, select, textarea {
                min-height: 44px;
            }
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="text-slate-800" x-data="{ sidebar: false }" x-cloak>

<div x-show="sidebar" @click="sidebar=false" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 md:hidden"></div>

<div class="flex min-h-screen">

    {{-- ============= SIDEBAR (GLASS) ============= --}}
    <aside class="fixed md:sticky top-0 left-0 z-50 h-screen w-72 glass border-r border-white/40 transition-all duration-300 md:translate-x-0 lg:w-72 sidebar-collapsible"
           :class="sidebar ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
        <div class="flex flex-col h-full">

            <div class="p-6 border-b border-slate-200/60">
                <div class="flex items-center gap-3">
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 rounded-2xl gradient-bg flex items-center justify-center text-white font-extrabold text-xl shadow-lg shadow-emerald-500/40">K</div>
                        <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-400 ring-2 ring-white animate-pulse-glow"></span>
                    </div>
                    <div class="sidebar-brand-text">
                        <div class="font-extrabold text-slate-900 text-base leading-tight tracking-tight">Koperasi<span class="text-emerald-600">App</span></div>
                        <div class="text-[9px] text-slate-500 uppercase tracking-[0.2em] font-extrabold">Portal Anggota</div>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

                <div class="px-3 pt-1 pb-2 text-[9px] font-extrabold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="sidebar-divider-text">Menu Utama</span>
                    <span class="flex-1 h-px bg-gradient-to-r from-slate-300 to-transparent"></span>
                </div>

                <a href="{{ route('portal.dashboard') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 {{ request()->routeIs('portal.dashboard') ? 'active' : 'hover:bg-white/60' }}">
                    <span class="nav-icon">🏠</span>
                    <span class="sidebar-label">Dashboard</span>
                </a>
                <a href="{{ route('portal.simpanan') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 {{ request()->routeIs('portal.simpanan') ? 'active' : 'hover:bg-white/60' }}">
                    <span class="nav-icon">💰</span>
                    <span class="sidebar-label">Simpanan</span>
                </a>
                <a href="{{ route('portal.pinjaman') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 {{ request()->routeIs('portal.pinjaman') ? 'active' : 'hover:bg-white/60' }}">
                    <span class="nav-icon">💳</span>
                    <span class="sidebar-label">Pinjaman</span>
                </a>
                <a href="{{ route('portal.transaksi') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 {{ request()->routeIs('portal.transaksi') ? 'active' : 'hover:bg-white/60' }}">
                    <span class="nav-icon">📊</span>
                    <span class="sidebar-label">Riwayat</span>
                </a>
                <a href="{{ route('portal.profil') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 {{ request()->routeIs('portal.profil') ? 'active' : 'hover:bg-white/60' }}">
                    <span class="nav-icon">👤</span>
                    <span class="sidebar-label">Profil Saya</span>
                </a>

                <div class="px-3 pt-5 pb-2 text-[9px] font-extrabold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="sidebar-divider-text">Aksi Cepat</span>
                    <span class="flex-1 h-px bg-gradient-to-r from-slate-300 to-transparent"></span>
                </div>

                <a href="{{ route('portal.setoran') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 {{ request()->routeIs('portal.setoran') ? 'active' : 'hover:bg-white/60' }}">
                    <span class="nav-icon">⬆️</span>
                    <span class="sidebar-label">Setor Online</span>
                    <span class="ml-auto text-[9px] font-extrabold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded sidebar-badge">+</span>
                </a>
                <a href="{{ route('portal.pengajuan-pinjaman') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 {{ request()->routeIs('portal.pengajuan-pinjaman') ? 'active' : 'hover:bg-white/60' }}">
                    <span class="nav-icon">📝</span>
                    <span class="sidebar-label">Ajukan Pinjaman</span>
                </a>

                <a href="{{ route('portal.ppob') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 {{ request()->routeIs('portal.ppob') ? 'active' : 'hover:bg-white/60' }}">
                    <span class="nav-icon">⚡</span>
                    <span class="sidebar-label">PPOB & Bayar</span>
                </a>
                <a href="{{ route('portal.voting') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 {{ request()->routeIs('portal.voting') ? 'active' : 'hover:bg-white/60' }}">
                    <span class="nav-icon">🗳️</span>
                    <span class="sidebar-label">Voting RAT</span>
                </a>

                <div class="px-3 pt-5 pb-2 text-[9px] font-extrabold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="sidebar-divider-text">Laporan</span>
                    <span class="flex-1 h-px bg-gradient-to-r from-slate-300 to-transparent"></span>
                </div>

                <a href="{{ url('/laporan/neraca') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:bg-white/60">
                    <span class="nav-icon">📑</span>
                    <span class="sidebar-label">Neraca</span>
                </a>
                <a href="{{ url('/laporan/laba-rugi') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:bg-white/60">
                    <span class="nav-icon">📈</span>
                    <span class="sidebar-label">Laba-Rugi</span>
                </a>
                <a href="{{ url('/laporan/arus-kas') }}" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:bg-white/60">
                    <span class="nav-icon">💹</span>
                    <span class="sidebar-label">Arus Kas</span>
                </a>

                <div class="px-3 pt-5 pb-2 text-[9px] font-extrabold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="sidebar-divider-text">Bantuan</span>
                    <span class="flex-1 h-px bg-gradient-to-r from-slate-300 to-transparent"></span>
                </div>

                <a href="https://wa.me/6281296052010" target="_blank" class="nav-item flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:bg-white/60">
                    <span class="nav-icon" style="background: linear-gradient(135deg, #25d366, #128c7e); color: white;">💬</span>
                    <span class="sidebar-label">WhatsApp</span>
                    <svg class="w-3 h-3 ml-auto text-slate-400 sidebar-badge" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5 19.5 4.5M19.5 4.5h-9m9 0v9"/></svg>
                </a>
            </nav>

            {{-- App promo card --}}
            <div class="m-3 mb-2 p-4 rounded-2xl relative overflow-hidden gradient-mesh text-white sidebar-promo-text">
                <div class="absolute inset-0 grid-pattern opacity-20"></div>
                <div class="relative">
                    <div class="text-[10px] font-extrabold uppercase tracking-wider opacity-90 mb-1 sidebar-app-info">📱 Mobile App</div>
                    <div class="font-extrabold text-sm leading-tight mb-2.5 sidebar-app-info">Akses dari HP-mu</div>
                    <a href="https://wa.me/6281296052010?text=Saya%20mau%20info%20mobile%20app" class="inline-flex items-center gap-1 text-[10px] font-extrabold bg-white text-emerald-700 px-3 py-1.5 rounded-md hover:scale-105 transition sidebar-app-info">
                        Coming Soon →
                    </a>
                </div>
            </div>

            <div class="p-3 border-t border-slate-200/60">
                <form action="{{ route('portal.logout') }}" method="POST">@csrf
                    <button class="w-full flex items-center gap-3 pl-4 pr-3 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition group">
                        <span class="nav-icon group-hover:bg-rose-100 group-hover:text-rose-600">🚪</span>
                        <span class="sidebar-label">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ============= MAIN ============= --}}
    <div class="flex-1 flex flex-col min-h-screen relative">

        <header class="sticky top-0 z-30 glass border-b border-slate-200/60">
            <div class="flex items-center justify-between px-4 md:px-8 h-16">
                <button @click="sidebar = !sidebar" class="md:hidden p-2 -ml-2 rounded-lg hover:bg-slate-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>

                <div class="hidden md:block">
                    <h1 class="font-extrabold text-lg text-slate-900 tracking-tight leading-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 font-medium">@yield('page-subtitle', 'Selamat datang kembali')</p>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Dark Mode Toggle --}}
                    <button @click="darkMode = !darkMode" class="p-2 rounded-xl hover:bg-white/60 transition group" title="Dark/Light mode">
                        <span class="text-lg" x-text="darkMode ? '☀️' : '🌙'">🌙</span>
                    </button>
                    <button class="relative p-2 rounded-xl hover:bg-white/60 transition group" title="Notifikasi">
                        <span class="text-lg">🔔</span>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                    </button>

                    <div class="flex items-center gap-2.5 pl-3 ml-1 border-l border-slate-200">
                        <div class="relative">
                            <div class="w-9 h-9 rounded-full gradient-bg text-white flex items-center justify-center font-extrabold text-sm shadow-md shadow-emerald-500/30">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 ring-2 ring-white"></span>
                        </div>
                        <div class="hidden sm:block">
                            <div class="text-[13px] font-extrabold text-slate-900 leading-tight">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-emerald-600 font-extrabold uppercase tracking-wider leading-tight">● Anggota Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-8 animate-fade-in">
            @yield('content')
        </main>

        <footer class="px-4 md:px-8 py-6 border-t border-slate-200/60 text-center text-xs text-slate-500">
            © {{ date('Y') }} {{ config('app.name') }} · Bantuan: <a href="https://wa.me/6281296052010" class="text-emerald-600 hover:underline font-bold">0812-9605-2010</a>
        </footer>
    </div>
</div>

@stack('scripts')
</body>
</html>
