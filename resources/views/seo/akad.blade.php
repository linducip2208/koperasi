@extends('seo._layout')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12">

    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <a href="{{ url('/#syariah') }}" class="hover:text-indigo-600">Akad Syariah</a>
        <span>/</span>
        <span class="text-slate-700">{{ $akad['nama'] }}</span>
    </nav>

    <header class="mb-12">
        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">Akad Syariah · {{ $akad['kategori'] }}</span>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
            Akad <span class="gradient-text">{{ $akad['nama'] }}</span>
        </h1>
        <p class="text-xl md:text-2xl text-slate-700 font-medium">{{ $akad['ringkas'] }}</p>
    </header>

    <section class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 mb-10 shadow-sm">
        <h2 class="text-2xl font-extrabold mb-4 text-slate-900">Apa itu Akad {{ $akad['nama'] }}?</h2>
        <div class="prose-custom">
            <p>{{ $akad['deskripsi'] }}</p>
        </div>
    </section>

    <section class="grid md:grid-cols-2 gap-6 mb-10">
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-100 p-8">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Rumus Perhitungan</span>
            <h3 class="font-extrabold text-xl mb-3 mt-1 text-slate-900">Cara Menghitung</h3>
            <div class="bg-white rounded-xl p-5 font-mono text-sm text-slate-800 leading-relaxed shadow-sm">
                {{ $akad['rumus'] }}
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border border-emerald-100 p-8">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Contoh Kasus Nyata</span>
            <h3 class="font-extrabold text-xl mb-3 mt-1 text-slate-900">Studi Kasus</h3>
            <p class="text-slate-700 leading-relaxed">{{ $akad['contoh'] }}</p>
        </div>
    </section>

    <section class="bg-amber-50 border-2 border-amber-200 rounded-2xl p-6 mb-12 flex gap-4 items-start">
        <div class="bg-amber-500 rounded-xl p-3 flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <h3 class="font-extrabold text-amber-900 mb-1">Dasar Hukum Syariah</h3>
            <p class="text-amber-800 text-sm leading-relaxed">{{ $akad['fatwa'] }} — Fatwa resmi Dewan Syariah Nasional (DSN-MUI) yang menjadi rujukan penerapan akad ini di koperasi syariah Indonesia.</p>
        </div>
    </section>

    <section class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 mb-12">
        <h2 class="text-3xl font-extrabold mb-4 text-slate-900">Implementasi Akad {{ $akad['nama'] }} di {{ $brand['nama'] }}</h2>
        <div class="prose-custom">
            <p>Software koperasi syariah {{ $brand['nama'] }} mendukung penuh akad <strong>{{ $akad['nama'] }}</strong> beserta 9 akad syariah lainnya (mudharabah, musyarakah, murabahah, ijarah, salam, istishna, qardh, wakalah, kafalah, hawalah). Setiap produk pinjaman atau pembiayaan yang dibuat di Filament Admin bisa dipilih akadnya — lalu sistem otomatis:</p>
            <ul>
                <li><strong>Memilih chart of accounts (CoA)</strong> yang sesuai untuk akad tersebut. Akad bagi hasil pakai akun "Pendapatan Bagi Hasil"; akad jual-beli pakai akun "Margin Murabahah"; akad sewa pakai akun "Pendapatan Ijarah".</li>
                <li><strong>Menjurnal otomatis</strong> setiap transaksi yang menggunakan akad ini, tanpa perlu intervensi manual oleh bagian akuntansi.</li>
                <li><strong>Generate akad agreement</strong> dalam format PDF dengan pasal-pasal standar fatwa DSN-MUI yang relevan, siap di-print dan ditandatangani anggota.</li>
                <li><strong>Validasi syarat sah akad</strong> — misal mudharabah tidak boleh kerugian atas pengelola, murabahah harga harus diketahui kedua pihak — sebelum transaksi disetujui.</li>
                <li><strong>Laporan terpisah per akad</strong> di laporan keuangan, sehingga Dewan Pengawas Syariah (DPS) bisa audit dengan mudah.</li>
            </ul>

            <p>Bagi koperasi yang dual-mode (melayani anggota syariah dan konvensional sekaligus), {{ $brand['nama'] }} memungkinkan toggle akad per produk — anggota muslim bisa memilih produk pembiayaan dengan akad {{ $akad['nama'] }}, sementara anggota umum tetap memakai produk pinjaman bunga konvensional. Laporan keuangan tetap terpisah dan compliant dengan masing-masing standar.</p>
        </div>
    </section>

    @include('seo._cta')

    @include('seo._faq')

    <section class="my-16">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Akad Syariah Lainnya</h2>
        <p class="text-slate-600 mb-8">{{ $brand['nama'] }} mendukung {{ count(config('pseo.akad')) }} akad syariah lengkap sesuai fatwa DSN-MUI.</p>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($akadLain as $a)
                <a href="{{ route('seo.akad', $a['slug']) }}" class="card-hover bg-white rounded-2xl border border-slate-200 p-5 block">
                    <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded mb-2">{{ $a['kategori'] }}</span>
                    <h3 class="font-extrabold text-base mb-1 text-slate-900">{{ $a['nama'] }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $a['ringkas'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
