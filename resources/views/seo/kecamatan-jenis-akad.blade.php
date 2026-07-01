@extends('pseo._layout', ['title' => $seoTitle, 'description' => $seoDescription, 'canonical' => $canonical ?? url()->current(), 'heroH1' => "{$jenis['singkatan']} Akad {$akad['nama']}", 'heroSub' => "{$kec['nama']}, {$kec['kota']}"])

@section('content')
<div class="prose prose-stone max-w-none">
    <p>{{ $akad['deskripsi'] }}</p>
    <p><strong>Rumus:</strong> {{ $akad['rumus'] }}</p>
    <p><strong>Contoh:</strong> {{ $akad['contoh'] }}</p>
    <p><strong>Dasar:</strong> {{ $akad['fatwa'] }}</p>
</div>
@include('seo._cta', ['brand' => $brand])
@include('seo._faq', ['faqs' => $faqs])
@endsection
