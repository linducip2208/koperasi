@extends('laporan.layout')

@section('title', 'NERACA SALDO (TRIAL BALANCE)')
@section('periode', 'Per ' . \Carbon\Carbon::parse($tanggal)->format('d F Y'))

@section('content')
    <table>
        <thead>
            <tr><th>Kode</th><th>Akun</th><th class="text-right">Debit (Rp)</th><th class="text-right">Kredit (Rp)</th></tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td style="width:80px;">{{ $row['kode'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td class="text-right">{{ $row['debit'] ? number_format($row['debit'], 0, ',', '.') : '—' }}</td>
                    <td class="text-right">{{ $row['kredit'] ? number_format($row['kredit'], 0, ',', '.') : '—' }}</td>
                </tr>
            @endforeach
            <tr class="grand-total">
                <td colspan="2">TOTAL {{ $total_debit === $total_kredit ? '✅ BALANCE' : '❌ TIDAK BALANCE' }}</td>
                <td class="text-right">{{ number_format($total_debit, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_kredit, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
@endsection
