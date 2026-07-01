<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seoTitle }} | {{ $brand['nama'] }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $brand['nama'] }}">
    <meta property="og:locale" content="id_ID">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .gradient-text { background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .gradient-bg { background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%); }
        .gradient-bg-dark { background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%); }
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(5,150,105,0.2); }
        .prose-custom p { margin-bottom: 1.25rem; line-height: 1.85; color: #374151; }
        .prose-custom ul { margin-bottom: 1.25rem; padding-left: 1.5rem; list-style-type: disc; }
        .prose-custom li { margin-bottom: 0.5rem; color: #374151; line-height: 1.7; }
        .prose-custom h2 { margin-top: 2.5rem; margin-bottom: 1rem; font-size: 1.75rem; font-weight: 800; color: #111827; }
        .prose-custom h3 { margin-top: 2rem; margin-bottom: 0.75rem; font-size: 1.25rem; font-weight: 700; color: #1f2937; }
        .prose-custom strong { color: #1f2937; font-weight: 700; }
        .btn-primary { background: linear-gradient(135deg, #059669, #10b981); color: white; font-weight: 700; padding: 12px 28px; border-radius: 14px; box-shadow: 0 4px 14px rgba(5,150,105,0.35); transition: all 0.3s ease; display: inline-block; }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(5,150,105,0.5); transform: translateY(-2px); }
        .pill-badge { background: #ecfdf5; color: #065f46; font-size: 0.75rem; font-weight: 700; padding: 4px 12px; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.05em; }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item summary { list-style: none; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
        .faq-item summary::after { content: '+'; font-size: 1.5rem; font-weight: 300; color: #10b981; transition: transform 0.3s; }
        .faq-item[open] summary::after { content: '\2212'; }
    </style>

    @if(!empty($jsonLd ?? null))
        <script type="application/ld+json">{!! $jsonLd !!}</script>
    @endif
</head>
<body class="bg-gradient-to-br from-emerald-50/50 via-white to-green-50/30 text-slate-800 antialiased">

<nav class="glass sticky top-0 z-50 border-b border-emerald-100/60">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5">
            <div class="w-10 h-10 rounded-xl gradient-bg flex items-center justify-center text-white font-extrabold text-lg shadow-lg">K</div>
            <span class="text-lg font-extrabold gradient-text">{{ $brand['nama'] }}</span>
        </a>
        <div class="flex items-center gap-1 text-sm">
            <a href="{{ url('/') }}" class="hidden md:inline-block text-slate-600 hover:text-emerald-700 font-medium px-3 py-2 rounded-lg hover:bg-emerald-50 transition">Beranda</a>
            <a href="{{ url('/#fitur') }}" class="hidden md:inline-block text-slate-600 hover:text-emerald-700 font-medium px-3 py-2 rounded-lg hover:bg-emerald-50 transition">Fitur</a>
            <a href="{{ url('/#harga') }}" class="hidden md:inline-block text-slate-600 hover:text-emerald-700 font-medium px-3 py-2 rounded-lg hover:bg-emerald-50 transition">Harga</a>
            <a href="{{ url('/admin/login') }}" class="btn-primary text-sm !py-1.5 !px-4">Login Admin</a>
        </div>
    </div>
</nav>

<main>
<div class="max-w-5xl mx-auto px-6 py-10 md:py-14">

    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2 flex-wrap">
        <a href="{{ url('/') }}" class="hover:text-emerald-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('seo.akad', $akad['slug']) }}" class="hover:text-emerald-600">Akad {{ $akad['nama'] }}</a>
        <span>/</span>
        <span class="text-slate-700">di {{ $kota['nama'] }}</span>
    </nav>

    <header class="mb-10">
        <span class="pill-badge mb-4 inline-block">{{ $akad['kategori'] }} · {{ $kota['provinsi'] }} · {{ $year }}</span>
        <h1 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight text-slate-900">
            Akad <span class="gradient-text">{{ $akad['nama'] }}</span> di {{ $kota['nama'] }}
        </h1>
        <p class="text-lg md:text-xl text-slate-600 leading-relaxed">
            {{ $akad['ringkas'] }} Pelajari penerapan akad {{ $akad['nama'] }} untuk koperasi syariah di {{ $kota['nama'] }}, {{ $kota['provinsi'] }}. Lengkap dengan rumus, contoh kasus, fatwa DSN-MUI, dan dukungan software {{ $brand['nama'] }}.
        </p>
    </header>

    <section class="bg-white rounded-3xl border border-emerald-100 p-8 md:p-12 mb-8 shadow-sm">
        <h2 class="text-2xl md:text-3xl font-extrabold mb-6 text-slate-900">Apa itu Akad {{ $akad['nama'] }}?</h2>
        <div class="prose-custom">
            <p>{{ $akad['deskripsi'] }}</p>
        </div>
    </section>

    <section class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200 p-8">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Rumus & Perhitungan</span>
            <h3 class="font-extrabold text-xl mb-3 mt-1 text-slate-900">Cara Menghitung {{ $akad['nama'] }}</h3>
            <div class="bg-white rounded-xl p-5 font-mono text-sm text-slate-800 leading-relaxed shadow-sm border border-emerald-100">
                {{ $akad['rumus'] }}
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-emerald-200 p-8">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Studi Kasus</span>
            <h3 class="font-extrabold text-xl mb-3 mt-1 text-slate-900">Contoh Nyata</h3>
            <p class="text-slate-700 leading-relaxed">{{ $akad['contoh'] }}</p>
        </div>
    </section>

    <section class="bg-amber-50 border-2 border-amber-200 rounded-2xl p-6 mb-8 flex gap-4 items-start">
        <div class="bg-amber-500 rounded-xl p-3 flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <h3 class="font-extrabold text-amber-900 mb-1">Dasar Hukum Syariah</h3>
            <p class="text-amber-800 text-sm leading-relaxed">{{ $akad['fatwa'] }} — Fatwa resmi Dewan Syariah Nasional Majelis Ulama Indonesia (DSN-MUI) yang menjadi acuan penerapan akad {{ $akad['nama'] }} di koperasi syariah seluruh Indonesia, termasuk {{ $kota['nama'] }}.</p>
        </div>
    </section>

    <section class="bg-white rounded-3xl border border-emerald-100 p-8 md:p-12 mb-8 shadow-sm">
        <h2 class="text-3xl font-extrabold mb-4 text-slate-900">Akad {{ $akad['nama'] }} dalam Konteks {{ $kota['nama'] }}</h2>
        <div class="prose-custom">

            <p>{{ $kota['nama'] }} sebagai salah satu kota penting di {{ $kota['provinsi'] }} memiliki <strong>{{ $kota['jumlah_koperasi'] }} koperasi aktif</strong>. Dari jumlah tersebut, sebagian signifikan mengadopsi sistem syariah — termasuk akad {{ $akad['nama'] }} — untuk melayani anggota muslim yang menginginkan layanan keuangan halal sesuai prinsip Islam dan fatwa DSN-MUI.</p>

            <p>Keunggulan penerapan akad {{ $akad['nama'] }} di {{ $kota['nama'] }} antara lain:</p>
            <ul>
                <li><strong>Kepatuhan syariah terjamin:</strong> Semua transaksi mengacu pada fatwa {{ $akad['fatwa'] }} yang diterbitkan DSN-MUI, sehingga anggota yakin tidak ada unsur riba, gharar (ketidakpastian), atau maysir (spekulasi) dalam operasional koperasi.</li>
                <li><strong>Dukungan Dewan Pengawas Syariah (DPS):</strong> Koperasi syariah di {{ $kota['nama'] }} dapat menunjuk DPS lokal bersertifikat DSN-MUI untuk mengawasi implementasi akad {{ $akad['nama'] }} agar tetap murni sesuai prinsip syariah.</li>
                <li><strong>Transparansi bagi hasil:</strong> Nisbah (porsi bagi hasil) disepakati di awal kontrak dengan anggota — keduanya tahu persis berapa bagian masing-masing — tidak seperti bunga bank yang bisa berubah sewaktu-waktu tanpa pemberitahuan.</li>
                <li><strong>Edukasi literasi syariah:</strong> Koperasi dapat menggunakan software {{ $brand['nama'] }} untuk mengedukasi anggota baru tentang cara kerja akad {{ $akad['nama'] }} — lengkap dengan simulasi kalkulator bagi hasil sehingga anggota paham sebelum tanda tangan.</li>
            </ul>

            <h3>Tantangan Khas {{ $kota['nama'] }} dalam Implementasi {{ $akad['nama'] }}</h3>
            <p>Seperti banyak kota Indonesia, pengurus koperasi di {{ $kota['nama'] }} menghadapi beberapa tantangan saat mengimplementasikan akad {{ $akad['nama'] }}:</p>
            <ul>
                <li><strong>Minimnya tenaga akuntansi syariah:</strong> Tidak banyak lulusan akuntansi syariah di {{ $kota['nama'] }} yang memahami pencatatan {{ $akad['kategori'] }} dengan benar. Software {{ $brand['nama'] }} mengatasi ini dengan menjurnal otomatis — tidak perlu staf khusus akuntansi syariah.</li>
                <li><strong>Kesulitan audit DPS:</strong> Tanpa sistem digital, DPS harus memeriksa ribuan transaksi manual satu per satu. Dengan {{ $brand['nama'] }}, laporan terpisah per akad bisa di-export dalam hitungan detik — audit DPS jadi efisien.</li>
                <li><strong>Anggota belum terbiasa:</strong> Sebagian anggota {{ $kota['nama'] }} masih mengasosiasikan "pinjaman = bunga". Butuh edukasi bahwa bagi hasil bukan bunga; total pembiayaan mungkin sama, tapi sumber pendapatan koperasi jelas dan halal.</li>
            </ul>

            <h3>Dukungan Software {{ $brand['nama'] }} untuk Akad {{ $akad['nama'] }}</h3>
            <p>Software koperasi syariah {{ $brand['nama'] }} mendukung penuh akad <strong>{{ $akad['nama'] }}</strong> beserta {{ count(config('pseo.akad')) }} akad syariah lainnya. Setiap produk pembiayaan yang dibuat di admin panel bisa dipilih akadnya — lalu sistem otomatis:</p>
            <ul>
                <li><strong>Memilih chart of accounts (CoA) syariah</strong> yang sesuai — akun pendapatan bagi hasil, margin, atau ujrah sesuai kategori akad.</li>
                <li><strong>Menjurnal otomatis</strong> setiap transaksi yang menggunakan akad {{ $akad['nama'] }} — tanpa perlu intervensi staf akuntansi.</li>
                <li><strong>Generate dokumen akad</strong> dalam format PDF dengan pasal-pasal standar fatwa DSN-MUI, siap di-print untuk ditandatangani anggota di {{ $kota['nama'] }}.</li>
                <li><strong>Laporan terpisah per akad</strong> — laporan keuangan syariah memisahkan pendapatan dari setiap jenis akad sehingga DPS bisa melakukan audit kepatuhan dengan mudah.</li>
            </ul>

            <p>Bagi koperasi di {{ $kota['nama'] }} yang melayani anggota syariah dan konvensional sekaligus, mode dual-system {{ $brand['nama'] }} memungkinkan satu instalasi menangani kedua segmen — toggle akad per produk, laporan keuangan terpisah, dan tidak perlu beli dua software berbeda.</p>
        </div>
    </section>

    <section class="mb-8">
        <h2 class="text-3xl font-extrabold mb-6 text-slate-900">Fitur {{ $brand['nama'] }} untuk Koperasi Syariah</h2>
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($brand['fitur'] as $f)
                <div class="bg-white rounded-2xl border border-emerald-100 p-5 flex gap-3 items-start card-hover">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-slate-700 text-sm">{{ $f }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-3xl font-extrabold mb-6 text-slate-900">Pertanyaan Umum tentang {{ $akad['nama'] }} di {{ $kota['nama'] }}</h2>
        <div class="space-y-3">
            @foreach($faqs as $index => $faq)
                <details class="faq-item bg-white rounded-2xl border border-emerald-100 shadow-sm group" {{ $index === 0 ? 'open' : '' }}>
                    <summary class="px-6 py-4 font-semibold text-slate-900 text-base flex items-center justify-between hover:text-emerald-700 transition">
                        <span>{{ $faq['q'] }}</span>
                    </summary>
                    <div class="px-6 pb-5 text-slate-600 text-sm leading-relaxed">{{ $faq['a'] }}</div>
                </details>
            @endforeach
        </div>
    </section>

    <section class="mb-12">
        <div class="rounded-3xl gradient-bg-dark p-10 md:p-14 text-white overflow-hidden relative shadow-2xl shadow-emerald-500/30">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-teal-400/20 rounded-full blur-3xl"></div>
            <div class="relative grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-3xl md:text-4xl font-extrabold mb-3">Jalankan Akad {{ $akad['nama'] }} dengan {{ $brand['nama'] }} di {{ $kota['nama'] }}</h3>
                    <p class="text-emerald-100 mb-6 text-lg leading-relaxed">Migrasi dari manual ke digital. Sistem otomatis jurnal, CoA syariah built-in, dan laporan siap audit DPS.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ url('/admin/login') }}" class="bg-white text-emerald-700 font-bold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition">Coba Gratis 14 Hari</a>
                        <a href="{{ url('/#fitur') }}" class="border border-emerald-300/50 text-white font-semibold px-6 py-3 rounded-xl hover:bg-white/10 transition">Fitur Syariah Lengkap</a>
                    </div>
                </div>
                <ul class="space-y-3 text-sm">
                    @foreach(collect($brand['fitur'])->take(5) as $f)
                        <li class="flex gap-3 items-start">
                            <svg class="w-5 h-5 text-emerald-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-emerald-50">{{ $f }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Kota Lain dengan Akad {{ $akad['nama'] }}</h2>
        <p class="text-slate-600 mb-8">Terapkan akad {{ $akad['nama'] }} untuk koperasi syariah di kota-kota Indonesia:</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach(collect(config('pseo.kota'))->reject(fn($k) => $k['slug'] === $kota['slug'])->take(12) as $k)
                <a href="{{ route('seo.akad-kota', [$akad['slug'], $k['slug']]) }}" class="bg-white rounded-xl border border-emerald-100 p-4 hover:border-emerald-400 hover:shadow-md transition">
                    <div class="font-bold text-slate-900 text-sm">{{ $akad['nama'] }} di {{ $k['nama'] }}</div>
                    <div class="text-xs text-slate-500">{{ $k['provinsi'] }}</div>
                </a>
            @endforeach
        </div>
    </section>

    <section>
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Akad Syariah Lainnya</h2>
        <p class="text-slate-600 mb-8">{{ $brand['nama'] }} mendukung {{ count(config('pseo.akad')) }} akad syariah lengkap — semuanya tersedia untuk koperasi di {{ $kota['nama'] }}.</p>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach(collect(config('pseo.akad'))->reject(fn($a) => $a['slug'] === $akad['slug'])->values() as $a)
                <a href="{{ route('seo.akad', $a['slug']) }}" class="card-hover bg-white rounded-2xl border border-emerald-100 p-5 block">
                    <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded mb-2">{{ $a['kategori'] }}</span>
                    <h3 class="font-extrabold text-base mb-1 text-slate-900">{{ $a['nama'] }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $a['ringkas'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

</div>
</main>

<footer class="mt-16 bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="col-span-2 md:col-span-1">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-xl gradient-bg flex items-center justify-center text-white font-extrabold">K</div>
                <span class="text-lg font-extrabold text-white">{{ $brand['nama'] }}</span>
            </div>
            <p class="text-sm text-slate-400">{{ $brand['tagline'] }}</p>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3 text-sm uppercase tracking-wider">Jenis Koperasi</h4>
            <ul class="space-y-2 text-sm">
                @foreach(collect(config('pseo.jenis'))->take(5) as $j)
                    <li><a href="{{ route('seo.jenis', $j['slug']) }}" class="hover:text-white transition">{{ $j['nama'] }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3 text-sm uppercase tracking-wider">Akad Syariah</h4>
            <ul class="space-y-2 text-sm">
                @foreach(collect(config('pseo.akad'))->take(5) as $a)
                    <li><a href="{{ route('seo.akad', $a['slug']) }}" class="hover:text-white transition">{{ $a['nama'] }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3 text-sm uppercase tracking-wider">Kota Populer</h4>
            <ul class="space-y-2 text-sm">
                @foreach(collect(config('pseo.kota'))->take(5) as $k)
                    <li><a href="{{ route('seo.kota', $k['slug']) }}" class="hover:text-white transition">Koperasi di {{ $k['nama'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-6 text-sm text-slate-500 text-center">
            &copy; {{ date('Y') }} {{ $brand['nama'] }} — Software Koperasi Konvensional & Syariah Indonesia.
        </div>
    </div>
</footer>

</body>
</html>
