@extends('laporan.layout')

@section('title', 'LAPORAN LABA RUGI')
@section('periode', 'Periode ' . \Carbon\Carbon::parse($dari)->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($sampai)->format('d M Y'))

@section('content')
    <table>
        <thead>
            <tr>
                <th colspan="2">PENDAPATAN</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendapatan as $row)
                <tr>
                    <td style="width: 80px;">{{ $row['kode'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td class="text-right">{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="2">TOTAL PENDAPATAN</td>
                <td class="text-right">{{ number_format($total_pendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th colspan="2">BEBAN</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($beban as $row)
                <tr>
                    <td style="width: 80px;">{{ $row['kode'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td class="text-right">{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="2">TOTAL BEBAN</td>
                <td class="text-right">{{ number_format($total_beban, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td colspan="2">SISA HASIL USAHA (SHU) — Sebelum Pajak</td>
                <td class="text-right">{{ number_format($shu, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <div class="sig-box">
            <p>Dibuat oleh,</p><br><br><br>
            <p style="border-top: 1px solid #000; padding-top: 5px;"><strong>Akuntan</strong></p>
        </div>
        <div class="sig-box">
            <p>Diperiksa oleh,</p><br><br><br>
            <p style="border-top: 1px solid #000; padding-top: 5px;"><strong>Pengawas</strong></p>
        </div>
        <div class="sig-box">
            <p>Disahkan oleh,</p><br><br><br>
            <p style="border-top: 1px solid #000; padding-top: 5px;"><strong>Ketua</strong></p>
        </div>
    </div>
@endsection
