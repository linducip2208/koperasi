@extends('pseo._layout', ['title' => $seoTitle, 'description' => $seoDescription, 'canonical' => $canonical ?? url()->current(), 'heroH1' => "{$jenis['nama']} di {$kec['nama']}", 'heroSub' => "{$kec['kota']}, {$kec['provinsi']}"])

@section('content')
<div class="prose prose-stone max-w-none">
    <p>{{ $jenis['deskripsi'] }}</p>

    <h2>{{ $jenis['singkatan'] }} di {{ $kec['nama'] }} — Fitur</h2>
    <ul>@foreach($jenis['fitur_utama'] as $f)<li>{{ $f }}</li>@endforeach</ul>

    <h2>Regulasi</h2>
    <p>{{ $jenis['regulasi'] }}</p>
</div>
@include('seo._cta', ['brand' => $brand])
@endsection
