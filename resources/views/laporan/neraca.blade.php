@extends('laporan.layout')

@section('title', 'NERACA (Laporan Posisi Keuangan)')
@section('periode', 'Per ' . \Carbon\Carbon::parse($tanggal)->format('d F Y'))

@section('content')
    <table>
        <thead>
            <tr>
                <th colspan="2">ASET</th>
                <th class="text-right">Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAset = 0; @endphp
            @foreach($aset as $row)
                <tr>
                    <td style="width: 80px;">{{ $row['kode'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td class="text-right">{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                </tr>
                @php $totalAset += $row['saldo']; @endphp
            @endforeach
            <tr class="total">
                <td colspan="2">TOTAL ASET</td>
                <td class="text-right">{{ number_format($totalAset, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th colspan="2">KEWAJIBAN</th>
                <th class="text-right">Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKewajiban = 0; @endphp
            @foreach($kewajiban as $row)
                <tr>
                    <td style="width: 80px;">{{ $row['kode'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td class="text-right">{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                </tr>
                @php $totalKewajiban += $row['saldo']; @endphp
            @endforeach
            <tr class="total">
                <td colspan="2">TOTAL KEWAJIBAN</td>
                <td class="text-right">{{ number_format($totalKewajiban, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th colspan="2">EKUITAS</th>
                <th class="text-right">Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalEkuitas = 0; @endphp
            @foreach($ekuitas as $row)
                <tr>
                    <td style="width: 80px;">{{ $row['kode'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td class="text-right">{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                </tr>
                @php $totalEkuitas += $row['saldo']; @endphp
            @endforeach
            <tr class="total">
                <td colspan="2">TOTAL EKUITAS</td>
                <td class="text-right">{{ number_format($totalEkuitas, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td colspan="2">TOTAL KEWAJIBAN + EKUITAS</td>
                <td class="text-right">{{ number_format($totalKewajiban + $totalEkuitas, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
@endsection
