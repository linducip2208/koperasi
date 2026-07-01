<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pendaftaran Anggota Baru — {{ $brand['nama'] ?? config('app.name') }}</title>
<meta name="description" content="Daftar jadi anggota koperasi online. Gratis, tanpa biaya pendaftaran. Status verifikasi dalam 1-2 hari kerja.">
<meta name="robots" content="index,follow">
<link rel="canonical" href="{{ url('/daftar') }}">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-slate-50 text-slate-800">

<header class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 text-white">
    <div class="max-w-3xl mx-auto px-4 py-12">
        <a href="/" class="text-sm opacity-80 hover:opacity-100">← Beranda</a>
        <h1 class="text-3xl md:text-4xl font-extrabold mt-3">📝 Daftar Jadi Anggota Koperasi</h1>
        <p class="text-base opacity-95 mt-2">Gratis, online, tanpa biaya pendaftaran. Verifikasi 1-2 hari kerja.</p>
    </div>
</header>

<main class="max-w-3xl mx-auto px-4 py-10">

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-lg mb-6">
            <strong>Ada {{ $errors->count() }} kesalahan input:</strong>
            <ul class="list-disc pl-6 mt-2 text-sm">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ url('/daftar') }}" class="bg-white rounded-2xl shadow-md p-6 space-y-6">
        @csrf

        <fieldset class="space-y-4">
            <legend class="font-bold text-slate-900 text-base">📋 Identitas</legend>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">NIK *</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" required minlength="16" maxlength="20"
                        class="w-full mt-1 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="255"
                        class="w-full mt-1 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                        class="w-full mt-1 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                        class="w-full mt-1 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required class="w-full mt-1 px-3 py-2.5 border border-slate-300 rounded-lg">
                        <option value="">Pilih...</option>
                        <option value="L" @selected(old('jenis_kelamin')==='L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin')==='P')>Perempuan</option>
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset class="space-y-4">
            <legend class="font-bold text-slate-900 text-base">📞 Kontak</legend>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full mt-1 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">No. HP / WhatsApp *</label>
                    <input type="text" name="telp" value="{{ old('telp') }}" required maxlength="20" placeholder="08xxxxxxxxxx"
                        class="w-full mt-1 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
        </fieldset>

        <fieldset class="space-y-4">
            <legend class="font-bold text-slate-900 text-base">🏠 Alamat & Pekerjaan</legend>
            <div>
                <label class="text-xs font-bold uppercase text-slate-500">Alamat Lengkap *</label>
                <textarea name="alamat" required rows="2" class="w-full mt-1 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">{{ old('alamat') }}</textarea>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">Kota *</label>
                    <input type="text" name="kota" value="{{ old('kota') }}" required
                        class="w-full mt-1 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                        class="w-full mt-1 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500">Penghasilan Bulanan</label>
                    <div class="relative mt-1">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">Rp</span>
                        <input type="number" name="penghasilan_bulanan" value="{{ old('penghasilan_bulanan') }}" min="0" step="100000"
                            class="w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>
        </fieldset>

        <div class="bg-amber-50 border border-amber-200 p-4 rounded-lg text-sm text-amber-800">
            <strong>⚠ Penting:</strong> Setelah submit, status Anda <strong>"Calon Anggota"</strong>. Admin koperasi akan verifikasi dokumen & meng-approve dalam 1-2 hari kerja. Anda akan menerima notifikasi WhatsApp dengan password sementara untuk login portal.
        </div>

        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg shadow-md transition">
            🚀 Daftar Sekarang
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        Sudah jadi anggota? <a href="/portal/login" class="text-emerald-600 font-semibold hover:underline">Login di sini</a>
    </p>
</main>

</body>
</html>
