<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Aplikasi Koperasi Indonesia — Konvensional + Syariah dalam Satu Platform | WA 0812-9605-2010</title>
    <meta name="description" content="Software koperasi paling lengkap di Indonesia. Simpan pinjam, akuntansi otomatis, SHU, POS, 9 akad syariah. SaaS multi-tenant ready. Demo gratis: 0812-9605-2010">
    <meta name="keywords" content="aplikasi koperasi, software koperasi, koperasi syariah, KSP, KSPPS, KSU, simpan pinjam, akuntansi koperasi, SHU, koperasi serba usaha, sistem informasi koperasi, SAAS koperasi">
    <meta name="author" content="Whitelabel.co.id">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
    <meta name="theme-color" content="#059669">
    <link rel="canonical" href="{{ url('/') }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:title" content="Aplikasi Koperasi Lengkap — Konvensional + Syariah">
    <meta property="og:description" content="Software Koperasi Indonesia: simpan pinjam, akuntansi, SHU, POS. Demo: WA 0812-9605-2010">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ url('/og-image.png') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Aplikasi Koperasi Indonesia — Lengkap">
    <meta name="twitter:description" content="Konvensional + Syariah, modul lengkap. WA 0812-9605-2010">

    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Aplikasi Koperasi Serba Usaha",
        "description": "Software manajemen koperasi lengkap Indonesia. Konvensional & syariah.",
        "applicationCategory": "BusinessApplication",
        "operatingSystem": "Web",
        "offers": {"@type": "Offer", "priceCurrency": "IDR", "price": "0", "url": "https://wa.me/6281296052010"},
        "publisher": {"@type": "Organization", "name": "Whitelabel.co.id", "telephone": "+6281296052010"},
        "aggregateRating": {"@type": "AggregateRating", "ratingValue": "4.9", "reviewCount": "127"}
    }
    </script>
    @endverbatim

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                        'fade-in':    'fadeIn 0.8s ease-out forwards',
                        'float':      'float 6s ease-in-out infinite',
                        'shine':      'shine 3s linear infinite',
                        'blob':       'blob 15s infinite',
                        'gradient':   'gradient 8s ease infinite',
                    },
                    keyframes: {
                        fadeInUp: { '0%': {opacity:0, transform:'translateY(30px)'}, '100%': {opacity:1, transform:'translateY(0)'} },
                        fadeIn:   { '0%': {opacity:0}, '100%': {opacity:1} },
                        float:    { '0%,100%': {transform:'translateY(0)'}, '50%': {transform:'translateY(-20px)'} },
                        shine:    { '0%': {backgroundPosition:'-200%'}, '100%': {backgroundPosition:'200%'} },
                        blob:     { '0%,100%': {transform:'translate(0,0) scale(1)'}, '33%': {transform:'translate(30px,-50px) scale(1.1)'}, '66%': {transform:'translate(-20px,20px) scale(0.9)'} },
                        gradient: { '0%,100%': {backgroundPosition:'0% 50%'}, '50%': {backgroundPosition:'100% 50%'} },
                    },
                },
            },
        };
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-font-smoothing: antialiased; }
        .gradient-text {
            background: linear-gradient(135deg, #059669 0%, #0d9488 50%, #0891b2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #047857 0%, #0d9488 35%, #0891b2 70%, #1e40af 100%);
            background-size: 200% 200%;
        }
        .glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.15);
        }
        .glass-dark {
            background: rgba(15,23,42,0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.05);
        }
        .grid-pattern {
            background-image:
                linear-gradient(rgba(5,150,105,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(5,150,105,0.06) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        .shine-text {
            background: linear-gradient(90deg, #059669 0%, #fbbf24 30%, #059669 60%, #fbbf24 90%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 3s linear infinite;
        }
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s ease-out; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .blob {
            position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.4;
            mix-blend-mode: multiply; pointer-events: none;
        }
        .number-counter { font-variant-numeric: tabular-nums; }
        .card-lift { transition: transform .35s ease, box-shadow .35s ease; }
        .card-lift:hover { transform: translateY(-6px); box-shadow: 0 24px 48px -12px rgba(0,0,0,.18); }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        @keyframes scaleIn { 0% { transform: scale(.85); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        @keyframes pingSlow { 0% { transform: scale(1); opacity: 1; } 100% { transform: scale(1.5); opacity: 0; } }
        .animate-shimmer { background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,.15) 50%, transparent 75%); background-size: 200% 100%; animation: shimmer 1.8s ease-in-out infinite; }
        .animate-scale-in { animation: scaleIn .7s cubic-bezier(.16,1,.3,1) forwards; }
        .stagger-1 { animation-delay: 0.1s; opacity: 0; }
        .stagger-2 { animation-delay: 0.2s; opacity: 0; }
        .stagger-3 { animation-delay: 0.3s; opacity: 0; }
        .stagger-4 { animation-delay: 0.4s; opacity: 0; }
        details summary::-webkit-details-marker { display: none; }
        details[open] .arrow { transform: rotate(180deg); }
        .marquee {
            display: flex; animation: scroll 30s linear infinite;
        }
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white text-slate-900 overflow-x-hidden" x-data="{ mobileOpen: false }">

    {{-- ========== NAV ========== --}}
    <header class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="#" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl gradient-bg flex items-center justify-center text-white font-bold shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition">K</div>
                    <span class="font-bold text-slate-900 text-lg tracking-tight">Koperasi<span class="gradient-text">App</span></span>
                </a>
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                    <a href="#fitur" class="hover:text-slate-900 transition">Fitur</a>
                    <a href="#syariah" class="hover:text-slate-900 transition">Syariah</a>
                    <a href="#showcase" class="hover:text-slate-900 transition">Showcase</a>
                    <a href="#harga" class="hover:text-slate-900 transition">Harga</a>
                    <a href="#faq" class="hover:text-slate-900 transition">FAQ</a>
                </nav>
                <div class="flex items-center gap-3">
                    <a href="{{ url('/admin') }}" class="hidden sm:inline-block text-sm font-medium text-slate-600 hover:text-slate-900">Login</a>
                    <a href="https://wa.me/6281296052010?text=Halo,%20saya%20tertarik%20dengan%20Aplikasi%20Koperasi"
                       class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-lg shadow-slate-900/20">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                        <span class="hidden sm:inline">Demo Gratis</span>
                    </a>
                    {{-- Mobile Hamburger --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-slate-600 hover:text-slate-900">
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Nav Menu --}}
            <div x-show="mobileOpen" x-transition class="md:hidden py-4 border-t border-slate-100">
                <nav class="flex flex-col gap-3 text-sm font-medium text-slate-600">
                    <a href="#fitur" @click="mobileOpen = false" class="hover:text-slate-900 px-2 py-1">Fitur</a>
                    <a href="#syariah" @click="mobileOpen = false" class="hover:text-slate-900 px-2 py-1">Syariah</a>
                    <a href="#showcase" @click="mobileOpen = false" class="hover:text-slate-900 px-2 py-1">Showcase</a>
                    <a href="#harga" @click="mobileOpen = false" class="hover:text-slate-900 px-2 py-1">Harga</a>
                    <a href="#faq" @click="mobileOpen = false" class="hover:text-slate-900 px-2 py-1">FAQ</a>
                    <a href="{{ url('/admin') }}" class="hover:text-slate-900 px-2 py-1 sm:hidden">Login</a>
                </nav>
            </div>
        </div>
    </header>

    {{-- ========== HERO ========== --}}
    <section class="relative pt-32 pb-24 overflow-hidden">
        <div class="absolute inset-0 grid-pattern"></div>
        <div class="blob bg-emerald-300 w-[500px] h-[500px] -top-20 -left-20 animate-blob"></div>
        <div class="blob bg-cyan-300 w-[400px] h-[400px] top-40 right-0 animate-blob" style="animation-delay: 2s;"></div>
        <div class="blob bg-amber-200 w-[300px] h-[300px] bottom-0 left-1/2 animate-blob" style="animation-delay: 4s;"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center reveal">
                <div class="inline-flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 rounded-full text-xs font-semibold text-slate-700 mb-8 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Platform Koperasi #1 Indonesia · UU P2SK Compliant
                </div>

                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight leading-[1.05] mb-6">
                    Koperasi modern. <br>
                    <span class="gradient-text">Konvensional</span> &amp;
                    <span class="gradient-text">Syariah</span><br>
                    dalam satu platform.
                </h1>

                <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed">
                    Software koperasi serba usaha paling lengkap di Indonesia. Simpan pinjam,
                    akuntansi otomatis, SHU, toko/POS, RAT digital — semua siap pakai dalam <strong class="text-slate-900">10 menit instalasi</strong>.
                </p>

                <div class="flex flex-wrap justify-center gap-3 mb-12">
                    <a href="https://wa.me/6281296052010?text=Halo,%20saya%20mau%20demo%20Aplikasi%20Koperasi"
                       class="group inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-6 py-3.5 rounded-xl font-semibold transition shadow-xl shadow-slate-900/30 hover:shadow-2xl hover:-translate-y-0.5">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                        Mulai Demo Gratis
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="#showcase" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-slate-300 text-slate-900 px-6 py-3.5 rounded-xl font-semibold transition shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Lihat Demo
                    </a>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-sm text-slate-500">
                    <div class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Tanpa kartu kredit</div>
                    <div class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Setup 10 menit</div>
                    <div class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Backup harian</div>
                    <div class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> 100% lokal Indonesia</div>
                </div>
            </div>

            {{-- Hero Mockup --}}
            <div class="relative mt-20 max-w-6xl mx-auto reveal">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-200">
                    <div class="absolute inset-0 gradient-bg opacity-95"></div>
                    <div class="relative p-1.5">
                        <div class="bg-slate-900 rounded-2xl overflow-hidden">
                            <div class="flex items-center gap-1.5 px-4 py-3 border-b border-slate-700/50">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                <div class="ml-4 px-3 py-1 text-xs text-slate-400 bg-slate-800 rounded-md font-mono">koperasi-app.id/admin</div>
                            </div>
                            <div class="grid md:grid-cols-4 gap-0 bg-slate-50">
                                <div class="bg-slate-900 p-4 hidden md:block">
                                    <div class="space-y-1">
                                        <div class="text-xs text-slate-500 uppercase tracking-wider mb-2">Menu</div>
                                        <div class="flex items-center gap-2 text-emerald-400 bg-emerald-500/10 px-3 py-2 rounded-lg text-sm font-medium">📊 Dashboard</div>
                                        <div class="flex items-center gap-2 text-slate-400 px-3 py-2 rounded-lg text-sm">👥 Anggota</div>
                                        <div class="flex items-center gap-2 text-slate-400 px-3 py-2 rounded-lg text-sm">💰 Simpanan</div>
                                        <div class="flex items-center gap-2 text-slate-400 px-3 py-2 rounded-lg text-sm">🏦 Pinjaman</div>
                                        <div class="flex items-center gap-2 text-slate-400 px-3 py-2 rounded-lg text-sm">📊 Akuntansi</div>
                                        <div class="flex items-center gap-2 text-slate-400 px-3 py-2 rounded-lg text-sm">🛒 POS</div>
                                    </div>
                                </div>
                                <div class="md:col-span-3 p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                        <div class="bg-white rounded-xl p-4 border border-slate-200">
                                            <div class="text-xs text-slate-500 mb-1">Anggota</div>
                                            <div class="text-2xl font-bold text-slate-900 number-counter">3.847</div>
                                            <div class="text-xs text-emerald-600 font-medium">+12.5%</div>
                                        </div>
                                        <div class="bg-gradient-to-br from-emerald-500 to-teal-500 text-white rounded-xl p-4">
                                            <div class="text-xs opacity-80 mb-1">Simpanan</div>
                                            <div class="text-2xl font-bold number-counter">4.25M</div>
                                            <div class="text-xs opacity-80 font-medium">+8.2%</div>
                                        </div>
                                        <div class="bg-white rounded-xl p-4 border border-slate-200">
                                            <div class="text-xs text-slate-500 mb-1">Pinjaman</div>
                                            <div class="text-2xl font-bold text-slate-900 number-counter">2.89M</div>
                                            <div class="text-xs text-emerald-600 font-medium">+5.1%</div>
                                        </div>
                                        <div class="bg-white rounded-xl p-4 border border-slate-200">
                                            <div class="text-xs text-slate-500 mb-1">NPL</div>
                                            <div class="text-2xl font-bold text-emerald-600 number-counter">2.1%</div>
                                            <div class="text-xs text-slate-500">Sehat</div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-xl p-5 border border-slate-200">
                                        <div class="flex justify-between items-center mb-4">
                                            <div class="font-semibold text-slate-900">Trend Simpanan vs Pinjaman</div>
                                            <div class="flex gap-3 text-xs">
                                                <span class="flex items-center gap-1 text-emerald-600">● Simpanan</span>
                                                <span class="flex items-center gap-1 text-cyan-600">● Pinjaman</span>
                                            </div>
                                        </div>
                                        <svg viewBox="0 0 400 100" class="w-full h-24">
                                            <defs>
                                                <linearGradient id="g1" x1="0" x2="0" y1="0" y2="1">
                                                    <stop offset="0%" stop-color="#10b981" stop-opacity="0.3"/>
                                                    <stop offset="100%" stop-color="#10b981" stop-opacity="0"/>
                                                </linearGradient>
                                            </defs>
                                            <path d="M0,80 C50,75 100,40 150,35 C200,30 250,55 300,40 C350,25 380,15 400,10 L400,100 L0,100 Z" fill="url(#g1)"/>
                                            <path d="M0,80 C50,75 100,40 150,35 C200,30 250,55 300,40 C350,25 380,15 400,10" fill="none" stroke="#10b981" stroke-width="2"/>
                                            <path d="M0,90 C50,85 100,70 150,65 C200,60 250,75 300,55 C350,45 380,40 400,35" fill="none" stroke="#06b6d4" stroke-width="2" stroke-dasharray="3,3"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Floating tooltip --}}
                <div class="absolute -top-6 -right-6 hidden md:block animate-float">
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-4 max-w-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">⚡</div>
                            <div>
                                <div class="text-sm font-bold text-slate-900">Auto-Jurnal</div>
                                <div class="text-xs text-slate-500">Tiap transaksi langsung tercatat</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-6 -left-6 hidden md:block animate-float" style="animation-delay: 1s;">
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-4 max-w-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">🕌</div>
                            <div>
                                <div class="text-sm font-bold text-slate-900">9 Akad Syariah</div>
                                <div class="text-xs text-slate-500">Murabahah, Mudharabah, dll</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== STATS COUNTER ========== --}}
    <section class="py-16 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                @php
                $stats = [
                    ['count' => '500+', 'label' => 'Koperasi Terbantu', 'icon' => '🏢'],
                    ['count' => '250K+', 'label' => 'Anggota Terdaftar', 'icon' => '👥'],
                    ['count' => '4.9/5', 'label' => 'Rating Pengguna', 'icon' => '⭐'],
                    ['count' => '99.9%', 'label' => 'Uptime SLA', 'icon' => '🛡️'],
                ];
                @endphp
                @foreach($stats as $s)
                    <div class="reveal">
                        <div class="text-4xl mb-2">{{ $s['icon'] }}</div>
                        <div class="text-4xl md:text-5xl font-extrabold gradient-text number-counter mb-1">{{ $s['count'] }}</div>
                        <div class="text-sm text-slate-600 font-medium">{{ $s['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========== LOGO CLOUD --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em] mb-8">Patuh Standar &amp; Regulasi Indonesia</p>
            <div class="flex flex-wrap justify-center items-center gap-x-12 gap-y-6 text-slate-700">
                <div class="flex items-center gap-2 font-bold"><span class="text-2xl">📋</span> SAK ETAP</div>
                <div class="flex items-center gap-2 font-bold"><span class="text-2xl">🕌</span> PSAK Syariah</div>
                <div class="flex items-center gap-2 font-bold"><span class="text-2xl">📊</span> Permenkop</div>
                <div class="flex items-center gap-2 font-bold"><span class="text-2xl">🏛️</span> Kemenkop UKM</div>
                <div class="flex items-center gap-2 font-bold"><span class="text-2xl">⚖️</span> UU P2SK 2023</div>
                <div class="flex items-center gap-2 font-bold"><span class="text-2xl">🛡️</span> OJK LKM</div>
            </div>
        </div>
    </section>

    {{-- ========== FITUR --}}
    <section id="fitur" class="py-24 bg-gradient-to-b from-white to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider mb-4">FITUR LENGKAP</span>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">
                    Semua yang dibutuhkan koperasi modern
                </h2>
                <p class="text-lg text-slate-600">25+ modul terintegrasi. Tidak perlu beli software tambahan untuk akuntansi, POS, atau payroll.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-5">
                @php
                $fitur = [
                    ['👥', 'Manajemen Anggota', 'CRUD lengkap, ahli waris, dokumen, KTA cetak, import Excel massal', 'from-emerald-500 to-teal-500'],
                    ['💰', 'Simpan Pinjam Lengkap', '6 jenis simpanan + pinjaman konv & 9 akad syariah dengan auto-jurnal', 'from-cyan-500 to-blue-500'],
                    ['🏦', '9 Akad Syariah', 'Murabahah, Mudharabah, Musyarakah, Ijarah, Qardh, Rahn, Salam, Istishna', 'from-purple-500 to-pink-500'],
                    ['📊', 'Akuntansi Otomatis', 'COA siap pakai, jurnal otomatis dari semua transaksi, laporan PDF', 'from-amber-500 to-orange-500'],
                    ['🏪', 'Toko / POS', 'Multi-satuan, multi-harga, bayar via potong simpanan, struk thermal', 'from-rose-500 to-red-500'],
                    ['🍰', 'SHU Otomatis', 'Hitung & distribusi otomatis akhir tahun ke simpanan/tunai/tahan', 'from-violet-500 to-indigo-500'],
                    ['📅', 'RAT Digital', 'Daftar hadir QR, quorum tracker, notulen, laporan tahunan', 'from-teal-500 to-emerald-500'],
                    ['📱', 'Portal & Mobile', 'Anggota cek saldo, ajukan pinjaman, bayar via QRIS dari HP', 'from-blue-500 to-indigo-500'],
                    ['⚠️', 'Manajemen Risiko', 'Kolektabilitas otomatis (5 level OJK), denda harian, PPAP', 'from-orange-500 to-red-500'],
                    ['🔔', 'WhatsApp Reminder', 'Reminder angsuran H-3 & H-1, konfirmasi setoran otomatis', 'from-emerald-500 to-green-500'],
                    ['🏢', 'Multi-Cabang', 'Konsolidasi laporan, transfer antar cabang, stok antar gudang', 'from-slate-500 to-slate-700'],
                    ['👔', 'HR & Payroll', 'Karyawan, gaji bulanan, slip gaji, BPJS, PPh21', 'from-pink-500 to-rose-500'],
                ];
                @endphp
                @foreach($fitur as $f)
                    <div class="reveal group relative bg-white rounded-2xl p-6 border border-slate-200 hover:border-slate-300 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 card-lift">
                        <div class="absolute inset-0 bg-gradient-to-br {{ $f[3] }} opacity-0 group-hover:opacity-5 rounded-2xl transition-opacity"></div>
                        <div class="relative">
                            <div class="text-3xl mb-4 inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br {{ $f[3] }} bg-opacity-10">{{ $f[0] }}</div>
                            <h3 class="font-bold text-lg text-slate-900 mb-2">{{ $f[1] }}</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $f[2] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========== SYARIAH --}}
    <section id="syariah" class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 gradient-bg opacity-95"></div>
        <div class="absolute inset-0 grid-pattern opacity-20"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 text-white reveal">
                <span class="inline-block glass text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider mb-4">DUAL MODE</span>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
                    Konvensional &amp; Syariah<br>
                    <span class="text-amber-300">dalam satu aplikasi</span>
                </h2>
                <p class="text-lg opacity-90">
                    Koperasi Anda bisa pilih mode konvensional, syariah, atau dual (kedua produk berdampingan).
                    Cocok untuk KSPPS dan KSU yang melayani semua anggota.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 reveal">
                <div class="glass rounded-3xl p-8 text-white">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="text-4xl mb-3">💼</div>
                            <h3 class="text-2xl font-bold">Konvensional</h3>
                            <p class="text-sm opacity-80 mt-1">Standar koperasi umum</p>
                        </div>
                        <span class="text-xs bg-white/20 px-3 py-1.5 rounded-full font-semibold">3 metode</span>
                    </div>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3"><span class="text-emerald-300 mt-0.5">✓</span><div><strong>Bunga Flat</strong> — sederhana, angsuran tetap</div></li>
                        <li class="flex items-start gap-3"><span class="text-emerald-300 mt-0.5">✓</span><div><strong>Bunga Menurun (Efektif)</strong> — bunga dari saldo pokok</div></li>
                        <li class="flex items-start gap-3"><span class="text-emerald-300 mt-0.5">✓</span><div><strong>Anuitas</strong> — angsuran tetap, komposisi berubah</div></li>
                        <li class="flex items-start gap-3"><span class="text-emerald-300 mt-0.5">✓</span><div>Simpanan Pokok, Wajib, Sukarela, Berjangka</div></li>
                    </ul>
                </div>

                <div class="glass rounded-3xl p-8 text-white border-2 border-amber-300/50 relative">
                    <div class="absolute -top-3 left-8 bg-amber-300 text-amber-900 text-xs font-bold px-3 py-1 rounded-full">9 AKAD LENGKAP</div>
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="text-4xl mb-3">🕌</div>
                            <h3 class="text-2xl font-bold">Syariah</h3>
                            <p class="text-sm opacity-80 mt-1">Sesuai Fatwa DSN-MUI</p>
                        </div>
                    </div>
                    <ul class="grid grid-cols-2 gap-2 text-sm">
                        <li class="flex items-center gap-2"><span class="text-amber-300">✓</span> Murabahah</li>
                        <li class="flex items-center gap-2"><span class="text-amber-300">✓</span> Mudharabah</li>
                        <li class="flex items-center gap-2"><span class="text-amber-300">✓</span> Musyarakah</li>
                        <li class="flex items-center gap-2"><span class="text-amber-300">✓</span> Ijarah</li>
                        <li class="flex items-center gap-2"><span class="text-amber-300">✓</span> Ijarah MB</li>
                        <li class="flex items-center gap-2"><span class="text-amber-300">✓</span> Qardh</li>
                        <li class="flex items-center gap-2"><span class="text-amber-300">✓</span> Rahn</li>
                        <li class="flex items-center gap-2"><span class="text-amber-300">✓</span> Salam</li>
                        <li class="flex items-center gap-2"><span class="text-amber-300">✓</span> Istishna</li>
                        <li class="flex items-center gap-2"><span class="text-amber-300">✓</span> Wadiah</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== SHOWCASE --}}
    <section id="showcase" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider mb-4">SCREENSHOT</span>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">Lihat aplikasinya beraksi</h2>
                <p class="text-lg text-slate-600">Interface Filament 3 — modern, responsive, dan dirancang untuk produktivitas.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="reveal bg-gradient-to-br from-emerald-50 to-cyan-50 rounded-2xl p-6 border border-emerald-100">
                    <div class="bg-white rounded-xl p-4 shadow-sm mb-4">
                        <div class="text-xs font-semibold text-slate-500 uppercase mb-2">Dashboard Admin</div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-slate-50 rounded-lg p-2"><div class="text-xs text-slate-500">Anggota</div><div class="text-lg font-bold">3.847</div></div>
                            <div class="bg-emerald-500 text-white rounded-lg p-2"><div class="text-xs opacity-80">Simpanan</div><div class="text-lg font-bold">4.25M</div></div>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-900">Dashboard Real-time</h3>
                    <p class="text-sm text-slate-600 mt-1">KPI utama, NPL, pertumbuhan</p>
                </div>

                <div class="reveal bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-100">
                    <div class="bg-white rounded-xl p-4 shadow-sm mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-xs font-semibold text-slate-500 uppercase">Jadwal Angsuran</div>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 rounded">Lancar</span>
                        </div>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between"><span>Bln 1</span><span class="font-mono text-emerald-600">✓ 916.800</span></div>
                            <div class="flex justify-between"><span>Bln 2</span><span class="font-mono text-emerald-600">✓ 916.800</span></div>
                            <div class="flex justify-between"><span>Bln 3</span><span class="font-mono text-amber-600">● 916.800</span></div>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-900">Workflow Pinjaman</h3>
                    <p class="text-sm text-slate-600 mt-1">Approval multi-level + auto-jurnal</p>
                </div>

                <div class="reveal bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                    <div class="bg-white rounded-xl p-4 shadow-sm mb-4">
                        <div class="text-xs font-semibold text-slate-500 uppercase mb-2">Mobile Anggota</div>
                        <div class="bg-slate-50 rounded-lg p-3">
                            <div class="text-xs text-slate-500">Total Saldo</div>
                            <div class="text-2xl font-bold gradient-text">Rp 12.450.000</div>
                            <div class="text-xs text-emerald-600 mt-1">+Rp 250.000 minggu ini</div>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-900">Portal & Mobile</h3>
                    <p class="text-sm text-slate-600 mt-1">Cek saldo, ajukan pinjaman dari HP</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== TESTIMONIAL --}}
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">Dipercaya 500+ koperasi</h2>
                <p class="text-lg text-slate-600">Dari KSP skala kecil hingga KSU 10.000+ anggota.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @php
                $testi = [
                    ['Sebelumnya pakai Excel & manual jurnal — sering selisih. Setelah pakai aplikasi ini, RAT kami selesai 3 minggu lebih cepat.', 'H. Suparman', 'Ketua KSU Sejahtera Bersama, Yogyakarta', '⭐⭐⭐⭐⭐'],
                    ['Modul syariahnya benar-benar lengkap. Dewan syariah kami review dan langsung approve. 9 akad terstruktur sesuai DSN-MUI.', 'Hj. Aisyah Rahman', 'KSPPS Amanah Ummah, Bandung', '⭐⭐⭐⭐⭐'],
                    ['Anggota suka karena bisa cek saldo dari HP. Reminder WhatsApp otomatis bikin tunggakan turun 60% dalam 6 bulan.', 'Pak Budi Santoso', 'KSP Mitra Tani, Solo', '⭐⭐⭐⭐⭐'],
                ];
                @endphp
                @foreach($testi as $t)
                    <div class="reveal bg-white rounded-2xl p-6 shadow-sm border border-slate-200 card-lift">
                        <div class="text-amber-400 mb-3">{{ $t[3] }}</div>
                        <p class="text-slate-700 mb-6 leading-relaxed">"{{ $t[0] }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full gradient-bg text-white flex items-center justify-center font-bold">{{ substr($t[1], 0, 1) }}</div>
                            <div>
                                <div class="font-bold text-slate-900 text-sm">{{ $t[1] }}</div>
                                <div class="text-xs text-slate-500">{{ $t[2] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========== HARGA --}}
    <section id="harga" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider mb-4">HARGA</span>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">Pilih paket yang sesuai</h2>
                <p class="text-lg text-slate-600">Bayar sekali, miliki source code. Atau pilih SaaS dengan whitelabel kustom.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto reveal">
                <div class="bg-white rounded-3xl p-8 border border-slate-200 hover:border-slate-300 hover:shadow-xl transition card-lift">
                    <div class="text-sm font-bold text-slate-500 uppercase tracking-wider">Regular</div>
                    <div class="mt-2 mb-1"><span class="text-4xl font-extrabold text-slate-900">1 Domain</span></div>
                    <p class="text-slate-500 text-sm mb-6">Untuk satu koperasi</p>
                    <a href="https://wa.me/6281296052010?text=Halo,%20saya%20minat%20paket%20Regular"
                       class="block text-center border-2 border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white px-4 py-3 rounded-xl font-semibold transition mb-6">
                        Tanya Harga
                    </a>
                    <ul class="space-y-3 text-sm text-slate-700">
                        <li class="flex gap-2"><span class="text-emerald-500">✓</span> Source code lengkap</li>
                        <li class="flex gap-2"><span class="text-emerald-500">✓</span> Semua fitur core</li>
                        <li class="flex gap-2"><span class="text-emerald-500">✓</span> Update 1 tahun</li>
                        <li class="flex gap-2"><span class="text-emerald-500">✓</span> Support email</li>
                    </ul>
                </div>

                <div class="relative bg-slate-900 text-white rounded-3xl p-8 shadow-2xl scale-105 border border-slate-800">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-amber-400 text-slate-900 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider">Populer</div>
                    <div class="text-sm font-bold text-amber-300 uppercase tracking-wider">Extended</div>
                    <div class="mt-2 mb-1"><span class="text-4xl font-extrabold">3 Domain</span></div>
                    <p class="text-slate-400 text-sm mb-6">Untuk multi-cabang</p>
                    <a href="https://wa.me/6281296052010?text=Halo,%20saya%20minat%20paket%20Extended"
                       class="block text-center bg-amber-400 hover:bg-amber-500 text-slate-900 px-4 py-3 rounded-xl font-bold transition mb-6">
                        Tanya Harga →
                    </a>
                    <ul class="space-y-3 text-sm">
                        <li class="flex gap-2"><span class="text-emerald-400">✓</span> Source code + maintenance</li>
                        <li class="flex gap-2"><span class="text-emerald-400">✓</span> Update 2 tahun</li>
                        <li class="flex gap-2"><span class="text-emerald-400">✓</span> Support prioritas WA</li>
                        <li class="flex gap-2"><span class="text-emerald-400">✓</span> Bantuan instalasi gratis</li>
                        <li class="flex gap-2"><span class="text-emerald-400">✓</span> Custom training online</li>
                    </ul>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-slate-200 hover:border-slate-300 hover:shadow-xl transition card-lift">
                    <div class="text-sm font-bold gradient-text uppercase tracking-wider">SaaS / Whitelabel</div>
                    <div class="mt-2 mb-1"><span class="text-4xl font-extrabold text-slate-900">Unlimited</span></div>
                    <p class="text-slate-500 text-sm mb-6">Multi-tenant + brand sendiri</p>
                    <a href="https://wa.me/6281296052010?text=Halo,%20saya%20minat%20SaaS%20Whitelabel"
                       class="block text-center bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-700 hover:to-cyan-700 text-white px-4 py-3 rounded-xl font-bold transition mb-6">
                        Custom Quote
                    </a>
                    <ul class="space-y-3 text-sm text-slate-700">
                        <li class="flex gap-2"><span class="text-emerald-500">✓</span> Multi-tenant unlimited</li>
                        <li class="flex gap-2"><span class="text-emerald-500">✓</span> Custom domain per tenant</li>
                        <li class="flex gap-2"><span class="text-emerald-500">✓</span> Branding 100% Anda</li>
                        <li class="flex gap-2"><span class="text-emerald-500">✓</span> Source code + maintenance</li>
                        <li class="flex gap-2"><span class="text-emerald-500">✓</span> SLA 99.9% uptime</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== FAQ --}}
    <section id="faq" class="py-24 bg-slate-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">Pertanyaan yang sering diajukan</h2>
            </div>

            <div class="space-y-3 reveal">
                @php
                $faqs = [
                    ['Apakah aplikasi ini benar-benar lengkap?', 'Ya. Mencakup 25+ modul: Anggota, Simpanan, Pinjaman (konv & syariah), Akuntansi, SHU, POS, Unit Produsen, Unit Jasa, Iuran, Asuransi, HR, Aset, RAT, Notifikasi, Multi-cabang. Tidak perlu beli software tambahan.'],
                    ['Mendukung berapa anggota?', 'Skala awal hingga 10.000 anggota per instalasi. Untuk skala lebih besar atau melayani banyak koperasi sekaligus, tersedia paket SaaS multi-tenant unlimited.'],
                    ['Dual mode konvensional + syariah, bagaimana cara kerjanya?', 'Setiap koperasi bisa pilih mode (konvensional/syariah/dual) di pengaturan. Setiap produk simpanan/pinjaman punya akad sendiri. Sistem otomatis pakai Calculator yang tepat: Flat/Efektif/Anuitas untuk konvensional, atau Murabahah/Mudharabah/dll untuk syariah.'],
                    ['Apakah ada portal & mobile untuk anggota?', 'Ada. Portal web siap pakai (cek saldo, pinjaman, ajukan online). API Sanctum tersedia untuk integrasi Flutter/native mobile app.'],
                    ['Bagaimana dengan keamanan & lisensi?', 'Aplikasi terkunci per-domain via API lisensi (whitelabel.co.id). Validasi tiap boot dengan offline fallback HMAC checksum. Backup database harian otomatis.'],
                    ['Berapa lama setup?', 'Paket Regular & Extended: 10 menit untuk install, 30 menit untuk konfigurasi awal. Kami sediakan video tutorial + bantuan WhatsApp jika butuh.'],
                    ['Bagaimana cara membeli?', 'Hubungi WhatsApp 0812-9605-2010. Kami akan jadwalkan demo (30 menit), kasih harga sesuai kebutuhan, dan bantu instalasi.'],
                ];
                @endphp
                @foreach($faqs as $f)
                    <details class="group bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-all hover:shadow-md">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-900 flex justify-between items-center gap-4 list-none">
                            <span class="flex-1">{{ $f[0] }}</span>
                            <svg class="arrow w-5 h-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600 leading-relaxed">{{ $f[1] }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========== CTA --}}
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 gradient-bg opacity-95"></div>
        <div class="absolute inset-0 grid-pattern opacity-20"></div>
        <div class="blob bg-amber-300 w-[400px] h-[400px] top-20 left-1/4 animate-blob"></div>
        <div class="blob bg-cyan-300 w-[400px] h-[400px] bottom-0 right-1/4 animate-blob" style="animation-delay: 3s;"></div>

        <div class="relative max-w-4xl mx-auto px-4 text-center text-white">
            <div class="inline-flex items-center gap-2 glass px-4 py-2 rounded-full text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                Aktif menerima permintaan demo
            </div>
            <h2 class="text-5xl md:text-6xl font-extrabold tracking-tight mb-6">
                Ready untuk modernisasi<br>koperasi Anda?
            </h2>
            <p class="text-xl opacity-90 mb-10 max-w-2xl mx-auto">
                Demo gratis 30 menit. Kami bantu pilih paket yang cocok dengan skala koperasi Anda.
            </p>
            <a href="https://wa.me/6281296052010?text=Halo,%20saya%20mau%20demo%20Aplikasi%20Koperasi"
               class="group inline-flex items-center gap-3 bg-white text-slate-900 px-8 py-5 rounded-2xl font-bold text-lg shadow-2xl hover:shadow-emerald-500/30 hover:scale-105 transition">
                <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                <span>WhatsApp 0812-9605-2010</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <p class="text-sm opacity-75 mt-6">Respon &lt; 5 menit · Tanpa kartu kredit · Free konsultasi</p>
        </div>
    </section>

    {{-- ========== FOOTER --}}
    <footer class="bg-slate-950 text-slate-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-9 h-9 rounded-xl gradient-bg flex items-center justify-center text-white font-bold shadow-lg">K</div>
                        <span class="font-bold text-white text-lg">Koperasi<span class="gradient-text">App</span></span>
                    </div>
                    <p class="text-sm leading-relaxed mb-4 max-w-md">Software lengkap untuk Koperasi Serba Usaha (KSP, KSPPS, KSU) di Indonesia. Konvensional &amp; syariah dalam satu aplikasi.</p>
                    <div class="flex gap-3">
                        <a href="https://wa.me/6281296052010" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-emerald-500 flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-sm">Produk</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#fitur" class="hover:text-white">Fitur</a></li>
                        <li><a href="#syariah" class="hover:text-white">Mode Syariah</a></li>
                        <li><a href="#harga" class="hover:text-white">Harga</a></li>
                        <li><a href="{{ url('/admin') }}" class="hover:text-white">Login Admin</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-sm">Hubungi Kami</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">📱 <a href="https://wa.me/6281296052010" class="hover:text-white">0812-9605-2010</a></li>
                        <li class="flex items-center gap-2">🌐 <a href="https://whitelabel.co.id" class="hover:text-white">whitelabel.co.id</a></li>
                        <li class="flex items-center gap-2 text-xs opacity-70">⏰ Respon &lt; 5 menit</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
                <div>© {{ date('Y') }} KoperasiApp. Lisensi via whitelabel.co.id</div>
                <div class="flex gap-6">
                    <a href="/sitemap.xml" class="hover:text-white">Sitemap</a>
                    <a href="/robots.txt" class="hover:text-white">Robots</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Floating WhatsApp --}}
    <a href="https://wa.me/6281296052010?text=Halo,%20saya%20mau%20tanya%20Aplikasi%20Koperasi"
       class="fixed bottom-6 right-6 z-50 group">
        <div class="absolute inset-0 bg-emerald-500 rounded-full animate-ping opacity-40"></div>
        <div class="relative bg-emerald-500 hover:bg-emerald-600 text-white rounded-full p-4 shadow-2xl flex items-center gap-2 transition hover:scale-110">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
        </div>
    </a>

    {{-- Scroll reveal --}}
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>

    {{-- ===================== WELCOME POPUP ===================== --}}
    <div id="welcome-popup" class="fixed inset-0 z-[200] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="popup-title">
        <div class="relative w-full max-w-3xl max-h-[92vh] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col" id="popup-card">
            <button onclick="closeWelcomePopup()" class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-white/90 hover:bg-slate-100 flex items-center justify-center text-slate-600 transition" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 px-7 py-7 text-white relative overflow-hidden flex-shrink-0">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-pink-300/20 rounded-full blur-3xl"></div>
                <div class="relative">
                    <span class="inline-block bg-white/20 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">💎 Source Code Premium</span>
                    <h2 id="popup-title" class="text-2xl md:text-3xl font-extrabold mb-1">Aplikasi Koperasi Indonesia</h2>
                    <p class="text-white/90 text-sm">Beli <strong>source code lengkap</strong> — Laravel 12 + Filament 3.3, Konvensional + Syariah, siap deploy.</p>
                </div>
            </div>

            <div class="border-b border-slate-200 flex flex-shrink-0 overflow-x-auto" role="tablist">
                <button onclick="switchTab('beli')" id="tab-beli" class="popup-tab popup-tab-active flex-1 min-w-[120px] px-4 py-3 text-sm font-bold border-b-2 transition" role="tab">🛒 Beli / Demo</button>
                <button onclick="switchTab('cara')" id="tab-cara" class="popup-tab flex-1 min-w-[120px] px-4 py-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-900 transition" role="tab">📖 Cara Pakai</button>
                <button onclick="switchTab('fitur')" id="tab-fitur" class="popup-tab flex-1 min-w-[120px] px-4 py-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-900 transition" role="tab">⚡ Fitur Lengkap</button>
            </div>

            <div class="overflow-y-auto px-7 py-6 flex-1">

                {{-- TAB: Beli / Demo --}}
                <div id="panel-beli" class="popup-panel">
                    <div class="text-center mb-5">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl shadow-lg shadow-emerald-500/30 mb-3">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91C21.95 6.45 17.5 2 12.04 2zm5.46 14.13c-.23.65-1.34 1.24-1.87 1.32-.48.07-1.09.1-1.76-.11-.41-.13-.93-.3-1.6-.59-2.82-1.22-4.66-4.06-4.8-4.25-.14-.19-1.14-1.51-1.14-2.89s.72-2.05.98-2.34c.26-.29.57-.36.76-.36s.38 0 .55.01c.18.01.41-.07.65.49.23.57.79 1.95.86 2.09.07.14.12.31.02.5-.09.19-.14.31-.28.48-.14.17-.3.38-.43.51-.14.14-.29.29-.12.57.16.28.73 1.21 1.57 1.96 1.08.96 1.99 1.26 2.27 1.4.28.14.45.12.61-.07.16-.19.7-.82.89-1.1.19-.28.37-.23.63-.14.26.09 1.64.77 1.92.91.28.14.46.21.53.33.07.12.07.69-.16 1.34z"/></svg>
                        </div>
                        <h3 class="font-extrabold text-2xl text-slate-900 mb-1">Beli Source Code Aplikasi</h3>
                        <p class="text-slate-600 text-sm">Kami <strong>hanya menjual source code</strong>. Anda dapat full akses repository, deploy di server sendiri, dan modifikasi sesuka hati.</p>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-2xl p-5 mb-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-emerald-700 mb-1">Nomor WhatsApp Pembelian</div>
                                <a href="https://wa.me/6281296052010?text=Halo,%20saya%20mau%20beli%20source%20code%20aplikasi%20koperasi." class="font-extrabold text-2xl md:text-3xl text-emerald-700 hover:text-emerald-800 transition" id="phone-display">0812-9605-2010</a>
                                <div class="text-xs text-slate-500 mt-1">Senin–Sabtu · 09.00–17.00 WIB · Respond &lt; 5 menit</div>
                            </div>
                            <button onclick="copyPhone()" class="bg-white hover:bg-emerald-100 text-emerald-700 font-bold px-3 py-2 rounded-lg text-xs transition flex-shrink-0 border border-emerald-200" id="copy-btn">📋 Salin</button>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-3 mb-4">
                        <a href="https://wa.me/6281296052010?text=Halo,%20saya%20mau%20beli%20source%20code%20aplikasi%20koperasi" class="flex items-center gap-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-4 py-3 rounded-xl shadow-lg shadow-emerald-500/30 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91C21.95 6.45 17.5 2 12.04 2z"/></svg>
                            <span>Chat WhatsApp Sekarang</span>
                        </a>
                        <a href="/docs.html" target="_blank" class="flex items-center gap-3 bg-white hover:bg-slate-50 text-slate-900 font-bold px-4 py-3 rounded-xl border-2 border-slate-200 transition">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Buka Dokumentasi Lengkap</span>
                        </a>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                        <div class="font-bold text-amber-900 mb-2 text-sm">🔑 Akun Default Setelah Install</div>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <div class="text-amber-700 font-bold">Admin Panel <code class="bg-amber-100 px-1 rounded">/admin</code></div>
                                <div class="text-slate-700 font-mono mt-1">Email: <strong>admin@koperasi.local</strong></div>
                                <div class="text-slate-700 font-mono">Password: <strong>admin123</strong></div>
                            </div>
                            <div>
                                <div class="text-amber-700 font-bold">License Pairing <code class="bg-amber-100 px-1 rounded">/__pair</code></div>
                                <div class="text-slate-700 mt-1">Tukar kunci aktivasi dari WhatsApp setelah pembelian.</div>
                            </div>
                        </div>
                        <p class="text-xs text-amber-800 mt-2">⚠️ <strong>Wajib ganti password</strong> setelah login pertama. Lihat docs lengkap untuk seed user lain (kasir, accounting, pengawas).</p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 text-sm">
                        <div class="font-bold text-slate-900 mb-2">💎 Yang Anda dapat saat beli source code:</div>
                        <ul class="space-y-1.5 text-slate-700">
                            <li class="flex gap-2"><span class="text-emerald-500 font-bold">✓</span><span><strong>Full source code</strong> — repository Laravel 12 + Filament 3.3 lengkap, no obfuscation</span></li>
                            <li class="flex gap-2"><span class="text-emerald-500 font-bold">✓</span><span><strong>Database migration & seeder</strong> — termasuk seeder demo data 100.000 record</span></li>
                            <li class="flex gap-2"><span class="text-emerald-500 font-bold">✓</span><span><strong>Dokumentasi instalasi</strong> — file <code class="bg-slate-200 px-1 rounded text-xs">docs.html</code> dengan tutorial step-by-step</span></li>
                            <li class="flex gap-2"><span class="text-emerald-500 font-bold">✓</span><span><strong>License key 1× aktivasi</strong> — domain-locked, valid selamanya</span></li>
                            <li class="flex gap-2"><span class="text-emerald-500 font-bold">✓</span><span><strong>Free update 6 bulan</strong> — bug fix dan minor feature</span></li>
                            <li class="flex gap-2"><span class="text-emerald-500 font-bold">✓</span><span><strong>Konsultasi WhatsApp 30 hari</strong> — bantuan setup dan troubleshooting</span></li>
                        </ul>
                    </div>
                </div>

                {{-- TAB: Cara Pakai --}}
                <div id="panel-cara" class="popup-panel hidden">
                    <h3 class="font-extrabold text-xl text-slate-900 mb-2">Cara Pakai Aplikasi Koperasi (Lengkap)</h3>
                    <p class="text-slate-600 text-sm mb-5">Dari aktivasi license sampai operasional harian — total &lt;1 jam setup pertama.</p>

                    <div class="space-y-4">
                        @foreach([
                            ['1', 'Aktivasi License', 'Kunjungi `/__pair`. Masukkan license key yang diberikan tim sales setelah pembayaran. Sistem auto-validasi domain dan generate `.license.lock`. Tanpa step ini, aplikasi terkunci.', 'bg-indigo-100 text-indigo-700'],
                            ['2', 'Login Admin Panel', 'Buka `/admin`. Login dengan akun Super Admin (kredensial dari email aktivasi). Masuk ke Filament Panel — dashboard modern dengan 15+ resource menu sidebar.', 'bg-purple-100 text-purple-700'],
                            ['3', 'Setup Profile Koperasi', 'Menu **Settings → Tenant** → isi nama koperasi, alamat, NIK badan hukum, NPWP, logo, dan pilih mode operasi: Konvensional / Syariah / Dual. Mode menentukan akad yang tersedia di produk.', 'bg-pink-100 text-pink-700'],
                            ['4', 'Konfigurasi Chart of Accounts (CoA)', 'Menu **Akuntansi → COA**. Sistem sudah include template CoA standar PSAK 27 — Anda tinggal review dan customize akun-akun spesifik koperasi (mis. Simpanan Lebaran, Dana Sosial).', 'bg-blue-100 text-blue-700'],
                            ['5', 'Tambah Produk Simpanan & Pinjaman', 'Menu **Produk → Simpanan / Pinjaman**. Buat produk dengan parameter: nama, plafon, tenor, bunga (atau akad syariah + nisbah), biaya admin, denda. Khusus syariah: pilih dari 12 akad (mudharabah, murabahah, ijarah, IMBT, salam, istishna, qardh, rahn, wakalah, kafalah, hawalah, musyarakah).', 'bg-emerald-100 text-emerald-700'],
                            ['6', 'Daftarkan Anggota', 'Menu **Anggota**. Bisa input manual satu-per-satu, atau **Import CSV** untuk migrasi data dari Excel — template CSV tersedia di tombol "Download Template". 1.000 anggota bisa di-import dalam 30 detik.', 'bg-amber-100 text-amber-700'],
                            ['7', 'Catat Transaksi Harian', 'Menu **Transaksi → Setoran / Penarikan / Cicilan**. Setiap input otomatis menjurnal akuntansi (debet/kredit), update saldo anggota, update kas, dan kirim notifikasi WhatsApp ke anggota.', 'bg-teal-100 text-teal-700'],
                            ['8', 'Anggota Akses Portal', 'Anggota login di `/portal/login` dengan username (NIK) + password. Bisa cek saldo simpanan, riwayat transaksi, sisa cicilan, ajukan pinjaman online, download bukti setoran. Mobile-friendly.', 'bg-cyan-100 text-cyan-700'],
                            ['9', 'Tutup Buku Bulanan', 'Menu **Akuntansi → Tutup Buku**. Pilih periode → klik "Tutup Buku" → sistem auto-generate Neraca, Laba-Rugi, Arus Kas, Trial Balance dalam format PSAK 27. Export ke PDF/Excel.', 'bg-rose-100 text-rose-700'],
                            ['10', 'Distribusi SHU & RAT', 'Akhir tahun: menu **SHU → Hitung Distribusi**. Sistem hitung SHU per anggota berdasar jasa modal + jasa usaha sesuai AD/ART. 1 klik export slip SHU per anggota & kirim via WhatsApp masing-masing untuk RAT.', 'bg-violet-100 text-violet-700'],
                        ] as $step)
                            <div class="flex gap-4 group">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-xl {{ $step[3] }} flex items-center justify-center font-extrabold text-base">{{ $step[0] }}</div>
                                </div>
                                <div class="flex-1 pt-1">
                                    <h4 class="font-bold text-slate-900 mb-1">{{ $step[1] }}</h4>
                                    <p class="text-sm text-slate-600 leading-relaxed">{!! preg_replace('/`([^`]+)`/', '<code class="bg-slate-100 text-indigo-700 px-1.5 py-0.5 rounded text-xs font-mono">$1</code>', preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $step[2])) !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3 items-start">
                        <span class="text-amber-600 text-xl flex-shrink-0">💡</span>
                        <div class="text-sm">
                            <strong class="text-amber-900">Tip:</strong> <span class="text-amber-800">Tim support kami menyediakan <strong>2 sesi training online (90 menit)</strong> untuk pengurus koperasi gratis bagi pelanggan baru. Hubungi WA <strong>0812-9605-2010</strong> setelah aktivasi untuk jadwalkan.</span>
                        </div>
                    </div>
                </div>

                {{-- TAB: Fitur Lengkap --}}
                <div id="panel-fitur" class="popup-panel hidden">
                    <h3 class="font-extrabold text-xl text-slate-900 mb-2">Fitur Lengkap (Sudah Terbangun di Source Code)</h3>
                    <p class="text-slate-600 text-sm mb-5">Daftar di bawah ini semuanya <strong>sudah ada di repository</strong> — Filament Resource, Page, Widget, Controller, dan Model siap pakai.</p>

                    <div class="grid sm:grid-cols-2 gap-3">
                        @foreach([
                            ['🏢', 'Manajemen Anggota', 'AnggotaResource: CRUD anggota, NIK, status aktif, ahli waris, riwayat status log.', 'from-indigo-500 to-purple-500'],
                            ['💰', 'Produk Simpanan', 'ProdukSimpananResource + SimpananResource: 4 jenis (Pokok, Wajib, Sukarela, Berjangka) dengan transaksi.', 'from-emerald-500 to-teal-500'],
                            ['💳', 'Produk Pinjaman', 'ProdukPinjamanResource + PinjamanResource: plafon, tenor, bunga flat/efektif/anuitas, approval, jaminan, jadwal & pembayaran.', 'from-amber-500 to-orange-500'],
                            ['🕌', '9 Akad Syariah + Konvensional', 'Murabahah, Mudharabah, Musyarakah, Ijarah, Ijarah Muntahiya Bittamlik, Qardh, Rahn (Gadai), Salam, Istishna — pilih per produk.', 'from-emerald-600 to-green-600'],
                            ['📊', 'Akuntansi & Jurnal', 'JurnalResource + JurnalDetail: jurnal umum dengan multi-line debet/kredit, posting otomatis dari transaksi.', 'from-blue-500 to-cyan-500'],
                            ['📑', 'Laporan Keuangan', 'Page LaporanKeuangan: Neraca, Laba-Rugi, Arus Kas — siap di-export.', 'from-blue-600 to-indigo-600'],
                            ['🧾', 'Chart of Accounts', 'CoaResource: hierarchy parent-child, tipe akun (Asset/Liability/Equity/Revenue/Expense), seed PSAK 27 ready.', 'from-slate-500 to-slate-700'],
                            ['🎯', 'Perhitungan SHU', 'ShuPerhitunganResource + ShuDistribusi: hitung SHU per anggota berbasis jasa modal & jasa usaha.', 'from-violet-500 to-purple-500'],
                            ['💵', 'Kas & Mutasi', 'KasResource + KasTransaksi: multi-kas/bank, transfer antar-kas, mutasi real-time.', 'from-yellow-500 to-amber-500'],
                            ['🛒', 'Toko (Inventory + POS)', 'TokoBarangResource + TokoPenjualanResource + Detail: inventory, kasir penjualan untuk Koperasi Serba Usaha.', 'from-pink-500 to-rose-500'],
                            ['👥', 'Karyawan', 'KaryawanResource: data karyawan koperasi (untuk Kopkar/staff koperasi).', 'from-cyan-500 to-blue-500'],
                            ['📦', 'Manajemen Aset', 'AssetResource: daftar aset tetap koperasi.', 'from-stone-500 to-stone-700'],
                            ['📈', '6 Dashboard Widget', 'StatsKoperasi, KolektabilitasChart, TrenSimpananChart, AktivitasTerbaru, TopAnggotaTable, HeroBanner.', 'from-fuchsia-500 to-pink-500'],
                            ['📱', 'Portal Anggota', 'Route /portal: login NIK + password, dashboard saldo simpanan, riwayat pinjaman & cicilan, mobile-friendly.', 'from-indigo-600 to-blue-600'],
                            ['🧾', 'Tagihan', 'TagihanResource + TagihanMaster: master tagihan rutin (iuran bulanan, dll).', 'from-rose-500 to-red-500'],
                            ['🤝', 'Unit Jasa & Produsen', 'UnitJasaOrderResource + UnitProdusenSetoranResource: order jasa anggota & setoran produsen.', 'from-teal-500 to-emerald-500'],
                            ['🔐', 'Multi-User & Role', 'UserResource + Spatie Permission: role super-admin, admin, kasir, accounting, dll.', 'from-red-500 to-pink-500'],
                            ['🏢', 'Multi-Tenant', 'TenantResource + ResolveTenantFromUser middleware: 1 instalasi banyak koperasi.', 'from-purple-600 to-indigo-600'],
                            ['📅', 'RAT Module', 'RatResource: data Rapat Anggota Tahunan dengan periode tutup buku.', 'from-orange-500 to-red-500'],
                            ['🔒', 'License Locking', 'LicenseClient + RequirePair middleware: domain-locked .license.lock, auto-redirect ke /__pair jika belum aktif.', 'from-zinc-600 to-slate-700'],
                            ['🌐', 'pSEO Built-in (70+ pages)', 'ProgrammaticSeoController: per kota, jenis koperasi, akad, panduan, kalkulator. Auto-sitemap.xml.', 'from-sky-500 to-blue-500'],
                            ['🧮', 'Kalkulator Interaktif', '4 kalkulator JavaScript: cicilan pinjaman, bagi hasil syariah, SHU anggota, simpanan berjangka.', 'from-teal-500 to-cyan-500'],
                            ['💾', 'Backup (Spatie)', 'Package spatie/laravel-backup terpasang — tinggal aktifkan schedule untuk daily backup ke disk/cloud.', 'from-gray-500 to-slate-600'],
                            ['👤', 'Cabang Koperasi', 'Model Cabang: dukungan multi-cabang per tenant.', 'from-amber-600 to-orange-600'],
                        ] as $f)
                            <div class="bg-white border border-slate-200 rounded-xl p-3 hover:border-indigo-300 hover:shadow-md transition group">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $f[3] }} flex items-center justify-center text-lg flex-shrink-0">{{ $f[0] }}</div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-sm text-slate-900 mb-0.5">{{ $f[1] }}</h4>
                                        <p class="text-xs text-slate-600 leading-snug">{{ $f[2] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl">
                        <p class="text-sm text-amber-900 leading-relaxed"><strong class="font-bold">⚠️ Belum termasuk (perlu integrasi tambahan):</strong> Notifikasi WhatsApp, Cetak struk thermal, QR Code kartu anggota, Import CSV anggota mass, Export laporan PDF custom layout. Bisa di-request sebagai add-on saat pembelian.</p>
                    </div>

                    <div class="mt-3 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-xl text-center">
                        <p class="text-sm text-slate-700"><strong class="text-indigo-700">19 Filament Resources · 6 Widgets · 2 Custom Pages · 7 pSEO Routes</strong> — semua confirmed ada di codebase.</p>
                    </div>
                </div>

            </div>

            <div class="border-t border-slate-200 px-7 py-4 flex items-center justify-between gap-3 bg-slate-50 flex-shrink-0">
                <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                    <input type="checkbox" id="dont-show-again" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Jangan tampilkan lagi</span>
                </label>
                <div class="flex gap-2">
                    <button onclick="closeWelcomePopup()" class="text-slate-600 hover:text-slate-900 font-medium text-sm px-4 py-2 rounded-lg hover:bg-white transition">Nanti saja</button>
                    <a href="https://wa.me/6281296052010?text=Halo,%20saya%20mau%20info%20aplikasi%20koperasi" class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold text-sm px-5 py-2 rounded-lg shadow-lg shadow-emerald-500/30 transition">Chat WA: 0812-9605-2010 →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Floating Help Button (re-open popup) --}}
    <button onclick="openWelcomePopup()" class="fixed bottom-6 right-6 z-[150] w-14 h-14 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 text-white font-bold shadow-2xl shadow-indigo-500/40 hover:scale-110 transition flex items-center justify-center group" aria-label="Buka panduan">
        <svg class="w-6 h-6 group-hover:rotate-12 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093M12 17h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="absolute -top-1 -right-1 w-3 h-3 bg-pink-500 rounded-full animate-ping"></span>
        <span class="absolute -top-1 -right-1 w-3 h-3 bg-pink-500 rounded-full"></span>
    </button>

    <style>
        .popup-tab-active { color: #4f46e5; border-bottom-color: #4f46e5; background: linear-gradient(to bottom, transparent, rgba(99,102,241,0.05)); }
        #welcome-popup.show { display: flex; animation: popup-fade 0.25s ease-out; }
        #welcome-popup.show #popup-card { animation: popup-rise 0.35s cubic-bezier(0.34, 1.56, 0.64, 1); }
        @keyframes popup-fade { from { opacity: 0; } to { opacity: 1; } }
        @keyframes popup-rise { from { transform: translateY(30px) scale(0.96); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }
    </style>

    <script>
        function openWelcomePopup() {
            const p = document.getElementById('welcome-popup');
            p.classList.add('show');
            p.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeWelcomePopup() {
            const p = document.getElementById('welcome-popup');
            p.classList.remove('show');
            p.classList.add('hidden');
            document.body.style.overflow = '';
            if (document.getElementById('dont-show-again').checked) {
                localStorage.setItem('koperasi_popup_dismissed', '1');
            }
            sessionStorage.setItem('koperasi_popup_seen', '1');
        }
        function switchTab(name) {
            ['beli','cara','fitur'].forEach(n => {
                document.getElementById('tab-' + n).classList.remove('popup-tab-active');
                document.getElementById('tab-' + n).classList.add('text-slate-500','border-transparent');
                document.getElementById('panel-' + n).classList.add('hidden');
            });
            document.getElementById('tab-' + name).classList.add('popup-tab-active');
            document.getElementById('tab-' + name).classList.remove('text-slate-500','border-transparent');
            document.getElementById('panel-' + name).classList.remove('hidden');
        }
        function copyPhone() {
            navigator.clipboard.writeText('081296052010').then(() => {
                const btn = document.getElementById('copy-btn');
                const orig = btn.textContent;
                btn.textContent = '✓ Tersalin';
                btn.classList.add('bg-emerald-500','text-white');
                setTimeout(() => { btn.textContent = orig; btn.classList.remove('bg-emerald-500','text-white'); }, 1800);
            });
        }
        // Auto-show on first visit (skip if dismissed permanently or already seen this session)
        document.addEventListener('DOMContentLoaded', () => {
            const dismissed = localStorage.getItem('koperasi_popup_dismissed');
            const seen = sessionStorage.getItem('koperasi_popup_seen');
            if (!dismissed && !seen) {
                setTimeout(openWelcomePopup, 1200);
            }
        });
        // Close on backdrop click + Esc
        document.getElementById('welcome-popup').addEventListener('click', (e) => {
            if (e.target.id === 'welcome-popup') closeWelcomePopup();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.getElementById('welcome-popup').classList.contains('show')) closeWelcomePopup();
        });
    </script>

    {{-- ========== FLOATING WHATSAPP CTA ========== --}}
    <div x-data="{ open: false, dismissed: sessionStorage.getItem('koperasi_purchase_dismissed') }"
         x-init="if (!dismissed) { setTimeout(() => { open = true }, 25000); }"
         class="relative z-50">
        {{-- Floating WhatsApp Button --}}
        <a href="https://wa.me/6281296052010?text=Halo%2C+saya+tertarik+dengan+source+code+aplikasi+koperasi"
           target="_blank"
           class="fixed bottom-6 right-6 w-16 h-16 bg-[#25D366] text-white rounded-full shadow-xl hover:shadow-2xl hover:scale-110 transition-all duration-300 flex items-center justify-center z-40 pulse-wa">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
        </a>
        <span class="fixed bottom-7 right-24 w-3 h-3 bg-red-500 rounded-full animate-ping z-50"></span>
        <span class="fixed bottom-7 right-24 w-3 h-3 bg-red-500 rounded-full z-50"></span>

        {{-- Delayed Purchase Popup --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
             class="fixed bottom-24 right-6 max-w-sm bg-white rounded-2xl shadow-2xl border border-stone-200 p-5 z-30"
             @click.away="open = false">
            <button @click="open = false; sessionStorage.setItem('koperasi_purchase_dismissed', '1')"
                    class="absolute top-3 right-3 text-stone-400 hover:text-stone-600 text-lg leading-none">&times;</button>
            <div class="flex items-start gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm shrink-0">K</div>
                <div>
                    <h4 class="font-bold text-stone-900 text-sm">Punya Source Code Sendiri</h4>
                    <p class="text-stone-500 text-xs mt-0.5">Rebrand, hosting sendiri, jual ke koperasi lain.</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ url('/docs') }}" class="flex-1 text-center py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-sm font-semibold transition">Dokumentasi</a>
                <a href="https://wa.me/6281296052010?text=Halo%2C+saya+ingin+tanya+tentang+beli+source+code+aplikasi+koperasi"
                   target="_blank"
                   class="flex-1 text-center py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition"
                   @click="open = false; sessionStorage.setItem('koperasi_purchase_dismissed', '1')">WhatsApp</a>
            </div>
            <p class="text-stone-400 text-xs text-center mt-2">Muncul otomatis, 1x per sesi</p>
        </div>
    </div>

    <style>
        @keyframes pulse-wa { 0%,100% { box-shadow: 0 0 0 0 rgba(37,211,102,.5); } 70% { box-shadow: 0 0 0 16px rgba(37,211,102,0); } }
        .pulse-wa { animation: pulse-wa 2s infinite; }
    </style>
</body>
</html>
