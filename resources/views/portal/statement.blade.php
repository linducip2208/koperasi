@extends('laporan.layout')

@section('title', 'STATEMENT ANGGOTA')
@section('periode', 'Periode ' . \Carbon\Carbon::parse($dari)->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($sampai)->format('d M Y'))

@section('content')
    <table style="margin-bottom:12px;">
        <tbody>
            <tr><td style="width:200px;">Nomor Anggota</td><td><strong>{{ $anggota->nomor_anggota }}</strong></td></tr>
            <tr><td>Nama</td><td><strong>{{ $anggota->nama }}</strong></td></tr>
            <tr><td>Total Saldo Aktif</td><td><strong>Rp {{ number_format($anggota->simpanan->where('status', 'aktif')->sum('saldo'), 0, ',', '.') }}</strong></td></tr>
        </tbody>
    </table>

    <table>
        <thead><tr><th>Tanggal</th><th>Nomor</th><th>Jenis</th><th class="text-right">Jumlah (Rp)</th><th class="text-right">Saldo Sesudah</th></tr></thead>
        <tbody>
            @forelse($transaksi as $t)
                <tr>
                    <td style="width:90px;">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M y') }}</td>
                    <td>{{ $t->nomor }}</td>
                    <td>{{ $t->jenis }}</td>
                    <td class="text-right">{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($t->saldo_sesudah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center"><em>Tidak ada transaksi pada periode ini.</em></td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
