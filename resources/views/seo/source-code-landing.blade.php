@extends('pseo._layout', ['title' => $seoTitle, 'description' => $seoDescription, 'canonical' => $canonical ?? url()->current(), 'heroH1' => 'Beli Source Code Aplikasi Koperasi', 'heroSub' => 'Konvensional & Syariah — Siap Pakai'])

@section('content')
<div class="prose prose-stone max-w-none">
    <h2>Dapatkan Source Code Aplikasi Koperasi Lengkap</h2>
    <p>{{ $brand['nama'] }} adalah software koperasi paling lengkap di Indonesia. Anda bisa <strong>membeli source code-nya</strong> — instalasi 10 menit, bisa direbrand, hosting sendiri, bahkan dijual kembali.</p>

    <h3>Yang Anda Dapatkan:</h3>
    <ul>
        @foreach($brand['fitur'] as $f)<li>{{ $f }}</li>@endforeach
        <li>Instalasi 10 menit + panduan lengkap</li>
        <li>Support teknis 1 tahun</li>
        <li>Update bug fix & security patch</li>
    </ul>

    <h3>Harga</h3>
    <p>Mulai dari <strong>{{ $brand['harga_mulai'] }}</strong>. Tersedia 3 paket: Regular (1 domain), Growth (3 domain), Whitelabel (unlimited + hak jual).</p>
</div>

{{-- Cross-sell apps --}}
<div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-3">
    @foreach($apps as $slug => $app)
        <a href="{{ url('/beli-aplikasi-' . $slug) }}" class="block p-4 bg-white rounded-xl border border-stone-200 hover:border-emerald-300 hover:shadow-md transition text-center card-lift">
            <div class="text-2xl mb-2">{{ $app['icon'] }}</div>
            <div class="font-semibold text-sm text-stone-800">{{ $app['nama'] }}</div>
        </a>
    @endforeach
</div>

@include('seo._faq', ['faqs' => $faqs])
@include('seo._cta')
@endsection
