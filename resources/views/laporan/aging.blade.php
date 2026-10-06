@extends('laporan.layout')

@section('title', 'AGING PIUTANG PEMBIAYAAN')
@section('periode', 'Per ' . \Carbon\Carbon::parse($tanggal)->format('d F Y'))

@section('content')
    <div class="section-title">RINGKASAN PER BUCKET</div>
    <table>
        <thead><tr><th>Bucket</th><th class="text-right">Akad</th><th class="text-right">Outstanding (Rp)</th></tr></thead>
        <tbody>
            @php $labels = ['lancar' => 'Lancar', 'dpk_1_30' => 'DPK (1–30 hari)', 'kurang_lancar_31_60' => 'Kurang Lancar (31–60)', 'diragukan_61_90' => 'Diragukan (61–90)', 'macet_90_plus' => 'Macet (>90 hari)']; @endphp
            @foreach($labels as $k => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-right">{{ $per_bucket[$k]['count'] ?? 0 }}</td>
                    <td class="text-right">{{ number_format($per_bucket[$k]['total'] ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="grand-total"><td>TOTAL OUTSTANDING</td><td class="text-right">{{ count($rows) }}</td><td class="text-right">{{ number_format($total_outstanding, 0, ',', '.') }}</td></tr>
        </tbody>
    </table>

    <div class="section-title">RINCIAN AKAD</div>
    <table>
        <thead><tr><th>No. Akad</th><th>Anggota</th><th>Produk</th><th class="text-right">Outstanding</th><th class="text-center">Telat</th><th>Kolektabilitas</th></tr></thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row['nomor_akad'] }}</td>
                    <td>{{ $row['anggota'] }}</td>
                    <td>{{ $row['produk'] }}</td>
                    <td class="text-right">{{ number_format($row['outstanding'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $row['hari_telat'] }} hr</td>
                    <td>{{ $row['kolektabilitas'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
