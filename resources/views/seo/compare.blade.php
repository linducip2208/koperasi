@extends('seo._layout')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12">

    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <span class="text-slate-700">{{ $a['nama'] }} vs {{ $b['nama'] }}</span>
    </nav>

    <header class="mb-12 max-w-3xl">
        <span class="inline-block bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">Perbandingan {{ $year }}</span>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
            <span class="gradient-text">{{ $a['nama'] }}</span> vs <span class="gradient-text">{{ $b['nama'] }}</span>
        </h1>
        <p class="text-lg text-slate-600 leading-relaxed">Bandingkan {{ $a['nama'] }} dengan {{ $b['nama'] }} secara head-to-head: fitur, harga, dukungan syariah, modul akuntansi, dan kelebihan-kekurangan masing-masing aplikasi koperasi.</p>
    </header>

    <section class="grid md:grid-cols-2 gap-6 mb-12">
        <div class="bg-white rounded-3xl border-2 border-indigo-200 p-8 shadow-xl shadow-indigo-100/50">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white font-extrabold text-xl">{{ substr($a['nama'], 0, 1) }}</div>
                <div>
                    <h2 class="font-extrabold text-2xl text-slate-900">{{ $a['nama'] }}</h2>
                    <p class="text-sm text-slate-500">{{ $a['tagline'] ?? 'Software Koperasi' }}</p>
                </div>
            </div>
            @if($a['slug'] === 'koperasi-app')
                <ul class="space-y-2 text-sm">
                    @foreach($brand['fitur'] as $f)
                        <li class="flex gap-2 items-start"><span class="text-emerald-500 font-bold">✓</span><span class="text-slate-700">{{ $f }}</span></li>
                    @endforeach
                </ul>
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <span class="text-xs text-slate-500">Mulai dari</span>
                    <div class="font-extrabold text-2xl text-indigo-600">{{ $brand['harga_mulai'] }}</div>
                </div>
            @else
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex gap-2 items-start"><span class="text-amber-500">○</span><span>Software koperasi lokal Indonesia</span></li>
                    <li class="flex gap-2 items-start"><span class="text-amber-500">○</span><span>Fitur dasar simpan-pinjam</span></li>
                    <li class="flex gap-2 items-start"><span class="text-amber-500">○</span><span>Laporan keuangan standar</span></li>
                    <li class="flex gap-2 items-start"><span class="text-red-400">✗</span><span>Mode syariah terbatas</span></li>
                    <li class="flex gap-2 items-start"><span class="text-red-400">✗</span><span>Tidak multi-tenant SaaS</span></li>
                </ul>
            @endif
        </div>

        <div class="bg-white rounded-3xl border-2 border-pink-200 p-8 shadow-xl shadow-pink-100/50">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center text-white font-extrabold text-xl">{{ substr($b['nama'], 0, 1) }}</div>
                <div>
                    <h2 class="font-extrabold text-2xl text-slate-900">{{ $b['nama'] }}</h2>
                    <p class="text-sm text-slate-500">{{ $b['tagline'] ?? 'Software Koperasi' }}</p>
                </div>
            </div>
            @if($b['slug'] === 'koperasi-app')
                <ul class="space-y-2 text-sm">
                    @foreach($brand['fitur'] as $f)
                        <li class="flex gap-2 items-start"><span class="text-emerald-500 font-bold">✓</span><span class="text-slate-700">{{ $f }}</span></li>
                    @endforeach
                </ul>
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <span class="text-xs text-slate-500">Mulai dari</span>
                    <div class="font-extrabold text-2xl text-pink-600">{{ $brand['harga_mulai'] }}</div>
                </div>
            @else
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex gap-2 items-start"><span class="text-amber-500">○</span><span>Software koperasi lokal Indonesia</span></li>
                    <li class="flex gap-2 items-start"><span class="text-amber-500">○</span><span>Fitur dasar simpan-pinjam</span></li>
                    <li class="flex gap-2 items-start"><span class="text-amber-500">○</span><span>Laporan keuangan standar</span></li>
                    <li class="flex gap-2 items-start"><span class="text-red-400">✗</span><span>Mode syariah terbatas</span></li>
                    <li class="flex gap-2 items-start"><span class="text-red-400">✗</span><span>Tidak multi-tenant SaaS</span></li>
                </ul>
            @endif
        </div>
    </section>

    <section class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 mb-12 overflow-x-auto">
        <h2 class="text-3xl font-extrabold mb-6 text-slate-900">Tabel Perbandingan Lengkap</h2>
        <table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="border-b-2 border-slate-200">
                    <th class="text-left py-3 font-bold text-slate-700">Kriteria</th>
                    <th class="text-center py-3 font-bold text-indigo-700">{{ $a['nama'] }}</th>
                    <th class="text-center py-3 font-bold text-pink-700">{{ $b['nama'] }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php
                    $isA = $a['slug'] === 'koperasi-app';
                    $isB = $b['slug'] === 'koperasi-app';
                    $rows = [
                        ['Mode Konvensional', $isA, $isB ? true : true],
                        ['Mode Syariah (' . count(config('pseo.akad')) . ' akad)', $isA, $isB],
                        ['Multi-tenant SaaS', $isA, $isB],
                        ['Filament Admin Modern', $isA, $isB],
                        ['Portal Anggota Mobile', $isA, $isB],
                        ['Auto-jurnal PSAK 27', $isA, true],
                        ['Hitung SHU Otomatis', $isA, true],
                        ['Import CSV/Excel 1-klik', $isA, $isB],
                        ['Notifikasi WhatsApp', $isA, $isB],
                        ['License-locked Standalone', $isA, $isB],
                        ['Update Berkala', $isA, true],
                    ];
                @endphp
                @foreach($rows as $r)
                    <tr>
                        <td class="py-3 text-slate-700">{{ $r[0] }}</td>
                        <td class="py-3 text-center">{!! $r[1] ? '<span class="text-emerald-500 text-xl font-bold">✓</span>' : '<span class="text-red-400 text-xl">✗</span>' !!}</td>
                        <td class="py-3 text-center">{!! $r[2] ? '<span class="text-emerald-500 text-xl font-bold">✓</span>' : '<span class="text-red-400 text-xl">✗</span>' !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <section class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 mb-12">
        <h2 class="text-3xl font-extrabold mb-4 text-slate-900">Kapan Pilih {{ $a['nama'] }}, Kapan Pilih {{ $b['nama'] }}?</h2>
        <div class="prose-custom">
            <p>Memilih software koperasi adalah keputusan yang akan memengaruhi operasional 5–10 tahun ke depan — bukan sesuatu yang sebaiknya berdasarkan harga termurah saja. Pertimbangkan kebutuhan jangka panjang: rencana ekspansi anggota, kebutuhan akad syariah, integrasi dengan banking, dan kemudahan training pengurus baru.</p>

            <h3>Pilih {{ $a['nama'] }} jika:</h3>
            <ul>
                <li>Koperasi Anda butuh mode <strong>dual-syariah/konvensional</strong> dalam 1 instalasi</li>
                <li>Anda ingin admin panel modern dan portal anggota mobile-friendly</li>
                <li>Anda butuh <strong>migrasi data dari Excel/CSV cepat</strong> tanpa entry manual</li>
                <li>Tim Anda kecil dan butuh sistem yang <strong>self-service</strong>, tidak butuh IT staff</li>
                <li>Anda butuh license-locked standalone (data on-premise) atau cloud SaaS — fleksibel</li>
            </ul>

            <h3>Pilih {{ $b['nama'] }} jika:</h3>
            <ul>
                <li>Anda sudah lama pakai {{ $b['nama'] }} dan biaya migrasi terlalu mahal sekarang</li>
                <li>Tim Anda sudah terbiasa dengan UI dan workflow {{ $b['nama'] }}</li>
                <li>Kebutuhan koperasi sangat dasar (hanya simpanan + pinjaman konvensional)</li>
                <li>Anda butuh fitur sangat spesifik yang hanya tersedia di {{ $b['nama'] }}</li>
            </ul>

            <p>Tip: <strong>{{ $brand['nama'] }} menyediakan trial 14 hari gratis dan migrasi awal dibantu</strong> — Anda bisa eksperimen dengan data koperasi sendiri tanpa komitmen. Jika cocok, langsung subscribe; jika tidak, data Anda tetap di Excel/sistem lama tanpa kerugian apapun.</p>
        </div>
    </section>

    @include('seo._cta')

</div>
@endsection
