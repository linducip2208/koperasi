<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi Anggota — {{ $koperasi?->displayName() ?? config('app.name') }}</title>
@vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md text-center">
    @if($koperasi?->logo_path)
        <img src="{{ asset('storage/' . $koperasi->logo_path) }}" alt="Logo" style="max-height:56px; margin:0 auto 12px;">
    @endif
    <div class="text-4xl mb-2">{{ $anggota->status === 'aktif' ? '✅' : '⚠️' }}</div>
    <h1 class="text-xl font-extrabold text-slate-800">{{ $koperasi?->displayName() ?? config('app.name') }}</h1>
    <p class="text-sm text-slate-500 mb-6">Verifikasi keanggotaan resmi</p>
    <table class="w-full text-sm text-left">
        <tr class="border-b"><td class="py-2 text-slate-500">Nomor Anggota</td><td class="py-2 font-mono font-bold text-right">{{ $anggota->nomor_anggota }}</td></tr>
        <tr class="border-b"><td class="py-2 text-slate-500">Nama</td><td class="py-2 font-bold text-right">{{ $anggota->nama }}</td></tr>
        <tr class="border-b"><td class="py-2 text-slate-500">Status</td><td class="py-2 text-right font-bold {{ $anggota->status === 'aktif' ? 'text-emerald-600' : 'text-amber-600' }}">{{ strtoupper($anggota->status) }}</td></tr>
        <tr><td class="py-2 text-slate-500">Anggota Sejak</td><td class="py-2 text-right">{{ $anggota->tanggal_masuk?->format('M Y') ?? '-' }}</td></tr>
    </table>
    <p class="text-[11px] text-slate-400 mt-6">Halaman verifikasi resmi. Data sensitif (NIK, saldo) tidak ditampilkan di sini.</p>
</div>
</body>
</html>
