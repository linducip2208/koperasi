@extends('portal.layout')

@section('title', 'Simpanan')
@section('page-title', 'Simpanan Saya')
@section('page-subtitle', 'Kelola tabungan & deposito di koperasi')

@section('content')
<div class="space-y-6 max-w-7xl">

    <div class="card-gradient p-6 md:p-8 relative overflow-hidden">
        <div class="blob bg-emerald-300 w-72 h-72 -top-20 -right-20 animate-blob"></div>
        <div class="relative">
            <div class="text-emerald-50 text-sm font-semibold mb-1">Total Saldo Simpanan</div>
            <div class="font-extrabold text-4xl md:text-5xl tracking-tight">
                Rp {{ number_format($total, 0, ',', '.') }}
            </div>
            <div class="text-emerald-100 text-sm mt-2">{{ $simpanan->count() }} rekening simpanan aktif</div>
        </div>
    </div>

    <div>
        <h2 class="font-extrabold text-lg text-slate-900 mb-4">Daftar Rekening Simpanan</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($simpanan as $s)
                <div class="card p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <div class="text-xs font-bold text-emerald-700 uppercase tracking-wider">{{ $s->produk?->nama ?? 'Simpanan' }}</div>
                            <div class="font-mono text-xs text-slate-500 mt-0.5">{{ $s->nomor_rekening }}</div>
                        </div>
                        <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-md
                            {{ $s->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $s->status }}</span>
                    </div>
                    <div class="font-extrabold text-2xl text-slate-900">Rp {{ number_format($s->saldo, 0, ',', '.') }}</div>
                    <div class="text-xs text-slate-500 mt-1">Dibuka: {{ \Carbon\Carbon::parse($s->tanggal_buka)->translatedFormat('d M Y') }}</div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-slate-500">
                    Belum ada rekening simpanan.
                </div>
            @endforelse
        </div>
    </div>

    <div>
        <h2 class="font-extrabold text-lg text-slate-900 mb-4">Riwayat Transaksi Simpanan</h2>
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Tanggal</th>
                            <th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Jenis</th>
                            <th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Keterangan</th>
                            <th class="text-right py-3 px-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transaksi as $t)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 text-slate-600 whitespace-nowrap">{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y') }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-block text-[10px] font-bold uppercase px-2 py-1 rounded-md
                                        {{ $t->jenis === 'setor' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $t->jenis }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-600">{{ $t->keterangan ?? '-' }}</td>
                                <td class="py-3 px-4 text-right font-bold {{ $t->jenis === 'setor' ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $t->jenis === 'setor' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-12 text-center text-slate-500">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transaksi->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 bg-slate-50">{{ $transaksi->links() }}</div>
            @endif
        </div>
    </div>

</div>
@endsection
