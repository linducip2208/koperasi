<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Lisensi — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-emerald-50 to-teal-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-lg w-full">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Aktivasi Lisensi</h1>
            <p class="text-slate-600 mt-1">{{ config('app.name') }}</p>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') ?? $errors->first() }}
            </div>
        @endif

        <div class="bg-slate-50 rounded-lg p-4 mb-6 text-sm">
            <div class="flex justify-between mb-2">
                <span class="text-slate-600">Domain:</span>
                <span class="font-mono font-semibold">{{ $domain }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Status:</span>
                <span class="font-semibold {{ $valid ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ $valid ? 'Aktif & Valid' : ($activated ? 'Belum Tervalidasi' : 'Belum Aktivasi') }}
                </span>
            </div>
        </div>

        @if (! $valid)
            <form action="{{ route('activation.activate') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Activation Key</label>
                    <input type="text" name="activation_key" required minlength="10" maxlength="64"
                           placeholder="XXXXX-XXXXX-XXXXX-XXXXX"
                           value="{{ old('activation_key') }}"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono">
                </div>
                <button type="submit" class="w-full bg-emerald-600 text-white py-2.5 rounded-lg font-semibold hover:bg-emerald-700 transition">
                    Aktivasi Sekarang
                </button>
            </form>
        @else
            <div class="space-y-3">
                <a href="{{ url('/admin') }}" class="block w-full bg-emerald-600 text-white py-2.5 rounded-lg font-semibold hover:bg-emerald-700 transition text-center">
                    Masuk ke Admin Panel
                </a>
                <form action="{{ route('activation.revoke') }}" method="POST"
                      onsubmit="return confirm('Yakin revoke lisensi domain ini?');">
                    @csrf
                    <button type="submit" class="w-full bg-slate-100 text-slate-700 py-2.5 rounded-lg font-semibold hover:bg-slate-200 transition">
                        Revoke Lisensi (untuk migrasi server)
                    </button>
                </form>
            </div>
        @endif

        <div class="mt-6 pt-6 border-t border-slate-200 text-center text-xs text-slate-500">
            Butuh bantuan? Hubungi <a href="https://whitelabel.co.id" class="text-emerald-600 hover:underline">whitelabel.co.id</a>
        </div>
    </div>
</body>
</html>
