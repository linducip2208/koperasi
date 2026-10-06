<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('code', 'Error') — {{ config('product.name') }}</title>
@vite('resources/css/app.css')
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-2xl shadow-xl p-10 max-w-md w-full text-center">
    <div class="text-7xl font-extrabold text-emerald-600">@yield('code', '500')</div>
    <h1 class="text-xl font-bold text-slate-800 mt-2">@yield('title', 'Terjadi kesalahan')</h1>
    <p class="text-sm text-slate-500 mt-2">@yield('message', 'Silakan coba lagi atau hubungi administrator.')</p>
    <div class="flex gap-2 justify-center mt-6">
        <a href="/" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm">Beranda</a>
        <a href="/portal/login" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-sm">Portal Anggota</a>
    </div>
    <p class="mt-6 text-[11px] text-slate-400">{{ config('product.name') }} v{{ config('product.version') }}</p>
</div>
</body>
</html>
