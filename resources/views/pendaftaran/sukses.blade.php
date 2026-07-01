<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pendaftaran Berhasil — {{ $brand['nama'] ?? config('app.name') }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-emerald-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-20 h-20 mx-auto bg-emerald-100 rounded-full flex items-center justify-center text-4xl mb-4">✅</div>
        <h1 class="text-2xl font-extrabold text-slate-900">Pendaftaran Berhasil!</h1>
        <p class="text-slate-600 mt-2">Halo <strong>{{ $anggota->nama }}</strong>, terima kasih sudah mendaftar.</p>

        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mt-6 text-left text-sm space-y-1.5">
            <div><span class="text-slate-500 text-xs uppercase font-bold">Nomor Calon Anggota</span><br><span class="font-mono font-bold">{{ $anggota->nomor_anggota }}</span></div>
            <div><span class="text-slate-500 text-xs uppercase font-bold">Email</span><br><span class="font-mono">{{ $anggota->email }}</span></div>
            <div><span class="text-slate-500 text-xs uppercase font-bold">Password Sementara</span><br><span class="font-mono font-bold text-rose-600">{{ $tempPassword }}</span> <span class="text-xs text-slate-500">(simpan ini, ganti setelah login)</span></div>
            <div><span class="text-slate-500 text-xs uppercase font-bold">Status</span><br><span class="inline-block bg-amber-100 text-amber-800 px-2 py-0.5 rounded text-xs font-bold">CALON ANGGOTA</span></div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4 text-left text-sm">
            <strong>📌 Langkah selanjutnya:</strong>
            <ol class="list-decimal pl-5 mt-1.5 space-y-1">
                <li>Tunggu verifikasi admin (1-2 hari kerja)</li>
                <li>Setelah disetujui, status berubah jadi "Aktif"</li>
                <li>Setor simpanan pokok untuk aktivasi penuh</li>
                <li>Login portal: <code class="text-xs bg-slate-100 px-1.5 py-0.5 rounded">{{ url('/portal/login') }}</code></li>
            </ol>
        </div>

        <a href="https://wa.me/6281296052010?text=Halo%2C+saya+baru+saja+mendaftar+anggota+dengan+nomor+{{ urlencode($anggota->nomor_anggota) }}+atas+nama+{{ urlencode($anggota->nama) }}.+Mohon+verifikasi."
           class="block bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg mt-6 no-underline">
            💬 Konfirmasi via WhatsApp ke Admin
        </a>
        <a href="/" class="block text-slate-500 hover:text-slate-700 mt-4 text-sm">← Kembali ke Beranda</a>
    </div>
</body>
</html>
