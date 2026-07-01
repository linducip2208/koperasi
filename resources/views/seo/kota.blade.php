@extends('seo._layout')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">

    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <span class="text-slate-700">Aplikasi Koperasi {{ $kota['nama'] }}</span>
    </nav>

    <header class="mb-12">
        <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">{{ $kota['provinsi'] }} · {{ $year }}</span>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
            Aplikasi <span class="gradient-text">Koperasi Terbaik</span><br>di {{ $kota['nama'] }} {{ $year }}
        </h1>
        <p class="text-lg md:text-xl text-slate-600 max-w-3xl leading-relaxed">
            Daftar software/aplikasi koperasi terbaik untuk pengurus koperasi di {{ $kota['nama'] }}. {{ $kota['nama'] }} memiliki sekitar <strong>{{ $kota['jumlah_koperasi'] }} koperasi aktif</strong> di {{ $kota['provinsi'] }} — dari Koperasi Simpan Pinjam, Serba Usaha, hingga Koperasi Syariah. Bandingkan fitur, harga, dan dukungan modul akuntansi PSAK 27.
        </p>
    </header>

    <section class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 mb-12 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-yellow-400 text-slate-900 font-bold px-3 py-1 rounded-lg text-sm">#1 PILIHAN</span>
            <span class="text-emerald-600 font-semibold text-sm">⭐ Rekomendasi Editor</span>
        </div>
        <h2 class="text-3xl md:text-4xl font-extrabold mb-3">{{ $brand['nama'] }} — Solusi Terlengkap untuk Koperasi {{ $kota['nama'] }}</h2>
        <p class="text-slate-600 mb-6 text-lg leading-relaxed">{{ $brand['tagline'] }}. Cocok untuk koperasi di {{ $kota['nama'] }} yang ingin migrasi dari pencatatan manual atau Excel ke sistem digital lengkap.</p>

        <ul class="grid md:grid-cols-2 gap-3 mb-8">
            @foreach($brand['fitur'] as $f)
                <li class="flex gap-3 items-start">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-slate-700">{{ $f }}</span>
                </li>
            @endforeach
        </ul>

        <div class="flex flex-wrap gap-3 items-center">
            <a href="{{ url('/#harga') }}" class="gradient-bg text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition">Coba Gratis di {{ $kota['nama'] }}</a>
            <span class="text-sm text-slate-500">Mulai dari <strong class="text-slate-900">{{ $brand['harga_mulai'] }}</strong> · trial 14 hari</span>
        </div>
    </section>

    <section class="mb-16">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Software Koperasi per Jenis di {{ $kota['nama'] }}</h2>
        <p class="text-slate-600 mb-8">Pilih jenis koperasi yang Anda kelola untuk lihat panduan dan modul yang relevan.</p>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($jenisKoperasi as $j)
                <a href="{{ route('seo.jenis', $j['slug']) }}" class="card-hover bg-white rounded-2xl border border-slate-200 p-6 block">
                    <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-1 rounded mb-3">{{ $j['singkatan'] }}</span>
                    <h3 class="font-extrabold text-lg mb-2 text-slate-900">{{ $j['nama'] }}</h3>
                    <p class="text-sm text-slate-600 mb-4 leading-relaxed">{{ $j['tagline'] }}</p>
                    <span class="text-indigo-600 font-semibold text-sm inline-flex items-center gap-1">Pelajari →</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 mb-16">
        <h2 class="text-3xl font-extrabold mb-4 text-slate-900">Mengapa Koperasi di {{ $kota['nama'] }} Butuh Software Modern?</h2>
        <div class="prose-custom max-w-none">
            <p>Pertumbuhan koperasi di {{ $kota['nama'] }} ({{ $kota['provinsi'] }}) cukup pesat — tercatat <strong>{{ $kota['jumlah_koperasi'] }} unit aktif</strong> per data Kementerian Koperasi UKM terbaru. Namun mayoritas masih menggunakan pembukuan manual: buku besar tulis tangan, Excel terpisah-pisah per pengurus, atau aplikasi accounting umum yang tidak dirancang untuk struktur PSAK 27 (Akuntansi Koperasi).</p>

            <p>Akibatnya: <strong>laporan keuangan koperasi telat berhari-hari menjelang RAT</strong>, perhitungan SHU per anggota error, simpanan-pinjaman tidak ter-rekonsiliasi dengan kas. Kondisi ini bukan hanya bikin pengurus stress menjelang Rapat Anggota Tahunan — tapi juga bikin koperasi sulit naik kelas: tidak bisa ekspansi, sulit dapat pinjaman ke induk, dan rentan temuan saat audit Dinas Koperasi.</p>

            <h3>3 masalah klasik koperasi {{ $kota['nama'] }} yang dipecahkan software modern</h3>
            <ul>
                <li><strong>Tutup buku bulanan butuh 3 hari → 30 menit.</strong> Jurnal otomatis dari setiap setoran simpanan dan pelunasan pinjaman; trial balance, neraca, dan laba-rugi ter-update real-time.</li>
                <li><strong>Hitung SHU 500 anggota tidak lagi pakai kalkulator.</strong> Engine SHU built-in sesuai AD/ART (jasa modal + jasa usaha), satu klik export ke PDF dan kirim notifikasi WhatsApp ke anggota.</li>
                <li><strong>Anggota bisa cek saldo dari HP.</strong> Portal Anggota mobile-friendly: cek simpanan, cicilan pinjaman, riwayat transaksi, dan ajukan pinjaman online — tanpa harus ke kantor koperasi.</li>
            </ul>

            <h3>Untuk siapa software ini cocok?</h3>
            <p>Software koperasi {{ $brand['nama'] }} cocok untuk semua skala koperasi di {{ $kota['nama'] }} — mulai dari koperasi RT/RW dengan 30 anggota, KPRI instansi pemerintah dengan 800 PNS, hingga Koperasi Serba Usaha dengan beberapa unit (toko, simpan-pinjam, jasa). Mode dual-syariah memungkinkan satu instalasi melayani anggota muslim (akad mudharabah, murabahah, ijarah) dan anggota umum (sistem bunga konvensional) sekaligus — tanpa harus beli 2 software berbeda.</p>

            <p>Khusus untuk Koperasi Pondok Pesantren (Kopontren) yang banyak tersebar di {{ $kota['provinsi'] }}, tersedia preset akad syariah lengkap dengan fatwa DSN-MUI sebagai dasar — sehingga pengurus pesantren tidak perlu menerjemahkan sendiri prinsip syariah ke dalam jurnal akuntansi.</p>
        </div>
    </section>

    @include('seo._cta')

    <section class="my-16">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Lihat juga koperasi di kota lain</h2>
        <p class="text-slate-600 mb-8">Software {{ $brand['nama'] }} sudah dipakai koperasi di {{ count(config('pseo.kota')) }}+ kota di Indonesia.</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($kotaTerkait as $k)
                <a href="{{ route('seo.kota', $k['slug']) }}" class="bg-white rounded-xl border border-slate-200 p-4 hover:border-indigo-400 hover:shadow-md transition">
                    <div class="font-bold text-slate-900 text-sm">{{ $k['nama'] }}</div>
                    <div class="text-xs text-slate-500">{{ $k['provinsi'] }}</div>
                </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
