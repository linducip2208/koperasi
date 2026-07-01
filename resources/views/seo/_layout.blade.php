<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seoTitle ?? 'Koperasi App' }} | {{ config('pseo.brand.nama') }}</title>
    <meta name="description" content="{{ $seoDescription ?? '' }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="{{ $seoTitle ?? '' }}">
    <meta property="og:description" content="{{ $seoDescription ?? '' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('pseo.brand.nama') }}">
    <meta property="og:locale" content="id_ID">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle ?? '' }}">
    <meta name="twitter:description" content="{{ $seoDescription ?? '' }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        h1, h2, h3 { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; letter-spacing: -0.02em; }
        .gradient-text { background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .gradient-bg { background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%); }
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(99,102,241,0.25); }
        .prose-custom p { margin-bottom: 1rem; line-height: 1.75; color: #374151; }
        .prose-custom ul { margin-bottom: 1rem; padding-left: 1.5rem; list-style-type: disc; }
        .prose-custom li { margin-bottom: 0.5rem; color: #374151; }
        .prose-custom h2 { margin-top: 2.5rem; margin-bottom: 1rem; font-size: 1.875rem; font-weight: 800; color: #111827; }
        .prose-custom h3 { margin-top: 2rem; margin-bottom: 0.75rem; font-size: 1.25rem; font-weight: 700; color: #1f2937; }
        .prose-custom strong { color: #111827; font-weight: 700; }
    </style>

    @stack('jsonld')
    @if(!empty($jsonLd ?? null))
        <script type="application/ld+json">{!! $jsonLd !!}</script>
    @endif
    @if(!empty($breadcrumbLd ?? null))
        <script type="application/ld+json">{!! $breadcrumbLd !!}</script>
    @endif
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/30 to-pink-50/20 text-slate-800 antialiased">

<nav class="glass sticky top-0 z-50 border-b border-white/40">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl gradient-bg flex items-center justify-center text-white font-extrabold shadow-lg">K</div>
            <span class="text-lg font-extrabold gradient-text">{{ config('pseo.brand.nama') }}</span>
        </a>
        <div class="flex items-center gap-1 text-sm">
            <a href="{{ url('/') }}" class="hidden md:inline-block text-slate-600 hover:text-slate-900 font-medium px-3 py-2 rounded-lg hover:bg-white/60 transition">Beranda</a>
            <a href="{{ url('/#fitur') }}" class="hidden md:inline-block text-slate-600 hover:text-slate-900 font-medium px-3 py-2 rounded-lg hover:bg-white/60 transition">Fitur</a>
            <a href="{{ url('/#harga') }}" class="hidden md:inline-block text-slate-600 hover:text-slate-900 font-medium px-3 py-2 rounded-lg hover:bg-white/60 transition">Harga</a>
            <a href="{{ route('portal.login') }}" class="text-slate-600 hover:text-slate-900 font-medium px-3 py-2 rounded-lg hover:bg-white/60 transition">Login</a>
            <a href="{{ url('/#harga') }}" class="gradient-bg text-white font-semibold px-5 py-2 rounded-lg text-sm shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 transition">Coba Gratis</a>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="mt-20 bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="col-span-2 md:col-span-1">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-xl gradient-bg flex items-center justify-center text-white font-extrabold">K</div>
                <span class="text-lg font-extrabold text-white">{{ config('pseo.brand.nama') }}</span>
            </div>
            <p class="text-sm text-slate-400">{{ config('pseo.brand.tagline') }}</p>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3 text-sm uppercase tracking-wider">Jenis Koperasi</h4>
            <ul class="space-y-2 text-sm">
                @foreach(collect(config('pseo.jenis'))->take(5) as $j)
                    <li><a href="{{ route('seo.jenis', $j['slug']) }}" class="hover:text-white transition">{{ $j['nama'] }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3 text-sm uppercase tracking-wider">Akad Syariah</h4>
            <ul class="space-y-2 text-sm">
                @foreach(collect(config('pseo.akad'))->take(5) as $a)
                    <li><a href="{{ route('seo.akad', $a['slug']) }}" class="hover:text-white transition">{{ $a['nama'] }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3 text-sm uppercase tracking-wider">Kota Populer</h4>
            <ul class="space-y-2 text-sm">
                @foreach(collect(config('pseo.kota'))->take(5) as $k)
                    <li><a href="{{ route('seo.kota', $k['slug']) }}" class="hover:text-white transition">Koperasi di {{ $k['nama'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-6 text-sm text-slate-500 text-center">
            &copy; {{ date('Y') }} {{ config('pseo.brand.nama') }} — Software Koperasi Konvensional & Syariah Indonesia.
        </div>
    </div>
</footer>

</body>
</html>
