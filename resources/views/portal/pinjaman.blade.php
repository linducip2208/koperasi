@extends('portal.layout')

@section('title', 'Pinjaman')
@section('page-title', 'Pinjaman Saya')
@section('page-subtitle', 'Lihat status pinjaman dan jadwal cicilan')

@section('content')
<div class="space-y-6 max-w-7xl">

    @php
        $totalPlafon  = $pinjaman->sum('plafon');
        $totalSaldo   = $pinjaman->sum('saldo_pokok');
        $aktifCount   = $pinjaman->whereIn('status', ['aktif','cair'])->count();
    @endphp

    <div class="grid md:grid-cols-3 gap-4">
        <div class="card p-5">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Pinjaman</div>
            <div class="font-extrabold text-2xl text-slate-900 mt-2">Rp {{ number_format($totalPlafon, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-1">{{ $pinjaman->count() }} pinjaman</div>
        </div>
        <div class="card p-5">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Sisa Pokok</div>
            <div class="font-extrabold text-2xl text-rose-700 mt-2">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500 mt-1">Belum terbayar</div>
        </div>
        <div class="card p-5">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Pinjaman Aktif</div>
            <div class="font-extrabold text-2xl text-emerald-700 mt-2">{{ $aktifCount }}</div>
            <div class="text-xs text-slate-500 mt-1">Sedang berjalan</div>
        </div>
    </div>

    <div class="space-y-5">
        @forelse($pinjaman as $p)
            <div class="card p-6">
                <div class="flex items-start justify-between flex-wrap gap-3 mb-4">
                    <div>
                        <div class="font-mono text-xs text-slate-500">{{ $p->nomor_akad }}</div>
                        <h3 class="font-extrabold text-lg text-slate-900 mt-1">{{ $p->produk?->nama ?? 'Pinjaman' }}</h3>
                        <div class="text-xs text-slate-500">Cair: {{ $p->tanggal_pencairan ? \Carbon\Carbon::parse($p->tanggal_pencairan)->translatedFormat('d M Y') : '-' }}</div>
                    </div>
                    <span class="text-[10px] font-bold uppercase px-3 py-1.5 rounded-lg
                        @switch($p->status)
                            @case('lunas') bg-emerald-100 text-emerald-700 @break
                            @case('macet') bg-rose-100 text-rose-700 @break
                            @case('cair')
                            @case('aktif') bg-emerald-100 text-emerald-700 @break
                            @default bg-amber-100 text-amber-700
                        @endswitch">
                        {{ $p->status }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5 text-sm">
                    <div>
                        <div class="text-xs text-slate-500">Plafon</div>
                        <div class="font-bold text-slate-900">Rp {{ number_format($p->plafon, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500">Sisa Pokok</div>
                        <div class="font-bold text-rose-700">Rp {{ number_format($p->saldo_pokok, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500">Tenor</div>
                        <div class="font-bold text-slate-900">{{ $p->tenor }} bulan</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500">Bunga/Margin</div>
                        <div class="font-bold text-slate-900">{{ rtrim(rtrim((string)$p->bunga_persen, '0'), '.') }}%</div>
                    </div>
                </div>

                @if($p->jadwal && $p->jadwal->count())
                    <details class="border-t border-slate-100 pt-4">
                        <summary class="cursor-pointer text-sm font-bold text-emerald-700 hover:text-emerald-800">
                            Lihat jadwal angsuran ({{ $p->jadwal->count() }} angsuran)
                        </summary>
                        <div class="mt-3 overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="text-left py-2 px-3 font-bold text-slate-600">#</th>
                                        <th class="text-left py-2 px-3 font-bold text-slate-600">Jatuh Tempo</th>
                                        <th class="text-right py-2 px-3 font-bold text-slate-600">Pokok</th>
                                        <th class="text-right py-2 px-3 font-bold text-slate-600">Margin</th>
                                        <th class="text-right py-2 px-3 font-bold text-slate-600">Total</th>
                                        <th class="text-center py-2 px-3 font-bold text-slate-600">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($p->jadwal->take(15) as $j)
                                        <tr>
                                            <td class="py-2 px-3 font-mono">{{ $j->angsuran_ke }}</td>
                                            <td class="py-2 px-3">{{ \Carbon\Carbon::parse($j->tanggal_jatuh_tempo)->translatedFormat('d M Y') }}</td>
                                            <td class="py-2 px-3 text-right">{{ number_format($j->pokok, 0, ',', '.') }}</td>
                                            <td class="py-2 px-3 text-right">{{ number_format($j->margin, 0, ',', '.') }}</td>
                                            <td class="py-2 px-3 text-right font-bold">{{ number_format($j->total_angsuran, 0, ',', '.') }}</td>
                                            <td class="py-2 px-3 text-center">
                                                <span class="inline-block text-[9px] font-bold px-1.5 py-0.5 rounded
                                                    {{ $j->status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : ($j->status === 'telat' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600') }}">
                                                    {{ str_replace('_', ' ', $j->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($p->jadwal->count() > 15)
                                <div class="text-xs text-slate-500 text-center mt-2">+{{ $p->jadwal->count() - 15 }} angsuran lainnya</div>
                            @endif
                        </div>
                    </details>
                @endif
            </div>
        @empty
            <div class="card p-12 text-center text-slate-500">
                <div class="text-lg font-semibold text-slate-700 mb-1">Belum ada pinjaman</div>
                <div class="text-sm">Hubungi admin koperasi untuk mengajukan pinjaman.</div>
            </div>
        @endforelse
    </div>

</div>
@endsection
