@extends('pseo._layout', ['title' => $seoTitle, 'description' => $seoDescription, 'canonical' => $canonical, 'heroH1' => "Aplikasi dengan {$fitur['nama']}", 'heroSub' => 'Source code siap pakai'])

@section('content')
<div class="prose prose-stone max-w-none"><p>{{ $fitur['desc'] }}</p><p><strong>{{ $brand['nama'] }}</strong> menyediakan fitur ini dan 15+ modul lainnya. Harga mulai <strong>{{ $brand['harga_mulai'] }}</strong>. <a href="https://wa.me/6281296052010" class="text-emerald-600 font-bold">WA 0812-9605-2010</a>.</p></div>
@include('seo._cta')
@endsection
