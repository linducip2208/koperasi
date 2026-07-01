@extends('laporan.layout')

@section('title', 'RINGKASAN PER PRODUK')
@section('periode', $periode)

@section('content')
    <div class="section-title">📦 Simpanan</div>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-right">Jumlah Rekening</th>
                <th class="text-right">Total Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totSimp = 0; $totRek = 0; @endphp
            @forelse($simpanan as $s)
                <tr>
                    <td>{{ $s->produk->nama ?? '—' }}</td>
                    <td class="text-right">{{ number_format($s->jml) }}</td>
                    <td class="text-right">{{ number_format($s->total, 0, ',', '.') }}</td>
                </tr>
                @php $totSimp += $s->total; $totRek += $s->jml; @endphp
            @empty
                <tr><td colspan="3" class="text-center" style="color:#9ca3af">Tidak ada data</td></tr>
            @endforelse
            @if($simpanan->count())
                <tr class="total">
                    <td>TOTAL</td>
                    <td class="text-right">{{ number_format($totRek) }}</td>
                    <td class="text-right">{{ number_format($totSimp, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">💳 Pinjaman Aktif</div>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-right">Jumlah Pinjaman</th>
                <th class="text-right">Outstanding Pokok (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totPinj = 0; $totJml = 0; @endphp
            @forelse($pinjaman as $p)
                <tr>
                    <td>{{ $p->produk->nama ?? '—' }}</td>
                    <td class="text-right">{{ number_format($p->jml) }}</td>
                    <td class="text-right">{{ number_format($p->outstanding, 0, ',', '.') }}</td>
                </tr>
                @php $totPinj += $p->outstanding; $totJml += $p->jml; @endphp
            @empty
                <tr><td colspan="3" class="text-center" style="color:#9ca3af">Tidak ada data</td></tr>
            @endforelse
            @if($pinjaman->count())
                <tr class="grand-total">
                    <td>TOTAL OUTSTANDING</td>
                    <td class="text-right">{{ number_format($totJml) }}</td>
                    <td class="text-right">{{ number_format($totPinj, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
