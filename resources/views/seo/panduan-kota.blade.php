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
    <meta property="og:type" content="article">
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
        .city-highlight { background: linear-gradient(135deg, #ecfdf5, #d1fae5); border-left: 4px solid #10b981; border-radius: 0 16px 16px 0; padding: 20px 24px; margin: 1.5rem 0; }
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
<div class="max-w-4xl mx-auto px-6 py-10 md:py-14">

    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2 flex-wrap">
        <a href="{{ url('/') }}" class="hover:text-emerald-600">Beranda</a>
        <span>/</span>
        <a href="#" class="hover:text-emerald-600">Panduan</a>
        <span>/</span>
        <a href="{{ route('seo.panduan', $panduan['slug']) }}" class="hover:text-emerald-600">{{ $panduan['judul'] }}</a>
        <span>/</span>
        <span class="text-slate-700">di {{ $kota['nama'] }}</span>
    </nav>

    <header class="mb-8">
        <span class="pill-badge mb-4 inline-block">Panduan · {{ $panduan['estimasi_baca'] }} menit baca · {{ $kota['provinsi'] }}</span>
        <h1 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight text-slate-900">
            {{ $panduan['judul'] }}<br><span class="gradient-text">di {{ $kota['nama'] }}</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-600 leading-relaxed">{{ $panduan['deskripsi'] }} Panduan ini disesuaikan dengan konteks {{ $kota['nama'] }}, {{ $kota['provinsi'] }} — mencakup regulasi lokal, sumber daya pendukung, dan cara {{ $brand['nama'] }} membantu pengurus koperasi di wilayah ini.</p>
    </header>

    <div class="city-highlight mb-8">
        <div class="flex flex-wrap items-center gap-3">
            <svg class="w-6 h-6 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <div>
                <span class="font-extrabold text-emerald-800 text-lg">{{ $kota['nama'] }}, {{ $kota['provinsi'] }}</span>
                <span class="text-emerald-700 text-sm block mt-0.5">{{ $kota['jumlah_koperasi'] }} koperasi aktif — software {{ $brand['nama'] }} siap bantu digitalisasi koperasi Anda.</span>
            </div>
        </div>
    </div>

    <article class="bg-white rounded-3xl border border-emerald-100 p-8 md:p-12 shadow-sm">
        <div class="prose-custom max-w-none">

            @switch($panduan['slug'])
                @case('cara-mendirikan-koperasi')
                    <p>Mendirikan koperasi di {{ $kota['nama'] }} tidak serumit yang dibayangkan, namun tetap memerlukan urutan langkah yang tepat agar koperasi memiliki status badan hukum yang sah dan bisa langsung beroperasi. Dengan lebih dari <strong>{{ $kota['jumlah_koperasi'] }} koperasi aktif</strong> di {{ $kota['nama'] }}, {{ $kota['provinsi'] }}, kota ini menunjukkan ekosistem koperasi yang dinamis — dan prosedur pendiriannya sudah terstandarisasi dengan baik.</p>

                    <h2>1. Rapat Pendirian dengan Minimal 20 Orang</h2>
                    <p>Kumpulkan minimal 20 orang calon anggota di {{ $kota['nama'] }} yang sepakat mendirikan koperasi. Adakan rapat pendirian dengan agenda: nama koperasi, tujuan & jenis usaha, AD/ART, modal awal (simpanan pokok), dan susunan pengurus pertama. Pastikan semua peserta menandatangani berita acara rapat — dokumen ini menjadi dasar hukum pertama koperasi Anda di {{ $kota['nama'] }}.</p>

                    <h2>2. Persiapan Dokumen Akta</h2>
                    <p>Siapkan: berita acara rapat pendirian, draft AD/ART, daftar nama 20 pendiri lengkap dengan KTP & NPWP, neraca awal, dan surat pernyataan modal sendiri. Khusus untuk koperasi syariah (KSPPS), tambahkan susunan Dewan Pengawas Syariah dengan minimal 1 anggota bersertifikat DSN-MUI. Notaris di {{ $kota['nama'] }} sudah terbiasa dengan dokumen-dokumen ini, sehingga proses berjalan lancar.</p>

                    <h2>3. Akta Notaris Pendirian</h2>
                    <p>Kunjungi notaris yang ditunjuk Kemenkop UKM di wilayah {{ $kota['nama'] }} atau {{ $kota['provinsi'] }}. Notaris akan mengangkat akta pendirian koperasi berdasarkan dokumen yang Anda siapkan. Biaya notaris di {{ $kota['nama'] }} berkisar Rp 1,5–3 juta tergantung kompleksitas AD/ART dan jenis koperasi yang didirikan.</p>

                    <h2>4. Pengesahan Badan Hukum di Kemenkumham</h2>
                    <p>Notaris akan submit ke sistem AHU online Kemenkumham. Setelah disetujui — biasanya 7–14 hari kerja — keluar SK Pengesahan Badan Hukum Koperasi. Sejak ini, koperasi Anda di {{ $kota['nama'] }} sah secara hukum dan bisa mulai beroperasi.</p>

                    <h2>5. NPWP, NIB, dan Izin Usaha</h2>
                    <p>Daftarkan NPWP koperasi ke Kantor Pelayanan Pajak di {{ $kota['nama'] }}. Lalu daftarkan NIB (Nomor Induk Berusaha) melalui OSS-RBA (oss.go.id). Untuk KSP/KSPPS, tambahkan izin usaha simpan pinjam dari Dinas Koperasi {{ $kota['provinsi'] }}. Dokumen-dokumen ini wajib agar koperasi bisa membuka rekening atas nama badan hukum di bank lokal {{ $kota['nama'] }}.</p>

                    <h2>6. Setup Operasional dengan Software</h2>
                    <p>Setelah administrasi selesai, segera siapkan infrastruktur operasional: buka rekening koperasi di bank, sediakan kantor (bisa di rumah pengurus untuk efisiensi biaya awal), dan yang paling penting — <strong>pasang software pengelolaan koperasi sejak hari pertama.</strong> Ini mencegah penumpukan data manual yang harus dimigrasi nanti. {{ $brand['nama'] }} memiliki paket siap pakai untuk koperasi baru di {{ $kota['nama'] }} — mulai {{ $brand['harga_mulai'] }}, termasuk bantuan setup awal oleh tim support via remote.</p>

                    <h2>Dukungan Dinas Koperasi {{ $kota['provinsi'] }}</h2>
                    <p>Dinas Koperasi di {{ $kota['provinsi'] }} menyediakan klinik konsultasi gratis untuk calon pendiri koperasi. Manfaatkan layanan ini untuk konsultasi AD/ART, pemilihan jenis koperasi yang tepat, dan informasi program pembinaan pemerintah. Setelah koperasi berjalan, {{ $brand['nama'] }} akan memudahkan pelaporan — format laporan sudah sesuai standar yang diminta Dinas Koperasi setempat.</p>
                    @break

                @case('akuntansi-koperasi-psak-27')
                    <p>PSAK 27 (Akuntansi Koperasi) adalah standar akuntansi khusus yang berlaku untuk seluruh koperasi di Indonesia — termasuk {{ $kota['nama'] }}, {{ $kota['provinsi'] }}. Berbeda dengan PSAK umum untuk Perseroan Terbatas, PSAK 27 mengakomodasi karakter unik koperasi: simpanan anggota sebagai modal sendiri, distribusi SHU, dan pencatatan jasa modal vs jasa usaha.</p>

                    <h2>Akun Khas Koperasi yang Wajib Ada</h2>
                    <p>Setiap koperasi di {{ $kota['nama'] }} harus memiliki akun-akun khusus berikut di chart of accounts (CoA):</p>
                    <ul>
                        <li><strong>Simpanan Pokok</strong> — disetor sekali saat masuk anggota, tidak dapat ditarik (modal permanen)</li>
                        <li><strong>Simpanan Wajib</strong> — disetor rutin bulanan, tidak dapat ditarik selama jadi anggota</li>
                        <li><strong>Simpanan Sukarela</strong> — bersifat tabungan, dapat ditarik kapan saja</li>
                        <li><strong>Cadangan Koperasi</strong> — alokasi dari SHU untuk ekspansi & risiko</li>
                        <li><strong>SHU Belum Dibagi</strong> — sisa hasil usaha akhir tahun sebelum pembagian RAT</li>
                        <li><strong>Pendapatan Jasa Bunga / Bagi Hasil</strong> — dari pinjaman atau pembiayaan</li>
                    </ul>

                    <h2>Jurnal Standar Transaksi Koperasi</h2>
                    <h3>1. Setoran Simpanan Pokok</h3>
                    <p>Dr. Kas Rp X / Cr. Simpanan Pokok Anggota Rp X — setiap anggota baru menyetor simpanan pokok.</p>
                    <h3>2. Pencairan Pinjaman</h3>
                    <p>Dr. Piutang Pinjaman Anggota Rp X / Cr. Kas Rp X — koperasi mengeluarkan dana pinjaman ke anggota.</p>
                    <h3>3. Pembayaran Cicilan + Bunga</h3>
                    <p>Dr. Kas Rp Y / Cr. Piutang Pinjaman Rp Y1 / Cr. Pendapatan Bunga Rp Y2 — anggota bayar cicilan, dipisah pokok dan bunga.</p>
                    <h3>4. Distribusi SHU</h3>
                    <p>Dr. SHU Belum Dibagi Rp Z / Cr. Hutang Jasa Modal Anggota Rp Z1 / Cr. Hutang Jasa Usaha Anggota Rp Z2 / Cr. Cadangan Rp Z3 — dilakukan saat RAT akhir tahun.</p>

                    <h2>Konteks {{ $kota['nama'] }}: Tantangan & Solusi</h2>
                    <p>Banyak koperasi di {{ $kota['nama'] }} masih mengandalkan staf akuntansi freelance yang belum tentu paham PSAK 27. Akibatnya, laporan keuangan sering dikembalikan Dinas Koperasi {{ $kota['provinsi'] }} karena format tidak sesuai. {{ $brand['nama'] }} mengatasi masalah ini dengan <strong>auto-journal engine</strong> — setiap transaksi otomatis terjurnal sesuai PSAK 27, dan 5 laporan keuangan (Neraca, SHU, Arus Kas, Perubahan Ekuitas, CALK) tersedia real-time. Pengurus di {{ $kota['nama'] }} tinggal review dan export ke PDF — tanpa perlu staf akuntansi khusus PSAK 27.</p>
                    @break

                @case('laporan-keuangan-koperasi')
                    <p>Berdasarkan PSAK 27 dan Permenkop UKM, ada 5 laporan keuangan yang wajib dibuat koperasi setiap akhir periode — bulanan untuk internal, tahunan untuk RAT dan setoran ke Dinas Koperasi {{ $kota['provinsi'] }}. Pengurus koperasi di {{ $kota['nama'] }} harus memahami kelima laporan ini agar tidak ada temuan saat pemeriksaan.</p>

                    <h2>1. Neraca (Laporan Posisi Keuangan)</h2>
                    <p>Snapshot aset, kewajiban, dan ekuitas pada satu titik waktu. Khas koperasi: bagian ekuitas dirinci sebagai Simpanan Pokok, Simpanan Wajib, Cadangan, dan SHU Belum Dibagi. Tidak ada konsep "modal saham" seperti PT. Ini penting dipahami pengurus {{ $kota['nama'] }} agar tidak keliru saat menghitung nilai kekayaan bersih koperasi.</p>

                    <h2>2. Laporan Sisa Hasil Usaha (PHU/SHU)</h2>
                    <p>Sama dengan Laba/Rugi di perusahaan biasa, tapi disebut "Sisa Hasil Usaha" karena prinsip koperasi bukan profit-maximizing melainkan pelayanan anggota. Format: Pendapatan Operasional (bunga/bagi hasil) − Beban Operasional − Beban Administratif = SHU.</p>

                    <h2>3. Laporan Arus Kas</h2>
                    <p>Memetakan keluar-masuk kas dari 3 aktivitas: Operasional (cicilan masuk, gaji keluar), Investasi (beli aset tetap), dan Pendanaan (setoran simpanan, pinjaman dari induk). Wajib pakai metode langsung untuk koperasi — bukan metode tidak langsung seperti di perusahaan besar.</p>

                    <h2>4. Laporan Perubahan Ekuitas Anggota</h2>
                    <p>Khas koperasi — memetakan pergerakan setiap pos ekuitas: simpanan pokok masuk dari anggota baru, simpanan wajib akumulasi bulanan, cadangan dari alokasi SHU, dan distribusi SHU ke anggota. Laporan ini menjadi bukti transparansi pengelolaan dana anggota.</p>

                    <h2>5. Catatan atas Laporan Keuangan (CALK)</h2>
                    <p>Penjelasan naratif yang melengkapi keempat laporan di atas: kebijakan akuntansi, rincian akun-akun penting, kontrak material, dan kontingensi. Di RAT, CALK dibacakan paragraf demi paragraf agar anggota paham kondisi keuangan.</p>

                    <h2>Konteks {{ $kota['nama'] }}</h2>
                    <p>Dinas Koperasi {{ $kota['provinsi'] }} secara rutin melakukan pemeriksaan laporan keuangan koperasi di wilayah {{ $kota['nama'] }}. Koperasi yang tidak bisa menyediakan kelima laporan di atas tepat waktu berisiko mendapat teguran administratif. {{ $brand['nama'] }} men-generate semua laporan ini secara otomatis dalam menu Laporan — pengurus di {{ $kota['nama'] }} tinggal pilih periode, klik export PDF, dan laporan siap diserahkan.</p>
                    @break

                @case('menghitung-shu-koperasi')
                    <p>Sisa Hasil Usaha (SHU) adalah surplus operasional koperasi setelah dikurangi semua beban. Pembagian SHU diatur AD/ART dan UU No. 25/1992 — tidak boleh dibagikan rata, harus proporsional terhadap kontribusi anggota berupa <strong>jasa modal</strong> (simpanan) dan <strong>jasa usaha</strong> (transaksi). Koperasi di {{ $kota['nama'] }} wajib menghitung SHU dengan cermat agar tidak ada keberatan dari anggota.</p>

                    <h2>Rumus SHU per Anggota</h2>
                    <ul>
                        <li>SHU Anggota = SHU Jasa Modal + SHU Jasa Usaha</li>
                        <li>SHU Jasa Modal = (Simpanan Anggota / Total Simpanan) × Pos Jasa Modal</li>
                        <li>SHU Jasa Usaha = (Transaksi Anggota / Total Transaksi) × Pos Jasa Usaha</li>
                    </ul>

                    <h2>Contoh Kasus di {{ $kota['nama'] }}</h2>
                    <p>Koperasi "{{ $kota['nama'] }} Makmur" punya SHU Rp 100 juta tahun ini. Anggota Ibu Ani punya simpanan Rp 5jt (dari total simpanan Rp 500jt) dan transaksi Rp 3jt (dari total transaksi Rp 100jt). Dengan standar 25% jasa modal + 25% jasa usaha:</p>
                    <ul>
                        <li>Pos Jasa Modal: 25% × Rp 100jt = Rp 25jt</li>
                        <li>Pos Jasa Usaha: 25% × Rp 100jt = Rp 25jt</li>
                        <li>SHU Jasa Modal Ibu Ani = (5/500) × 25jt = Rp 250.000</li>
                        <li>SHU Jasa Usaha Ibu Ani = (3/100) × 25jt = Rp 750.000</li>
                        <li><strong>Total SHU Ibu Ani = Rp 1.000.000</strong></li>
                    </ul>

                    <p>Dengan {{ $brand['nama'] }}, perhitungan SHU untuk 500 anggota di {{ $kota['nama'] }} selesai dalam <strong>5 detik</strong> — cukup klik, sistem langsung membaca data simpanan dan transaksi setiap anggota, hitung proporsinya, dan generate slip SHU siap kirim via WhatsApp.</p>
                    @break

                @case('jenis-simpanan-koperasi')
                    <p>Koperasi mengenal 4 jenis simpanan utama, masing-masing dengan karakter dan perlakuan akuntansi yang berbeda. Pengurus koperasi di {{ $kota['nama'] }} wajib memahami perbedaan ini agar tidak salah catat dan tidak salah komunikasi dengan anggota di {{ $kota['provinsi'] }}.</p>

                    <h2>1. Simpanan Pokok</h2>
                    <p>Disetor sekali saat anggota baru bergabung — biasanya Rp 100.000 sampai Rp 1 juta tergantung skala koperasi. Sifatnya <strong>permanen</strong>: tidak dapat ditarik selama menjadi anggota. Hanya bisa dikembalikan saat anggota mengundurkan diri atau diberhentikan.</p>

                    <h2>2. Simpanan Wajib</h2>
                    <p>Disetor rutin — umumnya bulanan — selama keanggotaan masih aktif, misal Rp 50.000/bulan. Sama seperti simpanan pokok, <strong>tidak dapat ditarik</strong> selama aktif. Akumulasinya jadi dasar perhitungan jasa modal SHU.</p>

                    <h2>3. Simpanan Sukarela</h2>
                    <p>Bersifat <strong>tabungan</strong> — bisa disetor dan ditarik kapan saja. Dicatat sebagai <strong>kewajiban (utang)</strong> koperasi ke anggota, bukan ekuitas. Beberapa koperasi memberi bunga rendah untuk jenis ini.</p>

                    <h2>4. Simpanan Berjangka (Deposito Koperasi)</h2>
                    <p>Anggota menyetor dengan tenor tetap (3, 6, 12 bulan) dan dapat imbal hasil lebih tinggi. Tidak bisa ditarik sebelum jatuh tempo tanpa penalty. Sangat populer di {{ $kota['nama'] }} karena bunga deposito koperasi umumnya lebih tinggi dari deposito bank — rata-rata 8–12% per tahun.</p>

                    <p>Software {{ $brand['nama'] }} memisahkan keempat jenis simpanan dalam modul terpisah — pengurus di {{ $kota['nama'] }} bisa mengatur parameter masing-masing (setoran minimum, biaya admin, bunga/nisbah) tanpa perlu bantuan teknis.</p>
                    @break

                @case('koperasi-syariah-vs-konvensional')
                    <p>Di {{ $kota['nama'] }}, {{ $kota['provinsi'] }}, koperasi syariah dan konvensional sama-sama berkembang pesat — tapi punya perbedaan fundamental dalam prinsip operasional yang harus dipahami pengurus sebelum memutuskan jenis koperasi yang akan didirikan.</p>

                    <h2>1. Sumber Pendapatan</h2>
                    <p><strong>Konvensional:</strong> bunga atas pinjaman — persentase tetap. <strong>Syariah:</strong> margin (jual-beli), bagi hasil (kerjasama), atau ujrah (sewa) sesuai akad yang dipakai.</p>
                    <h2>2. Akad Transaksi</h2>
                    <p><strong>Konvensional:</strong> akad utang-piutang. <strong>Syariah:</strong> 12 jenis akad spesifik — Mudharabah, Musyarakah, Murabahah, Ijarah, Salam, Istishna, Qardh, Rahn, Wakalah, Kafalah, Hawalah, IMBT — masing-masing untuk konteks berbeda.</p>
                    <h2>3. Struktur Pengawas</h2>
                    <p><strong>Konvensional:</strong> hanya Pengawas Manajemen. <strong>Syariah:</strong> ada tambahan Dewan Pengawas Syariah (DPS) bersertifikat DSN-MUI. Di {{ $kota['nama'] }}, DPS bisa direkrut dari ulama lokal atau akademisi ekonomi syariah dari universitas di {{ $kota['provinsi'] }}.</p>
                    <h2>4. Perlakuan Denda</h2>
                    <p><strong>Konvensional:</strong> denda menambah pendapatan koperasi. <strong>Syariah:</strong> denda tidak boleh jadi pendapatan — masuk ke Dana Kebajikan untuk disalurkan ke kegiatan sosial.</p>
                    <h2>5. Pelaporan</h2>
                    <p><strong>Konvensional:</strong> laporan PSAK 27 standar. <strong>Syariah:</strong> tambahan Laporan Sumber & Penggunaan Dana Zakat dan Dana Kebajikan, plus audit kepatuhan syariah oleh DPS.</p>

                    <p>Untuk koperasi di {{ $kota['nama'] }} yang ingin melayani kedua segmen, {{ $brand['nama'] }} mendukung <strong>mode dual-system</strong> — Konvensional & Syariah dalam 1 platform. Tinggal toggle per produk, laporan keuangan terpisah otomatis.</p>
                    @break

                @case('pembukuan-koperasi-digital')
                    <p>Di {{ $kota['nama'] }}, mayoritas koperasi skala mikro masih mengandalkan pembukuan manual — buku tulis untuk harian dan Excel untuk laporan bulanan. Namun seiring jumlah anggota dan transaksi membesar, pendekatan ini semakin tidak sustainable dan berisiko tinggi.</p>

                    <h2>Masalah Pembukuan Manual yang Dialami Koperasi {{ $kota['nama'] }}</h2>
                    <ul>
                        <li><strong>Error rumus Excel:</strong> Formula di-overwrite, sum range salah, referensi cell hilang saat insert baris baru — sering terjadi di koperasi yang bukunya dihandle oleh pengurus bergantian.</li>
                        <li><strong>Tidak ada audit trail:</strong> Tidak tahu siapa yang edit data, kapan, dan dari mana — masalah besar saat ada selisih antara kas fisik dan catatan buku.</li>
                        <li><strong>File berat & lambat:</strong> Excel dengan 10.000+ baris transaksi mulai macet, gampang corrupt, dan tidak bisa dibuka di HP. Padahal pengurus {{ $kota['nama'] }} sering perlu cek data saat di luar kantor.</li>
                        <li><strong>Tutup buku berhari-hari:</strong> Menjelang RAT, bendahara koperasi di {{ $kota['nama'] }} sering lembur 3–5 hari hanya untuk rekap manual ke neraca dan laba-rugi.</li>
                    </ul>

                    <h2>Manfaat Migrasi ke {{ $brand['nama'] }} untuk Koperasi {{ $kota['nama'] }}</h2>
                    <ul>
                        <li><strong>Error turun 90%.</strong> Sistem auto-jurnal — tidak ada celah salah ketik akun atau salah debet/kredit.</li>
                        <li><strong>Tutup buku 30 menit.</strong> Neraca, laba-rugi, arus kas ter-update real-time setiap ada transaksi baru.</li>
                        <li><strong>Multi-user dengan role.</strong> Kasir, akunting, ketua — masing-masing login dengan akses sesuai tugasnya.</li>
                        <li><strong>Backup otomatis.</strong> Data aman di cloud — tidak takut laptop rusak atau hilang.</li>
                        <li><strong>Anggota cek saldo sendiri via HP.</strong> Portal Anggota mengurangi beban admin yang kebanjiran pertanyaan "sisa pinjaman saya berapa ya, Bu?"</li>
                    </ul>

                    <h2>Cara Migrasi dari Excel ke Software</h2>
                    <ol>
                        <li>Tutup buku Excel di tanggal cut-off yang dipilih.</li>
                        <li>Export data anggota, simpanan, dan pinjaman ke file CSV.</li>
                        <li>Import ke {{ $brand['nama'] }} via template impor bawaan — satu klik.</li>
                        <li>Verifikasi saldo per anggota: cocokkan antara Excel lama dan software baru.</li>
                        <li>Set opening balance & mulai transaksi baru di sistem digital.</li>
                    </ol>
                    <p>Tim support {{ $brand['nama'] }} siap membantu migrasi data koperasi di {{ $kota['nama'] }} secara remote — gratis untuk pelanggan baru, termasuk import data 1.000+ anggota dalam 1 sesi.</p>
                    @break

                @case('rapat-anggota-tahunan-rat')
                    <p>Rapat Anggota Tahunan (RAT) adalah forum tertinggi koperasi yang wajib digelar setiap tahun. Di {{ $kota['nama'] }}, Dinas Koperasi {{ $kota['provinsi'] }} sangat ketat memantau RAT — koperasi yang 3 tahun berturut-turut tidak menyelenggarakan RAT bisa dibekukan izinnya.</p>

                    <h2>Persiapan Pra-RAT (1–2 Bulan Sebelum)</h2>
                    <ul>
                        <li>Tutup buku tahunan dan finalisasi laporan keuangan PSAK 27</li>
                        <li>Audit internal oleh Pengawas koperasi — verifikasi kewajaran laporan</li>
                        <li>Hitung distribusi SHU per anggota — jasa modal + jasa usaha</li>
                        <li>Susun laporan pertanggungjawaban pengurus</li>
                        <li>Susun draft rencana kerja & RAPB tahun berikutnya</li>
                    </ul>

                    <h2>Undangan & Kuorum</h2>
                    <p>Undangan dikirim minimal 14 hari sebelum RAT, lengkap dengan agenda, lampiran ringkasan laporan keuangan, dan tata cara hadir — offline di aula atau balai desa di {{ $kota['nama'] }}, atau online via Zoom untuk anggota yang merantau. Kuorum sah sesuai AD/ART — umumnya 50% + 1 dari total anggota (atau 2/3 untuk RAT yang mengubah AD/ART).</p>

                    <h2>Dokumen Wajib dari RAT</h2>
                    <ul>
                        <li>Berita Acara RAT (ditandatangani pimpinan rapat & sekretaris)</li>
                        <li>Daftar hadir anggota</li>
                        <li>Laporan keuangan yang disahkan</li>
                        <li>Rincian distribusi SHU per anggota</li>
                        <li>Rencana kerja & RAPB tahun berjalan</li>
                        <li>SK kepengurusan (bila ada pemilihan baru)</li>
                    </ul>

                    <p>Dinas Koperasi {{ $kota['provinsi'] }} mewajibkan semua dokumen ini diserahkan maksimal 30 hari setelah RAT. {{ $brand['nama'] }} menyediakan modul "Persiapan RAT" dengan satu klik export semua dokumen di atas — pengurus di {{ $kota['nama'] }} tinggal print, cap, dan serahkan.</p>
                    @break

                @default
                    <p>{{ $panduan['judul'] }} adalah topik penting bagi pengurus koperasi di {{ $kota['nama'] }}, {{ $kota['provinsi'] }}. Dengan <strong>{{ $kota['jumlah_koperasi'] }} koperasi aktif</strong> di wilayah ini, kebutuhan akan panduan yang praktis, aplikatif, dan spesifik konteks lokal sangat tinggi.</p>

                    <p>{{ $panduan['deskripsi'] }} Kami di {{ $brand['nama'] }} memahami bahwa setiap koperasi di {{ $kota['nama'] }} memiliki karakteristik unik — mulai dari skala usaha, profil anggota, hingga akses terhadap infrastruktur digital. Karena itu, software kami dirancang dengan fleksibilitas tinggi: bisa diakses dari laptop kantor, dari HP pengurus saat di lapangan, atau dari tablet anggota di rumah.</p>

                    <h3>Mengapa Pengurus di {{ $kota['nama'] }} Memilih {{ $brand['nama'] }}</h3>
                    <p>{{ $brand['tagline'] }}. Dengan harga mulai {{ $brand['harga_mulai'] }}, koperasi di {{ $kota['nama'] }} mendapatkan sistem lengkap yang mencakup:</p>
                    <ul>
                        @foreach($brand['fitur'] as $f)
                            <li>{{ $f }}</li>
                        @endforeach
                    </ul>
                    <p>Semua fitur di atas sudah termasuk dalam langganan — tidak ada biaya tambahan per modul, tidak ada batasan jumlah anggota, tidak ada biaya setup tersembunyi. Tim support siap membantu via chat atau remote session kapan pun koperasi Anda di {{ $kota['nama'] }} butuh bantuan.</p>

                    <h3>Langkah Selanjutnya</h3>
                    <p>Jika Anda adalah pengurus koperasi di {{ $kota['nama'] }} yang ingin mendalami topik {{ $panduan['judul'] }}, kami merekomendasikan langkah-langkah berikut:</p>
                    <ol>
                        <li>Pelajari panduan terkait di bawah ini untuk memperluas wawasan.</li>
                        <li>Coba {{ $brand['nama'] }} gratis 14 hari — login, input data dummy, dan eksplorasi semua fitur tanpa risiko.</li>
                        <li>Hubungi tim support kami untuk sesi demo 30 menit khusus kebutuhan koperasi Anda di {{ $kota['nama'] }}.</li>
                    </ol>
                    <p>Koperasi di {{ $kota['nama'] }} yang sudah digital dengan {{ $brand['nama'] }} rata-rata mengalami peningkatan efisiensi operasional hingga 70% dan penurunan NPL hingga 3% dalam 6 bulan pertama — karena monitoring pinjaman real-time dan notifikasi otomatis ke anggota yang telat bayar.</p>
            @endswitch

        </div>
    </article>

    <section class="my-12">
        <div class="rounded-3xl gradient-bg-dark p-10 md:p-14 text-white overflow-hidden relative shadow-2xl shadow-emerald-500/30">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-teal-400/20 rounded-full blur-3xl"></div>
            <div class="relative grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-3xl md:text-4xl font-extrabold mb-3">Siap Terapkan di {{ $kota['nama'] }}?</h3>
                    <p class="text-emerald-100 mb-6 text-lg leading-relaxed">Mulai dari <strong>{{ $brand['harga_mulai'] }}</strong>. Trial 14 hari gratis, tanpa kartu kredit. Tim support siap bantu setup dan migrasi data dari Excel Anda di {{ $kota['nama'] }}.</p>
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

    <section class="mb-12">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Panduan Lain untuk Koperasi di {{ $kota['nama'] }}</h2>
        <p class="text-slate-600 mb-8">Pelajari panduan koperasi lainnya yang relevan untuk pengurus di {{ $kota['nama'] }}, {{ $kota['provinsi'] }}:</p>
        <div class="grid md:grid-cols-2 gap-5">
            @foreach(collect(config('pseo.panduan'))->reject(fn($p) => $p['slug'] === $panduan['slug'])->take(6) as $p)
                <a href="{{ route('seo.panduan-kota', [$p['slug'], $kota['slug']]) }}" class="card-hover bg-white rounded-2xl border border-emerald-100 p-6 block">
                    <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded mb-3">{{ $p['estimasi_baca'] }} menit baca</span>
                    <h3 class="font-extrabold text-base mb-2 text-slate-900">{{ $p['judul'] }}</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $p['deskripsi'] }}</p>
                    <span class="text-emerald-600 font-semibold text-sm mt-2 inline-flex items-center gap-1">Baca di {{ $kota['nama'] }} →</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Panduan Ini di Kota Lain</h2>
        <p class="text-slate-600 mb-8">Baca panduan "{{ $panduan['judul'] }}" dalam konteks kota-kota lain di Indonesia.</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach(collect(config('pseo.kota'))->reject(fn($k) => $k['slug'] === $kota['slug'])->take(8) as $k)
                <a href="{{ route('seo.panduan-kota', [$panduan['slug'], $k['slug']]) }}" class="bg-white rounded-xl border border-emerald-100 p-4 hover:border-emerald-400 hover:shadow-md transition">
                    <div class="font-bold text-slate-900 text-sm">{{ $panduan['judul'] }}</div>
                    <div class="text-xs text-slate-500">di {{ $k['nama'] }}, {{ $k['provinsi'] }}</div>
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
