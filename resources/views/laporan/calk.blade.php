@extends('laporan.layout')

@section('title', 'CATATAN ATAS LAPORAN KEUANGAN (CALK) — SAK EP')
@section('periode', 'Periode ' . \Carbon\Carbon::parse($dari)->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($sampai)->format('d M Y'))

@section('content')
    <div class="section-title">A. INFORMASI UMUM</div>
    <p style="font-size:10px;">{{ $tenant->nama ?? config('app.name') }} adalah koperasi yang menyusun laporan keuangan
    berdasarkan SAK EP (Standar Akuntansi Keuangan Entitas Privat). Laporan ini mencakup Neraca, Laporan Laba Rugi (SHU),
    Laporan Perubahan Ekuitas, Laporan Arus Kas, dan CALK ini sebagai bagian tak terpisahkan.</p>

    <div class="section-title">B. KEBIJAKAN AKUNTANSI PENTING</div>
    <table>
        <tbody>
            @foreach($kebijakan as $k => $v)
                <tr><td style="width:180px;font-weight:bold;">{{ ucwords(str_replace('_', ' ', $k)) }}</td><td>{{ $v }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">C. RINGKASAN ANGKA (Rp)</div>
    <table>
        <tbody>
            @foreach($ringkasan as $k => $v)
                <tr><td>{{ strtoupper(str_replace('_', ' ', $k)) }}</td><td class="text-right" style="width:180px;">{{ number_format($v, 0, ',', '.') }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">D. SEGMEN UNIT USAHA</div>
    <table>
        <tbody>
            <tr><td>Rekening Simpanan (posisi)</td><td class="text-right" style="width:180px;">{{ number_format($segmen_usaha['simpanan_rekening']) }} rekening / Rp {{ number_format($segmen_usaha['simpanan_saldo'], 0, ',', '.') }}</td></tr>
            <tr><td>Pinjaman Aktif (outstanding pokok)</td><td class="text-right" style="width:180px;">{{ number_format($segmen_usaha['pinjaman_aktif']) }} akad / Rp {{ number_format($segmen_usaha['pinjaman_outstanding'], 0, ',', '.') }}</td></tr>
        </tbody>
    </table>

    <div class="section-title">E. RINCIAN AKUN MATERIAL (10 terbesar per kelompok)</div>
    @foreach($akun_material as $kelompok => $rows)
        <p style="font-weight:bold;margin:10px 0 4px;">{{ strtoupper($kelompok) }}</p>
        <table>
            <thead><tr><th>Kode</th><th>Akun</th><th class="text-right">Saldo (Rp)</th></tr></thead>
            <tbody>
                @foreach($rows as $row)
                    <tr><td style="width:80px;">{{ $row['kode'] }}</td><td>{{ $row['nama'] }}</td><td class="text-right">{{ number_format($row['saldo'], 0, ',', '.') }}</td></tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="section-title">F. PERISTIWA SETELAH TANGGAL LAPORAN</div>
    <p style="font-size:10px;">Tidak ada peristiwa material setelah {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }} sampai tanggal cetak
    yang memerlukan penyesuaian, berdasarkan data sistem. Pengesahan akhir dilakukan melalui RAT.</p>
@endsection
