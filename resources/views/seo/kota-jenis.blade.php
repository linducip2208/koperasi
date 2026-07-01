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
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(5,150,105,0.25); }
        .prose-custom p { margin-bottom: 1.25rem; line-height: 1.85; color: #374151; }
        .prose-custom ul { margin-bottom: 1.25rem; padding-left: 1.5rem; list-style-type: disc; }
        .prose-custom ol { margin-bottom: 1.25rem; padding-left: 1.5rem; list-style-type: decimal; }
        .prose-custom li { margin-bottom: 0.5rem; color: #374151; line-height: 1.7; }
        .prose-custom h2 { margin-top: 2.5rem; margin-bottom: 1rem; font-size: 1.75rem; font-weight: 800; color: #111827; }
        .prose-custom h3 { margin-top: 2rem; margin-bottom: 0.75rem; font-size: 1.25rem; font-weight: 700; color: #1f2937; }
        .prose-custom strong { color: #1f2937; font-weight: 700; }
        .btn-primary { background: linear-gradient(135deg, #059669, #10b981); color: white; font-weight: 700; padding: 12px 28px; border-radius: 14px; box-shadow: 0 4px 14px rgba(5,150,105,0.35); transition: all 0.3s ease; display: inline-block; }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(5,150,105,0.5); transform: translateY(-2px); }
        .btn-outline { border: 2px solid #059669; color: #059669; font-weight: 700; padding: 10px 24px; border-radius: 14px; transition: all 0.3s ease; display: inline-block; }
        .btn-outline:hover { background: #059669; color: white; box-shadow: 0 4px 14px rgba(5,150,105,0.35); }
        .pill-badge { background: #ecfdf5; color: #065f46; font-size: 0.75rem; font-weight: 700; padding: 4px 12px; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.05em; }
    </style>

    @if(!empty($jsonLd ?? null))
        <script type="application/ld+json">{!! $jsonLd !!}</script>
    @endif
</head>
<body class="bg-gradient-to-br from-emerald-50/50 via-white to-teal-50/30 text-slate-800 antialiased">

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
<div class="max-w-7xl mx-auto px-6 py-10 md:py-14">

    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2 flex-wrap">
        <a href="{{ url('/') }}" class="hover:text-emerald-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('seo.kota', $kota['slug']) }}" class="hover:text-emerald-600">Koperasi di {{ $kota['nama'] }}</a>
        <span>/</span>
        <a href="{{ route('seo.jenis', $jenis['slug']) }}" class="hover:text-emerald-600">{{ $jenis['singkatan'] }}</a>
        <span>/</span>
        <span class="text-slate-700">{{ $jenis['singkatan'] }} di {{ $kota['nama'] }}</span>
    </nav>

    <header class="mb-10 max-w-4xl">
        <span class="pill-badge mb-4 inline-block">{{ $kota['provinsi'] }} · {{ $jenis['singkatan'] }} · {{ $year }}</span>
        <h1 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight text-slate-900">
            {{ $jenis['nama'] }} di <span class="gradient-text">{{ $kota['nama'] }}</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-600 leading-relaxed">
            {{ $jenis['tagline'] }}. {{ $brand['nama'] }} adalah software terlengkap untuk mengelola {{ $jenis['singkatan'] }} di {{ $kota['nama'] }}, {{ $kota['provinsi'] }} — dari pencatatan anggota, simpanan & pinjaman, akuntansi PSAK 27, hingga distribusi SHU otomatis siap RAT.
        </p>
    </header>

    <section class="grid lg:grid-cols-3 gap-6 mb-12">
        <div class="lg:col-span-2 bg-white rounded-3xl border border-emerald-100 p-8 md:p-10 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <span class="bg-emerald-500 text-white font-bold px-3 py-1 rounded-lg text-sm">⭐ REKOMENDASI</span>
                <span class="text-emerald-700 font-semibold text-sm">#1 untuk {{ $jenis['singkatan'] }}</span>
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold mb-3 text-slate-900">{{ $brand['nama'] }} — Software {{ $jenis['singkatan'] }} Terlengkap di {{ $kota['nama'] }}</h2>
            <p class="text-slate-600 mb-6 leading-relaxed">{{ $brand['tagline'] }}. Dirancang khusus untuk {{ $jenis['nama'] }} — dengan struktur chart of accounts, modul transaksi, dan format laporan sesuai kebutuhan {{ $jenis['singkatan'] }} di {{ $kota['nama'] }}.</p>

            <h3 class="font-extrabold text-lg mb-4 text-slate-900">Fitur Utama {{ $brand['nama'] }}</h3>
            <ul class="grid md:grid-cols-2 gap-3 mb-8">
                @foreach($brand['fitur'] as $f)
                    <li class="flex gap-3 items-start">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-slate-700 text-sm">{{ $f }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="flex flex-wrap gap-3 items-center">
                <a href="{{ url('/admin/login') }}" class="btn-primary">Coba Gratis di {{ $kota['nama'] }}</a>
                <span class="text-sm text-slate-500">Mulai dari <strong class="text-slate-900">{{ $brand['harga_mulai'] }}</strong> · trial 14 hari</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-700 to-teal-600 rounded-3xl p-8 text-white shadow-xl shadow-emerald-500/20">
            <h3 class="font-extrabold text-xl mb-4">Sekilas {{ $kota['nama'] }}</h3>
            <p class="text-white/90 leading-relaxed mb-6 text-sm">{{ $kota['nama'] }} adalah salah satu pusat ekonomi di {{ $kota['provinsi'] }} dengan jumlah koperasi aktif mencapai <strong>{{ $kota['jumlah_koperasi'] }} unit</strong>. Kebutuhan software manajemen koperasi di {{ $kota['nama'] }} terus meningkat seiring tuntutan digitalisasi dan transparansi keuangan.</p>
            <div class="bg-white/15 rounded-2xl p-5 mb-4">
                <div class="text-3xl font-extrabold text-white mb-1">{{ $kota['jumlah_koperasi'] }}</div>
                <div class="text-white/70 text-sm">Koperasi Aktif</div>
            </div>
            <p class="text-white/80 text-xs leading-relaxed">Data dari Kementerian Koperasi UKM RI — estimasi jumlah koperasi terdaftar di wilayah {{ $kota['nama'] }}, {{ $kota['provinsi'] }}.</p>
        </div>
    </section>

    <section class="bg-white rounded-3xl border border-emerald-100 p-8 md:p-12 mb-12 shadow-sm">
        <h2 class="text-3xl font-extrabold mb-6 text-slate-900">Mengelola {{ $jenis['nama'] }} di {{ $kota['nama'] }}</h2>
        <div class="prose-custom max-w-none">

            <p>{{ $jenis['nama'] }} ({{ $jenis['singkatan'] }}) merupakan salah satu jenis koperasi yang paling banyak ditemukan di {{ $kota['nama'] }}, {{ $kota['provinsi'] }}. Dari total {{ $kota['jumlah_koperasi'] }} koperasi aktif di wilayah ini, sebagian besar bergerak di bidang ini karena relevansinya dengan kebutuhan masyarakat setempat — mulai dari kalangan pekerja, pedagang pasar, hingga komunitas lingkungan.</p>

            <h3>Karakteristik {{ $jenis['singkatan'] }} di {{ $kota['nama'] }}</h3>
            <p>Setiap {{ $jenis['nama'] }} di {{ $kota['nama'] }} wajib memenuhi standar operasional yang ditetapkan dalam regulasi nasional: <strong>{{ $jenis['regulasi'] }}</strong>. Software {{ $brand['nama'] }} sudah menyesuaikan seluruh format laporan dan struktur akun dengan regulasi ini, sehingga pengurus koperasi di {{ $kota['nama'] }} tidak perlu konversi manual saat menyerahkan laporan ke Dinas Koperasi setempat.</p>

            <p>Berbeda dengan koperasi di kota besar lain yang lebih mapan secara infrastruktur IT, mayoritas {{ $jenis['singkatan'] }} di {{ $kota['nama'] }} masih menggunakan sistem pembukuan semi-manual: kombinasi buku tulis untuk pencatatan harian dan Microsoft Excel untuk laporan bulanan. Namun praktik ini semakin tidak sustainable ketika jumlah anggota tumbuh melampaui 100 orang — risiko error meningkat, tutup buku bisa berhari-hari, dan perhitungan SHU menjadi rawan selisih.</p>

            <h3>5 Langkah Digitalisasi {{ $jenis['singkatan'] }} di {{ $kota['nama'] }}</h3>
            <ol>
                <li><strong>Tutup buku di sistem lama</strong> — finalisasi posisi keuangan terakhir sebagai saldo awal di software baru.</li>
                <li><strong>Import data anggota</strong> — dari Excel atau CSV ke dalam {{ $brand['nama'] }}, verifikasi satu per satu.</li>
                <li><strong>Setting produk simpanan & pinjaman</strong> — tentukan suku bunga, tenor, biaya administrasi, dan denda sesuai AD/ART koperasi Anda.</li>
                <li><strong>Training pengurus 1–2 hari</strong> — tim support {{ $brand['nama'] }} siap remote session untuk melatih bendahara, sekretaris, dan admin Anda.</li>
                <li><strong>Go-live dan monitoring</strong> — mulai transaksi di software, pantau dashboard real-time, dan siapkan laporan untuk RAT mendatang.</li>
            </ol>

            <h3>Fitur Spesifik untuk {{ $jenis['singkatan'] }}</h3>
            <p>Berikut fitur-fitur esensial yang wajib ada dalam software untuk {{ $jenis['nama'] }}:</p>
            <ul>
                @foreach($jenis['fitur_utama'] as $f)
                    <li><strong>{{ $f }}</strong></li>
                @endforeach
            </ul>
            <p>Setiap fitur di atas sudah built-in di {{ $brand['nama'] }} — tidak perlu plugin tambahan atau konfigurasi rumit.</p>

            <h3>Mengapa Pengurus {{ $jenis['singkatan'] }} di {{ $kota['nama'] }} Memilih {{ $brand['nama'] }}</h3>
            <p>Beberapa alasan konkret yang membuat {{ $brand['nama'] }} menjadi pilihan utama untuk {{ $jenis['nama'] }} di {{ $kota['nama'] }}:</p>
            <ul>
                <li><strong>Multi-user dengan role-based access:</strong> Bendahara, admin, ketua, pengawas — masing-masing login dengan hak akses berbeda. Transaksi tercatat siapa yang input, kapan, dari IP mana.</li>
                <li><strong>Akuntansi otomatis:</strong> Setiap setoran simpanan, pencairan pinjaman, dan pembayaran angsuran otomatis terjurnal sesuai PSAK 27 tanpa perlu campur tangan staf akuntansi.</li>
                <li><strong>Pelaporan siap audit:</strong> Laporan keuangan lengkap (Neraca, Laba-Rugi, Arus Kas, Perubahan Ekuitas, CALK) bisa di-export ke PDF atau Excel dalam hitungan detik — siap untuk RAT atau pemeriksaan Dinas Koperasi.</li>
                <li><strong>Portal Anggota:</strong> Anggota bisa cek saldo simpanan, histori pinjaman, sisa cicilan, dan ajukan pinjaman baru via HP tanpa harus datang ke kantor koperasi.</li>
                <li><strong>Backup & keamanan:</strong> Data dienkripsi dan otomatis dibackup — tidak akan hilang walau laptop rusak atau kena virus. Cocok untuk {{ $jenis['singkatan'] }} di {{ $kota['nama'] }} yang belum punya server sendiri.</li>
            </ul>

            <h3>Dukungan untuk Koperasi Syariah</h3>
            <p>Bila {{ $jenis['singkatan'] }} Anda di {{ $kota['nama'] }} juga menjalankan unit syariah (atau ingin beralih ke sistem syariah), {{ $brand['nama'] }} mendukung dual-mode — Konvensional dan Syariah dalam satu platform. 12 akad syariah lengkap siap pakai, termasuk Mudharabah, Murabahah, Ijarah, dan Qardh. Setiap produk bisa di-set akadnya sesuai kebutuhan, dan laporan keuangan syariah terpisah sempurna dari laporan konvensional.</p>

            <p>Dengan jumlah penduduk muslim yang signifikan di {{ $kota['provinsi'] }}, koperasi yang menyediakan layanan syariah cenderung memiliki loyalitas anggota lebih tinggi dan tingkat NPL lebih rendah — karena faktor kepercayaan dan kepatuhan terhadap prinsip halal.</p>
        </div>
    </section>

    <section class="my-14">
        <div class="rounded-3xl gradient-bg-dark p-10 md:p-14 text-white overflow-hidden relative shadow-2xl shadow-emerald-500/30">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-teal-400/20 rounded-full blur-3xl"></div>
            <div class="relative grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-3xl md:text-4xl font-extrabold mb-3">Siap Digitalisasi {{ $jenis['singkatan'] }} di {{ $kota['nama'] }}?</h3>
                    <p class="text-emerald-100 mb-6 text-lg leading-relaxed">{{ $brand['tagline'] }}. Trial gratis 14 hari, tanpa kartu kredit, migrasi data gratis dibantu tim support.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ url('/admin/login') }}" class="bg-white text-emerald-700 font-bold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition">Coba Gratis 14 Hari</a>
                        <a href="{{ url('/#fitur') }}" class="border border-emerald-300/50 text-white font-semibold px-6 py-3 rounded-xl hover:bg-white/10 transition">Lihat Fitur Lengkap</a>
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

    <section class="mb-16">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Jenis Koperasi Lain di {{ $kota['nama'] }}</h2>
        <p class="text-slate-600 mb-8">Selain {{ $jenis['singkatan'] }}, {{ $brand['nama'] }} juga mendukung jenis koperasi lain di {{ $kota['nama'] }}:</p>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($jenisLain as $j)
                <a href="{{ route('seo.kota-jenis', [$kota['slug'], $j['slug']]) }}" class="card-hover bg-white rounded-2xl border border-emerald-100 p-6 block">
                    <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded mb-3">{{ $j['singkatan'] }}</span>
                    <h3 class="font-extrabold text-lg mb-2 text-slate-900">{{ $j['nama'] }}</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $j['tagline'] }}</p>
                    <span class="text-emerald-600 font-semibold text-sm mt-3 inline-flex items-center gap-1">Lihat di {{ $kota['nama'] }} →</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Lihat {{ $jenis['singkatan'] }} di Kota Lain</h2>
        <p class="text-slate-600 mb-8">{{ $brand['nama'] }} mendukung operasional koperasi di berbagai kota di Indonesia.</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach(collect(config('pseo.kota'))->reject(fn($k) => $k['slug'] === $kota['slug'])->take(8) as $k)
                <a href="{{ route('seo.kota-jenis', [$k['slug'], $jenis['slug']]) }}" class="bg-white rounded-xl border border-emerald-100 p-4 hover:border-emerald-400 hover:shadow-md transition">
                    <div class="font-bold text-slate-900 text-sm">{{ $jenis['singkatan'] }} di {{ $k['nama'] }}</div>
                    <div class="text-xs text-slate-500">{{ $k['provinsi'] }}</div>
                </a>
            @endforeach
        </div>
    </section>

</div>
</main>

<footer class="bg-slate-900 text-slate-300">
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
