<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Check-in RAT {{ $rat->tahun_buku }}</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-emerald-50 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
    <div class="text-center mb-6">
        <div class="text-4xl mb-2">📋</div>
        <h1 class="text-xl font-extrabold text-stone-800">Check-in RAT Tahun Buku {{ $rat->tahun_buku }}</h1>
        <p class="text-sm text-stone-500">{{ \Carbon\Carbon::parse($rat->tanggal)->format('d M Y') }} @if($rat->lokasi) • {{ $rat->lokasi }} @endif</p>
    </div>

    <div class="bg-stone-50 border rounded-xl p-4 mb-6 text-center">
        <div class="text-3xl font-extrabold {{ $rat->quorum_tercapai ? 'text-emerald-600' : 'text-amber-600' }}">{{ $rat->jumlah_hadir }} / {{ $rat->jumlah_anggota_terdaftar }}</div>
        <div class="text-xs text-stone-500 mt-1">Kehadiran {{ $persen }}% (minimal {{ $rat->quorum_persen }}%)</div>
        <div class="mt-2">
            @if($rat->quorum_tercapai)
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">✅ QUORUM TERCAPAI</span>
            @else
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">⏳ BELUM QUORUM</span>
            @endif
        </div>
    </div>

    @if(session('flash'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-800 font-semibold">{{ session('flash') }}</div>
    @endif
    @error('nomor_anggota')
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ url()->current() }}">
        @csrf
        <label class="text-sm font-bold text-stone-700">Nomor Anggota / NIK</label>
        <input type="text" name="nomor_anggota" required autofocus placeholder="cth: AGT-000123"
            class="mt-1 w-full border border-stone-300 rounded-xl px-4 py-3 text-lg font-mono focus:ring-2 focus:ring-emerald-500 outline-none">
        <button class="mt-4 w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl transition">
            ✅ Catat Kehadiran
        </button>
    </form>
    <p class="text-[11px] text-stone-400 text-center mt-4">Panitia: gunakan halaman ini di pintu masuk (scan QR → input nomor anggota).</p>
</div>
</body>
</html>
