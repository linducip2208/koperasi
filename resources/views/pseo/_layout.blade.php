{{-- pSEO shared layout. Vars: $title, $description, $canonical, $jsonLd, $breadcrumbs, $heroH1, $heroSub --}}
@php
    $title = $title ?? 'KoperasiApp';
    $description = $description ?? 'Software koperasi simpan pinjam + unit usaha terlengkap di Indonesia.';
    $canonical = $canonical ?? url()->current();
    $breadcrumbs = $breadcrumbs ?? [];
    $jsonLd = $jsonLd ?? null;
    $heroH1 = $heroH1 ?? null;
    $heroSub = $heroSub ?? null;
    $appName = 'KoperasiApp';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($description), 158) }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#059669">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($description), 158) }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:locale" content="id_ID">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($description), 158) }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body{font-family:'Inter','system-ui',sans-serif;font-feature-settings:'cv02','cv03','cv04','cv11';-webkit-font-smoothing:antialiased}
        .brand-gradient{background:linear-gradient(135deg,#059669,#10b981);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    </style>

    @if($jsonLd)
        <script type="application/ld+json">{!! $jsonLd !!}</script>
    @endif
    @stack('head')
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <header class="sticky top-0 z-40 backdrop-blur bg-white/85 border-b border-slate-200">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center gap-4">
            <a href="/" class="font-extrabold text-lg tracking-tight text-slate-900">
                <span class="brand-gradient">{{ $appName }}</span>
            </a>
            <ul class="hidden md:flex items-center gap-5 text-sm font-medium text-slate-600 ml-6">
                <li><a class="hover:text-emerald-600" href="/#fitur">Fitur</a></li>
                <li><a class="hover:text-emerald-600" href="/#syariah">Syariah</a></li>
                <li><a class="hover:text-emerald-600" href="/docs">Dokumentasi</a></li>
                <li><a class="hover:text-emerald-600" href="/#faq">FAQ</a></li>
            </ul>
            <div class="ml-auto flex items-center gap-2">
                <a href="/admin/login" class="hidden sm:inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-md text-slate-700 hover:text-emerald-700">Login Admin</a>
                <a href="/admin/login" class="inline-flex items-center px-4 py-1.5 text-sm font-semibold rounded-md text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-500/25">Coba Demo</a>
            </div>
        </nav>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-12">
        @if($heroH1)
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">{{ $heroH1 }}</h1>
            @if($heroSub)
            <p class="mt-3 text-lg text-slate-500 max-w-3xl mx-auto">{{ $heroSub }}</p>
            @endif
        </div>
        @endif

        @if(!empty($breadcrumbs))
        <nav class="flex text-sm text-slate-400 mb-8" aria-label="Breadcrumb">
            @foreach($breadcrumbs as $i => $crumb)
                @if(isset($crumb['url']))
                    <a href="{{ $crumb['url'] }}" class="hover:text-emerald-600">{{ $crumb['label'] }}</a>
                @else
                    <span class="text-slate-600">{{ $crumb['label'] }}</span>
                @endif
                @if(!$loop->last)
                    <span class="mx-2">/</span>
                @endif
            @endforeach
        </nav>
        @endif

        @yield('content')

        {{-- Source Code CTA — muncul di SEMUA halaman pSEO --}}
        @include('seo._cta')
    </main>

    <footer class="border-t border-slate-200 bg-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} {{ $appName }} — Software Koperasi Indonesia. Dibangun oleh <a href="https://whitelabel.co.id" class="text-emerald-600 hover:underline">Whitelabel.co.id</a>.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
