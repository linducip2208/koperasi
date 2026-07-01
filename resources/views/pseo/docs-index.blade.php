<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi & Demo — KoperasiApp</title>
    <meta name="description" content="Akses demo KoperasiApp, ikuti panduan 45 langkah, dan lihat semua 41 modul dengan screenshot.">
    <link rel="canonical" href="{{ url('/docs') }}">
    <meta property="og:title" content="Dokumentasi & Demo — KoperasiApp">
    <meta property="og:description" content="Software koperasi simpan pinjam + unit usaha. 41 modul, 8 role, multi-cabang.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','system-ui','sans-serif']}}}}</script>
</head>
<body class="bg-zinc-50 text-zinc-900 font-sans antialiased">

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-zinc-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
        <a href="/" class="font-extrabold text-lg text-emerald-700">KoperasiApp</a>
        <a href="/admin/login" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 shadow-md">Login Admin Panel</a>
    </div>
</header>

<div class="sticky top-[57px] z-40 bg-white/95 backdrop-blur-md border-b border-zinc-200 overflow-x-auto shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2.5 flex gap-5 text-xs font-semibold whitespace-nowrap">
        <a href="#akun-demo" class="text-emerald-600">Akun Demo</a>
        <a href="#tutorial" class="text-zinc-500 hover:text-emerald-600">Tutorial 45 Langkah</a>
        <a href="#menu" class="text-zinc-500 hover:text-emerald-600">Struktur Menu</a>
        <a href="#fitur" class="text-zinc-500 hover:text-emerald-600">Fitur Lengkap</a>
        <a href="#cepat" class="text-zinc-500 hover:text-emerald-600">Panduan Cepat</a>
    </div>
</div>

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-12 space-y-16">

<section class="text-center">
    <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-4 py-1.5 rounded-full">Demo & Dokumentasi</span>
    <h1 class="mt-4 text-3xl sm:text-5xl font-extrabold text-zinc-900 tracking-tight">KoperasiApp — Demo & Dokumentasi Lengkap</h1>
    <p class="mt-4 text-zinc-500 max-w-3xl mx-auto text-lg">Jelajahi 41 modul operasional koperasi — dari keanggotaan, simpan-pinjam, toko & POS, akuntansi double-entry, SHU otomatis, hingga laporan ODS Kemenkop. 8 role siap pakai.</p>
    <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto">
        <div class="bg-white rounded-xl p-4 ring-1 ring-zinc-200"><div class="text-2xl font-extrabold text-emerald-600">41</div><div class="text-xs text-zinc-500 mt-1">Modul</div></div>
        <div class="bg-white rounded-xl p-4 ring-1 ring-zinc-200"><div class="text-2xl font-extrabold text-emerald-600">8</div><div class="text-xs text-zinc-500 mt-1">Role</div></div>
        <div class="bg-white rounded-xl p-4 ring-1 ring-zinc-200"><div class="text-2xl font-extrabold text-emerald-600">45</div><div class="text-xs text-zinc-500 mt-1">Langkah Tutorial</div></div>
        <div class="bg-white rounded-xl p-4 ring-1 ring-zinc-200"><div class="text-2xl font-extrabold text-emerald-600">150+</div><div class="text-xs text-zinc-500 mt-1">Permission</div></div>
    </div>
</section>

<section id="akun-demo" class="scroll-mt-32">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-extrabold text-zinc-900">Akun Demo</h2>
        <p class="mt-2 text-zinc-500">
            Admin: <a href="/admin/login" class="text-emerald-600 font-semibold hover:underline">/admin/login</a>
            &nbsp;·&nbsp;
            Anggota: <a href="/portal/login" class="text-emerald-600 font-semibold hover:underline">/portal/login</a>
            <br><strong>Semua password admin: admin123</strong> · Anggota: anggota123
        </p>
    </div>
    <div class="overflow-x-auto rounded-xl ring-1 ring-zinc-200 shadow-sm bg-white">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-zinc-50 text-left">
                    <th class="px-4 py-3 font-semibold text-zinc-700">Role</th>
                    <th class="px-4 py-3 font-semibold text-zinc-700">Email</th>
                    <th class="px-4 py-3 font-semibold text-zinc-700">Password</th>
                    <th class="px-4 py-3 font-semibold text-zinc-700 hidden sm:table-cell">Cakupan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @foreach ($accounts as $acc)
                <tr class="hover:bg-emerald-50/50 transition">
                    <td class="px-4 py-3 font-semibold text-zinc-800">{{ $acc['role'] }}</td>
                    <td class="px-4 py-3 font-mono text-sm text-zinc-600">{{ $acc['email'] }}</td>
                    <td class="px-4 py-3 font-mono text-sm text-zinc-600">{{ $acc['password'] }}</td>
                    <td class="px-4 py-3 text-xs text-zinc-500 hidden sm:table-cell">{{ $acc['scope'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<section id="tutorial" class="scroll-mt-32">
    <div class="text-center mb-8">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">45 Langkah</span>
        <h2 class="mt-3 text-2xl font-extrabold text-zinc-900">Tutorial Langkah Demi Langkah</h2>
        <p class="mt-2 text-zinc-500 max-w-3xl mx-auto">9 fase mencakup seluruh alur bisnis koperasi — dari setup awal sampai laporan dan monitoring.</p>
    </div>
    @foreach ($tutorial as $phase)
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="shrink-0 w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-lg">{{ $loop->iteration }}</div>
            <h3 class="text-lg font-bold text-zinc-900">{{ $phase['phase'] }}</h3>
            <span class="text-xs text-zinc-400">{{ count($phase['steps']) }} langkah</span>
        </div>
        <div class="bg-white rounded-2xl ring-1 ring-zinc-200 shadow-sm overflow-hidden ml-0 sm:ml-13">
            @foreach ($phase['steps'] as $step)
            <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100 last:border-0">
                <div class="flex gap-3 items-start">
                    <div class="shrink-0 w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs mt-0.5">{{ $loop->iteration }}</div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-zinc-800 text-sm">{{ $step['title'] }}</h4>
                        <p class="mt-1 text-xs text-zinc-500 leading-relaxed">{{ $step['detail'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</section>

<section id="menu" class="scroll-mt-32">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-extrabold text-zinc-900">Struktur Menu Admin</h2>
        <p class="mt-2 text-zinc-500">9 navigation group — 41 modul disusun mengikuti alur bisnis koperasi.</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($menu as $group)
        <div class="bg-white rounded-xl ring-1 ring-zinc-200 shadow-sm p-5 hover:shadow-md transition">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xl">{{ $group['icon'] }}</span>
                <h3 class="font-bold text-zinc-900 text-sm">{{ $group['group'] }}</h3>
                <span class="text-xs text-zinc-400 ml-auto">{{ count($group['items']) }}</span>
            </div>
            <ul class="space-y-2">
                @foreach ($group['items'] as $item)
                <li class="text-xs text-zinc-600 leading-relaxed">
                    <span class="font-semibold text-zinc-800">{{ $item['name'] }}</span>
                    <span class="text-zinc-400"> — {{ $item['desc'] }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>
</section>

<section id="fitur" class="scroll-mt-32">
    <div class="text-center mb-8">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Fitur Lengkap</span>
        <h2 class="mt-3 text-2xl font-extrabold text-zinc-900">Eksplorasi Fitur KoperasiApp</h2>
        <p class="mt-2 text-zinc-500 max-w-3xl mx-auto">Setiap fitur dilengkapi screenshot dari aplikasi yang berjalan.</p>
    </div>
    <div class="space-y-12">
        @foreach ($features as $ft)
        <div class="flex flex-col lg:flex-row gap-6 items-center">
            <div class="flex-1 w-full">
                <div class="relative rounded-xl overflow-hidden ring-1 ring-zinc-200 shadow-lg bg-white">
                    <div class="flex items-center gap-1.5 px-4 py-2.5 bg-zinc-100 border-b border-zinc-200">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span class="w-3 h-3 rounded-full bg-green-400"></span>
                        <span class="ml-3 text-[10px] text-zinc-400 font-mono truncate">admin/koperasi/{{ Str::slug($ft['group']) }}</span>
                    </div>
                    @php $imgPath = 'marketing/screens/' . $ft['screenshot']; @endphp
                    @if(file_exists(public_path($imgPath)))
                    <img src="{{ asset($imgPath) }}" alt="{{ $ft['title'] }}" class="w-full h-auto" loading="lazy">
                    @else
                    <div class="aspect-video bg-gradient-to-br from-emerald-50 to-white flex items-center justify-center p-6">
                        <div class="text-center">
                            <span class="text-sm text-zinc-400">Screenshot: {{ $ft['screenshot'] }}</span>
                            <span class="text-xs text-emerald-500 mt-1 block">(jalankan node scripts/screenshot.cjs)</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="flex-1 w-full">
                <span class="text-[11px] font-semibold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">{{ $ft['group'] }}</span>
                <h3 class="mt-3 text-xl font-extrabold text-zinc-900">{{ $ft['title'] }}</h3>
                <p class="mt-3 text-sm text-zinc-500 leading-relaxed">{{ $ft['description'] }}</p>
                <ul class="mt-4 space-y-2">
                    @foreach ($ft['bullets'] as $bullet)
                    <li class="flex items-start gap-2 text-sm text-zinc-600">
                        <span class="text-emerald-500 mt-1 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        {{ $bullet }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endforeach
    </div>
</section>

<section id="cepat" class="scroll-mt-32">
    <div class="text-center mb-8">
        <span class="text-xs font-bold uppercase tracking-widest text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Alur Bisnis</span>
        <h2 class="mt-3 text-2xl font-extrabold text-zinc-900">Panduan Cepat: Dari Setup ke Laporan</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl p-5 text-center ring-1 ring-zinc-200">
            <div class="inline-flex w-10 h-10 rounded-full bg-emerald-600 text-white items-center justify-center font-extrabold text-sm mb-3">1</div>
            <h3 class="font-bold text-zinc-900 text-sm">Setup Data Master</h3>
            <p class="text-xs text-zinc-500 mt-1">Tenant, COA, Kas, Anggota, Produk</p>
        </div>
        <div class="bg-white rounded-xl p-5 text-center ring-1 ring-zinc-200">
            <div class="inline-flex w-10 h-10 rounded-full bg-blue-600 text-white items-center justify-center font-extrabold text-sm mb-3">2</div>
            <h3 class="font-bold text-zinc-900 text-sm">Transaksi Simpanan</h3>
            <p class="text-xs text-zinc-500 mt-1">Setor, Tarik, Transfer, Blokir</p>
        </div>
        <div class="bg-white rounded-xl p-5 text-center ring-1 ring-zinc-200">
            <div class="inline-flex w-10 h-10 rounded-full bg-amber-600 text-white items-center justify-center font-extrabold text-sm mb-3">3</div>
            <h3 class="font-bold text-zinc-900 text-sm">Transaksi Pinjaman</h3>
            <p class="text-xs text-zinc-500 mt-1">Pengajuan, Approval, Akad, Bayar</p>
        </div>
        <div class="bg-white rounded-xl p-5 text-center ring-1 ring-zinc-200">
            <div class="inline-flex w-10 h-10 rounded-full bg-violet-600 text-white items-center justify-center font-extrabold text-sm mb-3">4</div>
            <h3 class="font-bold text-zinc-900 text-sm">Akuntansi & Laporan</h3>
            <p class="text-xs text-zinc-500 mt-1">Jurnal, Kas, Rekonsiliasi, SHU</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl ring-1 ring-zinc-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100"><div class="flex gap-3 items-start"><div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-sm">A</div><div class="flex-1 min-w-0"><h4 class="font-bold text-zinc-900 text-sm">Setup Tenant & COA</h4><div class="mt-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded">Pengaturan → Tenant + Akuntansi → COA</div><p class="mt-1 text-xs text-zinc-500">Isi data koperasi. Seeder sediakan ~75 COA standar. Tambahkan akun khusus jika perlu.</p></div></div></div>
        <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100"><div class="flex gap-3 items-start"><div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-sm">B</div><div class="flex-1 min-w-0"><h4 class="font-bold text-zinc-900 text-sm">Buat Kas & Bank</h4><div class="mt-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded">Akuntansi → Kas → New</div><p class="mt-1 text-xs text-zinc-500">Minimal 1 kas tunai + 1 rekening bank. Set saldo awal sesuai kondisi riil.</p></div></div></div>
        <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100"><div class="flex gap-3 items-start"><div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-sm">C</div><div class="flex-1 min-w-0"><h4 class="font-bold text-zinc-900 text-sm">Daftarkan Anggota</h4><div class="mt-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded">Keanggotaan → Anggota → New</div><p class="mt-1 text-xs text-zinc-500">Input NIK, nama, alamat, pekerjaan. Upload KTP + selfie. Isi tab Ahli Waris.</p></div></div></div>
        <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100"><div class="flex gap-3 items-start"><div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-sm">D</div><div class="flex-1 min-w-0"><h4 class="font-bold text-zinc-900 text-sm">Setup Produk Simpanan & Pinjaman</h4><div class="mt-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded">Simpan Pinjam → Produk Simpanan / Produk Pinjaman</div><p class="mt-1 text-xs text-zinc-500">6 produk simpanan + 9 produk pinjaman. Edit bunga, margin, tenor sesuai kebijakan.</p></div></div></div>
        <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100"><div class="flex gap-3 items-start"><div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-sm">E</div><div class="flex-1 min-w-0"><h4 class="font-bold text-zinc-900 text-sm">Setor Simpanan Anggota</h4><div class="mt-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded">Simpan Pinjam → Simpanan → Action: Setor</div><p class="mt-1 text-xs text-zinc-500">Setor pokok + wajib. Auto-jurnal: Kas Debit, Simpanan Kredit. Cetak kuitansi PDF.</p></div></div></div>
        <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100"><div class="flex gap-3 items-start"><div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-sm">F</div><div class="flex-1 min-w-0"><h4 class="font-bold text-zinc-900 text-sm">Proses Pinjaman Anggota</h4><div class="mt-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded">Simpan Pinjam → Pinjaman → New</div><p class="mt-1 text-xs text-zinc-500">Pilih anggota + produk. 9 kalkulator auto-hitung. Upload jaminan. Submit → Approve → Akad → Cairkan.</p></div></div></div>
        <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100"><div class="flex gap-3 items-start"><div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-sm">G</div><div class="flex-1 min-w-0"><h4 class="font-bold text-zinc-900 text-sm">Pembayaran Angsuran</h4><div class="mt-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded">Pinjaman → Action: Bayar</div><p class="mt-1 text-xs text-zinc-500">Auto-alokasi pokok, bunga, denda. Cetak slip PDF. Reminder WhatsApp otomatis.</p></div></div></div>
        <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100"><div class="flex gap-3 items-start"><div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-sm">H</div><div class="flex-1 min-w-0"><h4 class="font-bold text-zinc-900 text-sm">Penjualan Toko (POS)</h4><div class="mt-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded">Toko & Unit Usaha → Toko Penjualan → New</div><p class="mt-1 text-xs text-zinc-500">Repeater barang, 6 metode bayar, auto-kembalian, struk thermal 58mm/80mm.</p></div></div></div>
        <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100"><div class="flex gap-3 items-start"><div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-sm">I</div><div class="flex-1 min-w-0"><h4 class="font-bold text-zinc-900 text-sm">Jurnal & Kas Opname</h4><div class="mt-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded">Akuntansi → Jurnal / Kas Opname</div><p class="mt-1 text-xs text-zinc-500">Jurnal manual double-entry. Cocok fisik kas vs sistem. Auto-jurnal selisih.</p></div></div></div>
        <div class="px-5 py-3.5 hover:bg-zinc-50 transition border-b border-zinc-100"><div class="flex gap-3 items-start"><div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-extrabold text-sm">J</div><div class="flex-1 min-w-0"><h4 class="font-bold text-zinc-900 text-sm">SHU, RAT & Laporan</h4><div class="mt-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded">SHU → SHU Perhitungan + Laporan</div><p class="mt-1 text-xs text-zinc-500">Hitung SHU → distribusi. Catat RAT. Generate laporan PDF untuk Dinas Koperasi.</p></div></div></div>
    </div>
</section>

<section class="py-8">
    <div class="bg-gradient-to-br from-emerald-700 to-emerald-900 rounded-3xl p-8 sm:p-14 text-center text-white shadow-2xl shadow-emerald-500/20">
        <h2 class="text-2xl sm:text-4xl font-extrabold">Siap Kelola Koperasi dengan Modern?</h2>
        <p class="mt-4 text-emerald-100 max-w-2xl mx-auto">Coba demo gratis — langsung login dan jelajahi semua 41 modul.</p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="/admin/login" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-white text-emerald-700 font-bold rounded-xl hover:bg-emerald-50 transition shadow-lg">Login Admin Panel</a>
            <a href="https://wa.me/6281296052010" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition ring-1 ring-white/20">WhatsApp Sales</a>
        </div>
    </div>
</section>

</main>

<footer class="bg-zinc-900 text-zinc-400 py-12 mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center text-xs">
        &copy; {{ date('Y') }} KoperasiApp — Software Koperasi Indonesia. Dibuat dengan Laravel + Filament.
    </div>
</footer>

</body>
</html>
