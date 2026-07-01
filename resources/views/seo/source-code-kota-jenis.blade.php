@extends('pseo._layout', ['title' => $seoTitle, 'description' => $seoDescription, 'canonical' => $canonical])

@section('content')
<div class="prose prose-stone max-w-none"><h2>Source Code {{ $jenis['singkatan'] }} untuk {{ $kota['nama'] }}</h2><p>{{ $jenis['deskripsi'] }}</p><p>Harga mulai <strong>{{ $brand['harga_mulai'] }}</strong>. <a href="https://wa.me/6281296052010" class="text-emerald-600 font-bold">WA 0812-9605-2010</a>.</p></div>
@include('seo._cta')
@endsection
