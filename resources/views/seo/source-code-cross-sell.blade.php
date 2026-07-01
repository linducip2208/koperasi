@extends('pseo._layout', ['title' => $seoTitle, 'description' => $seoDescription, 'canonical' => $canonical])

@section('content')
<div class="prose prose-stone max-w-none"><h2>Beli Aplikasi {{ $app['nama'] }}</h2><p>{{ $app['desc'] }}</p><p>Source code lengkap, instalasi mudah, support penuh. <a href="https://wa.me/6281296052010" class="text-emerald-600 font-bold">WA 0812-9605-2010</a>.</p></div>
@include('seo._cta')
@endsection
