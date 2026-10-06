@extends('laporan.layout')

@section('title', 'LAPORAN PERUBAHAN EKUITAS (SAK EP)')
@section('periode', 'Periode ' . \Carbon\Carbon::parse($dari)->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($sampai)->format('d M Y'))

@section('content')
    <p style="font-size:10px;color:#4b5563;margin-bottom:10px;">
        Basis: SAK EP (Entitas Privat). Ekuitas awal = posisi per {{ \Carbon\Carbon::parse($sebelum)->format('d M Y') }}.
        SHU berjalan pembanding dari Laporan Laba Rugi: <strong>Rp {{ number_format($shu_berjalan, 0, ',', '.') }}</strong>
    </p>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Komponen Ekuitas</th>
                <th class="text-right">Saldo Awal (Rp)</th>
                <th class="text-right">Mutasi (Rp)</th>
                <th class="text-right">Saldo Akhir (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rincian as $row)
                <tr>
                    <td style="width: 80px;">{{ $row['kode'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td class="text-right">{{ number_format($row['saldo_awal'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row['mutasi'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row['saldo_akhir'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="2">TOTAL EKUITAS</td>
                <td class="text-right">{{ number_format($total_awal, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_mutasi, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_akhir, 0, ',', '.') }}</td>
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
