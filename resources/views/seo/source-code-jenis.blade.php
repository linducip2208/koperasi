@extends('pseo._layout', ['title' => $seoTitle, 'description' => $seoDescription, 'canonical' => $canonical, 'heroH1' => "Beli Aplikasi {$jenis['nama']}", 'heroSub' => $jenis['tagline']])

@section('content')
<div class="prose prose-stone max-w-none"><p>{{ $jenis['deskripsi'] }}</p><h2>Fitur {{ $jenis['singkatan'] }}</h2><ul>@foreach($jenis['fitur_utama'] as $f)<li>{{ $f }}</li>@endforeach</ul><p>Harga mulai <strong>{{ $brand['harga_mulai'] }}</strong>. <a href="https://wa.me/6281296052010" class="text-emerald-600 font-bold">WA 0812-9605-2010</a>.</p></div>
@include('seo._cta')
@endsection
