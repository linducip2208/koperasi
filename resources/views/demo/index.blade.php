<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Demo — {{ config('product.name') }}</title>
<meta name="robots" content="noindex, nofollow">
@vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8">
<div class="max-w-5xl mx-auto">
    <div class="text-center mb-8">
        <div class="text-4xl mb-2">🖥️</div>
        <h1 class="text-2xl font-extrabold text-slate-800">Demo {{ config('product.name') }}</h1>
        <p class="text-sm text-slate-500">Jelajahi fitur, menu, dan akun peran. Kredensial demo diberikan oleh sales setelah request demo.</p>
        <div class="flex gap-2 justify-center mt-4">
            <a href="https://wa.me/{{ config('support.whatsapp') }}?text=Halo,%20saya%20mau%20request%20demo" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm">Request Demo →</a>
            <a href="/" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-sm">Beranda</a>
        </div>
    </div>

    <h2 class="font-extrabold text-slate-800 mb-3">Akun Peran</h2>
    <div class="bg-white rounded-2xl shadow overflow-hidden mb-8">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs uppercase text-slate-500 border-b">
                <th class="px-4 py-3">Peran</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Cakupan</th>
            </tr></thead>
            <tbody>
                @foreach($accounts as $a)
                    <tr class="border-b border-slate-100 last:border-0">
                        <td class="px-4 py-2.5 font-bold">{{ $a['role'] }}</td>
                        <td class="px-4 py-2.5 font-mono text-xs">{{ $a['email'] }}</td>
                        <td class="px-4 py-2.5 text-slate-600">{{ $a['scope'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="font-extrabold text-slate-800 mb-3">Struktur Menu</h2>
    <div class="grid md:grid-cols-2 gap-4 mb-8">
        @foreach($menu as $g)
            <div class="bg-white rounded-2xl shadow p-5">
                <div class="font-extrabold mb-2">{{ $g['group'] }}</div>
                <ul class="text-sm text-slate-600 space-y-1.5">
                    @foreach($g['items'] as $it)
                        <li><strong class="text-slate-800">{{ $it['name'] }}</strong> — {{ $it['desc'] }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <h2 class="font-extrabold text-slate-800 mb-3">Fitur Unggulan</h2>
    <div class="grid md:grid-cols-3 gap-4 mb-8">
        @foreach($features as $f)
            <div class="bg-white rounded-2xl shadow p-5">
                <div class="font-extrabold">{{ $f['name'] ?? $f['title'] ?? 'Fitur' }}</div>
                <p class="text-sm text-slate-600 mt-1">{{ $f['desc'] ?? '' }}</p>
            </div>
        @endforeach
    </div>

    <h2 class="font-extrabold text-slate-800 mb-3">Tutorial Singkat</h2>
    <div class="bg-white rounded-2xl shadow p-5 mb-8 text-sm text-slate-600 space-y-2">
        @foreach($tutorial as $t)
            <div><strong class="text-slate-800">{{ $t['title'] ?? $t['step'] ?? '' }}</strong> — {{ $t['desc'] ?? $t['detail'] ?? '' }}</div>
        @endforeach
    </div>
</div>
</body>
</html>
