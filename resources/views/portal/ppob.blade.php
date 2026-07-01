@extends('portal.layout')
@section('title', 'PPOB & Pembayaran')
@section('page-title', 'PPOB')
@section('page-subtitle', 'Pulsa, Listrik, BPJS, E-Wallet')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
        @foreach(['pulsa' => '📱 Pulsa', 'pln' => '⚡ Listrik', 'bpjs' => '🏥 BPJS', 'ewallet' => '💳 E-Wallet', 'pdam' => '💧 PDAM', 'internet' => '🌐 Internet'] as $k => $label)
            <a href="?kategori={{ $k }}" class="card p-4 text-center hover:border-emerald-300 hover:shadow-lg transition-all card-lift {{ request('kategori') === $k ? 'ring-2 ring-emerald-500' : '' }}">
                <div class="text-2xl mb-2">{{ explode(' ', $label)[0] }}</div>
                <div class="text-xs font-bold text-stone-700">{{ explode(' ', $label)[1] ?? $label }}</div>
            </a>
        @endforeach
    </div>

    @php
        $produk = \App\Models\PpobProduk::where('aktif', true)
            ->when(request('kategori'), fn($q) => $q->where('kategori', request('kategori')))
            ->get();
    @endphp

    @if($produk->isNotEmpty())
        <div class="card p-5">
            <h3 class="font-extrabold text-sm text-stone-800 mb-4">Pilih Produk</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($produk as $p)
                    <form action="{{ route('portal.ppob.beli') }}" method="POST" class="bg-stone-50 rounded-xl p-4 border border-stone-200 hover:border-emerald-300 transition">
                        @csrf
                        <input type="hidden" name="ppob_produk_id" value="{{ $p->id }}">
                        <div class="font-extrabold text-sm text-stone-800 mb-1">{{ $p->nama }}</div>
                        <div class="text-emerald-700 font-bold text-lg mb-3">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</div>
                        <input type="text" name="no_tujuan" required placeholder="No. HP / ID Pelanggan"
                               class="w-full px-3 py-2 rounded-lg border border-stone-200 text-sm mb-2 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-lg text-sm transition">
                            Beli Sekarang
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    @else
        <div class="card p-12 text-center text-stone-400">
            <div class="text-5xl mb-3 opacity-30">🛍️</div>
            <p class="font-semibold">Pilih kategori PPOB untuk melihat produk</p>
        </div>
    @endif

    {{-- Histori Transaksi --}}
    @php $history = \App\Models\PpobTransaksi::where('anggota_id', $anggota->id)->latest()->limit(10)->get(); @endphp
    @if($history->isNotEmpty())
        <div class="card p-5">
            <h3 class="font-extrabold text-sm text-stone-800 mb-4">📋 Riwayat Transaksi</h3>
            <div class="space-y-2">
                @foreach($history as $h)
                    <div class="flex justify-between items-center p-3 bg-stone-50 rounded-xl">
                        <div>
                            <div class="font-semibold text-sm text-stone-800">{{ $h->produk->nama ?? '—' }}</div>
                            <div class="text-xs text-stone-400">{{ $h->no_tujuan }} &middot; {{ $h->created_at->format('d M H:i') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-stone-800">Rp {{ number_format($h->harga, 0, ',', '.') }}</div>
                            <span class="text-xs px-2 py-0.5 rounded-full font-bold {{ $h->status === 'sukses' ? 'bg-emerald-100 text-emerald-700' : ($h->status === 'gagal' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">{{ ucfirst($h->status) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
