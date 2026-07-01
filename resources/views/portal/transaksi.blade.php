@extends('portal.layout')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')
@section('page-subtitle', 'Semua aktivitas akun Anda')

@section('content')
<div class="space-y-6 max-w-7xl">

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Tanggal</th>
                        <th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Kategori</th>
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
                                    {{ $t->kategori === 'simpanan' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $t->kategori }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-700 capitalize">{{ str_replace('_', ' ', $t->jenis) }}</td>
                            <td class="py-3 px-4 text-slate-600 max-w-md truncate">{{ $t->keterangan ?? '-' }}</td>
                            <td class="py-3 px-4 text-right font-bold
                                {{ ($t->kategori === 'simpanan' && $t->jenis === 'setor') ? 'text-emerald-700' : 'text-rose-700' }}">
                                Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-slate-500">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-xs text-slate-500 text-center">
        Menampilkan 50 transaksi terakhir. Untuk laporan periode tertentu, hubungi admin koperasi.
    </div>

</div>
@endsection
