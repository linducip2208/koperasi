<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kartu Anggota — {{ $anggota->nama }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    @media print {
        body { background: white !important; }
        .no-print { display: none !important; }
        .kartu { box-shadow: none !important; page-break-inside: avoid; }
    }
    body { font-family: 'Inter', sans-serif; background: #f1f5f9; padding: 2rem; }
    .kartu {
        width: 540px; height: 340px;
        background: linear-gradient(135deg, #047857 0%, #0d9488 50%, #0891b2 100%);
        border-radius: 1.25rem; padding: 1.75rem; color: white;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        position: relative; overflow: hidden;
    }
    .kartu::before {
        content: ''; position: absolute; top: -100px; right: -100px;
        width: 250px; height: 250px; border-radius: 50%;
        background: rgba(255,255,255,0.1);
    }
    .kartu::after {
        content: ''; position: absolute; bottom: -80px; left: -80px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(0,0,0,0.1);
    }
</style>
</head>
<body class="flex flex-col items-center min-h-screen gap-4">

<div class="no-print w-full max-w-3xl flex justify-between items-center mb-2">
    <a href="javascript:history.back()" class="text-slate-600 hover:text-slate-900 text-sm font-semibold">← Kembali</a>
    <button onclick="window.print()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-5 py-2 rounded-lg shadow-md text-sm">🖨️ Cetak Kartu</button>
</div>

<div class="kartu relative">
    <div class="relative z-10 flex justify-between items-start">
        <div>
            <div class="text-xs font-bold uppercase tracking-[0.2em] opacity-80">Kartu Anggota</div>
            <div class="text-2xl font-extrabold mt-1">{{ config('app.name', 'Koperasi') }}</div>
        </div>
        <div class="bg-white rounded-lg p-2 shadow-lg">
            @php
                $qrData = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'portal.qr-login',
                    now()->addYears(5),
                    ['anggota' => $anggota->id]
                );
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=" . urlencode($qrData);
            @endphp
            <img src="{{ $qrUrl }}" alt="QR Login Portal" width="100" height="100" style="display: block;">
        </div>
    </div>

    <div class="relative z-10 mt-8">
        <div class="text-xs uppercase tracking-wider opacity-70 font-bold">Nama Anggota</div>
        <div class="text-xl font-extrabold tracking-tight">{{ $anggota->nama }}</div>
    </div>

    <div class="relative z-10 grid grid-cols-2 gap-3 mt-4">
        <div>
            <div class="text-[10px] uppercase tracking-wider opacity-70 font-bold">Nomor Anggota</div>
            <div class="font-mono font-bold text-sm">{{ $anggota->nomor_anggota }}</div>
        </div>
        <div>
            <div class="text-[10px] uppercase tracking-wider opacity-70 font-bold">NIK</div>
            <div class="font-mono font-bold text-sm">{{ $anggota->nik ? str_pad(substr($anggota->nik, 0, 4), 4, '0', STR_PAD_LEFT) . ' ' . str_repeat('•', 8) . ' ' . substr($anggota->nik, -4) : '-' }}</div>
        </div>
    </div>

    <div class="relative z-10 absolute bottom-5 left-7 right-7 flex justify-between items-end pt-3 border-t border-white/20">
        <div>
            <div class="text-[10px] uppercase tracking-wider opacity-70 font-bold">Tgl Masuk</div>
            <div class="text-xs font-bold">{{ $anggota->tanggal_masuk ? \Carbon\Carbon::parse($anggota->tanggal_masuk)->translatedFormat('d M Y') : '-' }}</div>
        </div>
        <div class="text-right">
            <div class="text-[10px] uppercase tracking-wider opacity-70 font-bold">Status</div>
            <div class="text-xs font-bold uppercase">{{ $anggota->status }}</div>
        </div>
    </div>
</div>

<div class="no-print mt-3 max-w-3xl text-xs text-slate-500 text-center">
    QR Code di kartu ini dapat di-scan oleh anggota untuk <strong>login otomatis ke Portal Anggota</strong> tanpa input email/password. Token aktif 5 tahun. Cetak pada kertas tebal/PVC ukuran kartu standar.
</div>

</body>
</html>
