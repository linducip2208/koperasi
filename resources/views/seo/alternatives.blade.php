@extends('seo._layout')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12">

    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <span class="text-slate-700">Alternatif {{ $competitor['nama'] }}</span>
    </nav>

    <header class="mb-12 max-w-3xl">
        <span class="inline-block bg-pink-100 text-pink-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">{{ $year }} · Software Koperasi</span>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
            Alternatif <span class="gradient-text">{{ $competitor['nama'] }}</span> Terbaik {{ $year }}
        </h1>
        <p class="text-lg text-slate-600 leading-relaxed">Mencari alternatif {{ $competitor['nama'] }} ({{ $competitor['tagline'] }})? Berikut 5 software koperasi terbaik yang bisa jadi pengganti — dengan fitur lebih lengkap, mode dual-syariah, dan dukungan migrasi data.</p>
    </header>

    <section class="bg-white rounded-3xl border-2 border-indigo-200 p-8 md:p-12 mb-10 shadow-xl shadow-indigo-100">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-yellow-400 text-slate-900 font-bold px-3 py-1 rounded-lg text-sm">#1 ALTERNATIF TERBAIK</span>
        </div>
        <h2 class="text-3xl md:text-4xl font-extrabold mb-3">{{ $brand['nama'] }}</h2>
        <p class="text-slate-600 mb-6 text-lg">{{ $brand['tagline'] }}</p>
        <h3 class="font-extrabold text-lg mb-3 text-slate-900">Kelebihan dibanding {{ $competitor['nama'] }}:</h3>
        <ul class="grid md:grid-cols-2 gap-3 mb-8">
            @foreach($brand['fitur'] as $f)
                <li class="flex gap-3 items-start">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-slate-700">{{ $f }}</span>
                </li>
            @endforeach
        </ul>
        <a href="{{ url('/#harga') }}" class="gradient-bg text-white font-bold px-6 py-3 rounded-xl shadow-lg inline-block">Coba {{ $brand['nama'] }} Gratis →</a>
    </section>

    <section class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 mb-12">
        <h2 class="text-3xl font-extrabold mb-4 text-slate-900">Mengapa Beralih dari {{ $competitor['nama'] }}?</h2>
        <div class="prose-custom">
            <p>{{ $competitor['nama'] }} adalah salah satu software koperasi yang cukup dikenal di Indonesia, namun banyak koperasi melaporkan keterbatasan saat skala mulai membesar atau saat butuh fitur lebih spesifik (terutama dukungan akad syariah dan multi-tenant SaaS).</p>
            <h3>Kekurangan umum yang sering dikeluhkan pengguna {{ $competitor['nama'] }}:</h3>
            <ul>
                <li>Antarmuka admin yang dated/lawas — kurang nyaman untuk pengurus generasi muda</li>
                <li>Tidak ada portal anggota mobile — anggota tetap harus telepon admin untuk cek saldo</li>
                <li>Mode syariah terbatas atau tidak ada — koperasi syariah harus pakai workaround</li>
                <li>Migrasi data awal sulit — butuh entry manual ribuan baris</li>
                <li>Update fitur lambat — versi baru jarang dirilis</li>
                <li>Support response lama — tiket bisa stuck berhari-hari</li>
            </ul>
            <p>Dengan {{ $brand['nama'] }} (dibangun di atas Laravel 12 + Filament 3.3 modern), pengurus mendapat pengalaman yang segar: admin panel cepat dan responsif, anggota bisa cek saldo dari HP via portal, mode dual-syariah bawaan tanpa workaround, dan import data via CSV 1-klik. Tim support {{ $brand['nama'] }} biasanya respond dalam 1 jam pada jam kerja, dan migrasi awal dibantu gratis.</p>
        </div>
    </section>

    <section class="my-16">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Software Koperasi Lainnya</h2>
        <p class="text-slate-600 mb-8">Bandingkan {{ $competitor['nama'] }} dengan software koperasi lain.</p>
        <div class="grid md:grid-cols-2 gap-5">
            @foreach($competitorLain as $c)
                <a href="{{ route('seo.compare', 'koperasi-app-vs-' . $c['slug']) }}" class="card-hover bg-white rounded-2xl border border-slate-200 p-6 block">
                    <h3 class="font-extrabold text-lg mb-1 text-slate-900">{{ $brand['nama'] }} vs {{ $c['nama'] }}</h3>
                    <p class="text-sm text-slate-600">{{ $c['tagline'] }} — bandingkan head-to-head.</p>
                </a>
            @endforeach
        </div>
    </section>

    @include('seo._cta')

</div>
@endsection
