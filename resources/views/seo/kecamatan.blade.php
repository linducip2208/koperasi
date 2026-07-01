@extends('pseo._layout', ['title' => $seoTitle, 'description' => $seoDescription, 'canonical' => $canonical ?? url()->current(), 'heroH1' => "Koperasi di {$kec['nama']}", 'heroSub' => "{$kec['kota']}, {$kec['provinsi']}"])

@section('content')
<div class="prose prose-stone max-w-none">
    <p>{{ $kec['nama'] }} adalah salah satu kecamatan di {{ $kec['kota'] }}, Provinsi {{ $kec['provinsi'] }}. Koperasi di {{ $kec['nama'] }} membutuhkan software yang handal untuk mengelola simpanan, pinjaman, dan unit usaha. <strong>{{ $brand['nama'] }}</strong> hadir sebagai solusi aplikasi koperasi terlengkap — mendukung dual-mode <strong>Konvensional & Syariah</strong>.</p>

    <h2>Fitur Aplikasi Koperasi untuk {{ $kec['nama'] }}</h2>
    <ul>
        @foreach($brand['fitur'] as $f)<li>{{ $f }}</li>@endforeach
    </ul>

    <h2>Kenapa Koperasi di {{ $kec['nama'] }} Butuh Software?</h2>
    <p>Manual dengan Excel rawan error, lambat, dan tidak bisa multi-user. Software koperasi seperti {{ $brand['nama'] }} mengotomatisasi pencatatan, mengurangi risiko human error hingga 90%, dan memungkinkan akses dari mana saja — kantor, rumah, atau HP.</p>

    <p>Harga mulai dari <strong>{{ $brand['harga_mulai'] }}</strong> — sudah termasuk semua modul tanpa batas anggota. Hubungi <a href="https://wa.me/6281296052010" target="_blank" class="text-emerald-600 font-bold">WA 0812-9605-2010</a> untuk demo gratis.</p>
</div>

@include('seo._cta', ['brand' => $brand])
@endsection
