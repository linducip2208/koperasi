@extends('laporan.layout')

@section('title', 'BUKU BESAR — ' . $coa['kode'] . ' ' . $coa['nama'])
@section('periode', 'Periode ' . \Carbon\Carbon::parse($dari)->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($sampai)->format('d M Y'))

@section('content')
    <table>
        <tbody>
            <tr class="total"><td colspan="4">SALDO AWAL</td><td class="text-right">{{ number_format($saldo_awal, 0, ',', '.') }}</td></tr>
        </tbody>
    </table>
    <table style="margin-top:10px;">
        <thead>
            <tr><th>Tanggal</th><th>No. Jurnal</th><th>Keterangan</th><th class="text-right">Debit (Rp)</th><th class="text-right">Kredit (Rp)</th><th class="text-right">Saldo (Rp)</th></tr>
        </thead>
        <tbody>
            @forelse($lines as $l)
                <tr>
                    <td style="width:90px;">{{ \Carbon\Carbon::parse($l['tanggal'])->format('d M Y') }}</td>
                    <td style="width:120px;">{{ $l['nomor'] }}</td>
                    <td>{{ $l['keterangan'] }}</td>
                    <td class="text-right">{{ $l['debit'] ? number_format($l['debit'], 0, ',', '.') : '—' }}</td>
                    <td class="text-right">{{ $l['kredit'] ? number_format($l['kredit'], 0, ',', '.') : '—' }}</td>
                    <td class="text-right">{{ number_format($l['saldo'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center"><em>Tidak ada mutasi pada periode ini.</em></td></tr>
            @endforelse
            <tr class="grand-total"><td colspan="5">SALDO AKHIR</td><td class="text-right">{{ number_format($saldo_akhir, 0, ',', '.') }}</td></tr>
        </tbody>
    </table>
@endsection
