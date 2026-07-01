@extends('laporan.layout')

@section('title', 'LAPORAN ARUS KAS')
@section('periode', $periode)

@section('content')
    <table>
        <thead>
            <tr>
                <th>URAIAN</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Penerimaan Kas</td>
                <td class="text-right">{{ number_format($masuk, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pengeluaran Kas</td>
                <td class="text-right">({{ number_format($keluar, 0, ',', '.') }})</td>
            </tr>
            <tr class="grand-total">
                <td>Kenaikan/Penurunan Kas Bersih</td>
                <td class="text-right">{{ number_format($net, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 20px; font-size: 10px; color: #6b7280;">
        <em>Catatan: Laporan ini menggunakan metode langsung berdasarkan pergerakan akun kas & bank.</em>
    </p>
@endsection
