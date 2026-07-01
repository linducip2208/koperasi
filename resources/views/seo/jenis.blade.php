@extends('seo._layout')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">

    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <a href="{{ url('/#fitur') }}" class="hover:text-indigo-600">Jenis Koperasi</a>
        <span>/</span>
        <span class="text-slate-700">{{ $jenis['singkatan'] }}</span>
    </nav>

    <header class="mb-12 max-w-4xl">
        <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">{{ $jenis['singkatan'] }} · Panduan {{ $year }}</span>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
            <span class="gradient-text">{{ $jenis['nama'] }}</span>
        </h1>
        <p class="text-xl md:text-2xl text-slate-700 font-medium mb-3">{{ $jenis['tagline'] }}</p>
        <p class="text-slate-600 leading-relaxed">{{ $jenis['deskripsi'] }}</p>
    </header>

    <section class="grid md:grid-cols-2 gap-6 mb-16">
        <div class="bg-white rounded-2xl border border-slate-200 p-8">
            <h2 class="font-extrabold text-xl mb-4 text-slate-900">Fitur Utama yang Wajib Ada</h2>
            <ul class="space-y-3">
                @foreach($jenis['fitur_utama'] as $f)
                    <li class="flex gap-3 items-start">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-slate-700">{{ $f }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-xl shadow-indigo-500/20">
            <span class="text-xs font-bold uppercase tracking-wider text-white/70">Dasar Hukum</span>
            <h2 class="font-extrabold text-2xl mb-3 mt-1">Regulasi Pemerintah</h2>
            <p class="text-white/90 leading-relaxed mb-6">{{ $jenis['regulasi'] }}</p>
            <p class="text-sm text-white/80">Software {{ $brand['nama'] }} mengikuti format laporan dan struktur akun sesuai regulasi ini, sehingga koperasi {{ $jenis['singkatan'] }} bisa langsung serahkan laporan ke Dinas Koperasi tanpa konversi manual.</p>
        </div>
    </section>

    <section class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 mb-16">
        <h2 class="text-3xl font-extrabold mb-4 text-slate-900">Panduan Lengkap Mengelola {{ $jenis['nama'] }}</h2>
        <div class="prose-custom max-w-none">
            <p>{{ $jenis['nama'] }} ({{ $jenis['singkatan'] }}) memiliki karakteristik operasional yang berbeda dengan jenis koperasi lain. Pengelolaan harian, struktur produk, dan kewajiban pelaporan masing-masing punya kekhasan tersendiri yang harus dipahami pengurus sebelum mulai beroperasi.</p>

            <h3>1. Struktur Produk & Layanan</h3>
            <p>Untuk {{ $jenis['singkatan'] }}, fokus utama operasional adalah pada layanan inti yang sudah disebutkan dalam fitur utama di atas. Setiap produk wajib didaftarkan di software dengan parameter lengkap: tipe produk, syarat keanggotaan, plafon, tenor, suku bunga atau nisbah bagi hasil, biaya administrasi, dan ketentuan denda keterlambatan. {{ $brand['nama'] }} memungkinkan pengurus mendefinisikan produk simpanan/pinjaman dengan formula sendiri tanpa coding.</p>

            <h3>2. Manajemen Keanggotaan</h3>
            <p>Setiap anggota harus terdaftar dengan KTP, NIK, alamat lengkap, dan bukti pembayaran simpanan pokok. {{ $jenis['singkatan'] }} biasanya juga merekam data tambahan — untuk KSP butuh data pekerjaan dan slip gaji, untuk Kopkar butuh NIP karyawan, untuk KSPPS butuh data agama dan kemampuan beragama. Data ini dipakai untuk validasi pengajuan pinjaman/pembiayaan dan perhitungan SHU jasa modal.</p>

            <h3>3. Akuntansi & Tutup Buku</h3>
            <p>Setiap transaksi simpanan, pinjaman, atau penjualan wajib otomatis menjurnal sesuai PSAK 27 (Akuntansi Koperasi) atau PSAK ETAP/EMKM. Tutup buku bulanan menghasilkan: Neraca, Laporan Laba/Rugi, Laporan Arus Kas, dan untuk KSPPS tambahan Laporan Sumber & Penggunaan Dana Zakat dan Dana Kebajikan. {{ $brand['nama'] }} menjalankan ini otomatis — pengurus tinggal review dan tanda tangan.</p>

            <h3>4. Sisa Hasil Usaha (SHU)</h3>
            <p>Akhir tahun, total surplus/defisit dialokasikan ke pos-pos sesuai AD/ART: dana cadangan, dana pendidikan, dana sosial, jasa modal anggota, dan jasa usaha anggota. {{ $jenis['singkatan'] }} biasanya pakai formula 25% jasa modal + 25% jasa usaha + 50% pos lain (cadangan, pendidikan, sosial, pengurus). {{ $brand['nama'] }} otomatis hitung SHU per anggota dan generate slip distribusi yang bisa di-print atau kirim via WhatsApp.</p>

            <h3>5. Rapat Anggota Tahunan (RAT)</h3>
            <p>Setiap akhir tahun buku, {{ $jenis['singkatan'] }} wajib menggelar RAT dengan agenda pengesahan laporan keuangan, distribusi SHU, dan rencana kerja tahun berjalan. {{ $brand['nama'] }} menyediakan modul export laporan RAT dalam format PDF siap presentasi — neraca, laba-rugi, distribusi SHU per anggota, dan analisis rasio keuangan koperasi.</p>
        </div>
    </section>

    @include('seo._cta')

    @include('seo._faq')

    <section class="my-16">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Jenis Koperasi Lainnya</h2>
        <p class="text-slate-600 mb-8">Pelajari panduan jenis koperasi lain yang didukung {{ $brand['nama'] }}.</p>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($jenisLain as $j)
                <a href="{{ route('seo.jenis', $j['slug']) }}" class="card-hover bg-white rounded-2xl border border-slate-200 p-6 block">
                    <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-1 rounded mb-3">{{ $j['singkatan'] }}</span>
                    <h3 class="font-extrabold text-lg mb-2 text-slate-900">{{ $j['nama'] }}</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $j['tagline'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="my-16">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Kota dengan Koperasi {{ $jenis['singkatan'] }} Aktif</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($kotaPopuler as $k)
                <a href="{{ route('seo.kota', $k['slug']) }}" class="bg-white rounded-xl border border-slate-200 p-4 hover:border-indigo-400 hover:shadow-md transition">
                    <div class="font-bold text-slate-900 text-sm">Koperasi di {{ $k['nama'] }}</div>
                    <div class="text-xs text-slate-500">{{ $k['provinsi'] }}</div>
                </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
