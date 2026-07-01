@extends('pseo._layout', ['title' => $seoTitle, 'description' => $seoDescription, 'canonical' => $canonical, 'heroH1' => "Jual Source Code Koperasi — {$kota['nama']}", 'heroSub' => $kota['provinsi']])

@section('content')
<div class="prose prose-stone max-w-none">
    <h2>Beli Source Code Aplikasi Koperasi untuk {{ $kota['nama'] }}</h2>
    <p>Cari software koperasi yang bisa dipasang sendiri di {{ $kota['nama'] }}? <strong>{{ $brand['nama'] }}</strong> menyediakan source code lengkap — Anda tinggal install, rebrand, dan jalankan. Cocok untuk koperasi di {{ $kota['nama'] }}, {{ $kota['provinsi'] }}.</p>
    <h3>Yang termasuk:</h3>
    <ul>@foreach($brand['fitur'] as $f)<li>{{ $f }}</li>@endforeach</ul>
    <p>Harga mulai <strong>{{ $brand['harga_mulai'] }}</strong>. <a href="https://wa.me/6281296052010" class="text-emerald-600 font-bold">WA 0812-9605-2010</a>.</p>
</div>
@include('seo._cta')
@endsection
