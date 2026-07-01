<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan ODS — Kemenkop</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; }
        .header { text-align: center; border-bottom: 2px solid #047857; padding-bottom: 8px; margin-bottom: 12px; }
        .header h1 { font-size: 15px; margin: 0; color: #047857; }
        .header h2 { font-size: 12px; margin: 4px 0; }
        .header p { margin: 2px 0; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        th { background: #f0fdf4; text-align: left; padding: 6px; border-bottom: 1px solid #047857; font-size: 9px; text-transform: uppercase; }
        td { padding: 5px 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total { font-weight: bold; background: #f0fdf4; }
        .grand-total { font-weight: bold; background: #047857; color: white; }
        .section-title { font-size: 11px; font-weight: bold; margin-top: 12px; margin-bottom: 4px; color: #047857; }
        .footer { margin-top: 20px; font-size: 8px; color: #6b7280; text-align: center; }
        .indicator-grid { display: table; width: 100%; }
        .indicator-row { display: table-row; }
        .indicator-cell { display: table-cell; width: 25%; padding: 8px; text-align: center; border: 1px solid #d1d5db; }
        .indicator-cell .value { font-size: 16px; font-weight: 800; color: #047857; }
        .indicator-cell .label { font-size: 8px; color: #6b7280; text-transform: uppercase; margin-top: 2px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ODS (Organisasi, Data, Sarana)</h1>
        <h2>Laporan Kepada Kemenkop UKM RI</h2>
        <p>Per {{ \Carbon\Carbon::parse($data['tanggal'])->format('d F Y') }} &middot; Dicetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="indicator-grid">
        <div class="indicator-row">
            <div class="indicator-cell">
                <div class="value">{{ number_format($data['total_anggota']) }}</div>
                <div class="label">Anggota</div>
            </div>
            <div class="indicator-cell">
                <div class="value">Rp {{ number_format($data['total_simpanan'], 0, ',', '.') }}</div>
                <div class="label">Simpanan</div>
            </div>
            <div class="indicator-cell">
                <div class="value">Rp {{ number_format($data['total_pinjaman'], 0, ',', '.') }}</div>
                <div class="label">Outstanding Pinjaman</div>
            </div>
            <div class="indicator-cell">
                <div class="value">{{ $data['npl'] }}%</div>
                <div class="label">NPL</div>
            </div>
        </div>
    </div>

    <div class="section-title">I. Organisasi</div>
    <table>
        <tr><td style="width: 300px;">Jumlah Anggota</td><td class="text-right"><strong>{{ number_format($data['total_anggota']) }}</strong> orang</td></tr>
        <tr><td>Anggota Laki-laki</td><td class="text-right">{{ number_format($data['total_anggota_l']) }} orang</td></tr>
        <tr><td>Anggota Perempuan</td><td class="text-right">{{ number_format($data['total_anggota_p']) }} orang</td></tr>
        <tr class="total"><td>Rasio Partisipasi Keanggotaan</td><td class="text-right">—</td></tr>
    </table>

    <div class="section-title">II. Data Keuangan</div>
    <table>
        <thead><tr><th>Komponen</th><th class="text-right">Nilai (Rp)</th></tr></thead>
        <tr><td>Total Simpanan</td><td class="text-right">{{ number_format($data['total_simpanan'], 0, ',', '.') }}</td></tr>
        <tr><td>— Simpanan Pokok</td><td class="text-right">{{ number_format($data['total_simpanan_pokok'], 0, ',', '.') }}</td></tr>
        <tr><td>— Simpanan Wajib</td><td class="text-right">{{ number_format($data['total_simpanan_wajib'], 0, ',', '.') }}</td></tr>
        <tr><td>— Simpanan Sukarela</td><td class="text-right">{{ number_format($data['total_simpanan_sukarela'], 0, ',', '.') }}</td></tr>
        <tr><td>Jumlah Rekening Simpanan</td><td class="text-right">{{ number_format($data['jumlah_rekening']) }}</td></tr>
        <tr><td>Outstanding Pinjaman</td><td class="text-right">{{ number_format($data['total_pinjaman'], 0, ',', '.') }}</td></tr>
        <tr><td>Jumlah Pinjaman Aktif</td><td class="text-right">{{ number_format($data['jumlah_pinjaman']) }}</td></tr>
        <tr class="grand-total"><td>Total Volume Usaha</td><td class="text-right">{{ number_format($data['total_volume_usaha'], 0, ',', '.') }}</td></tr>
    </table>

    <div class="section-title">III. Sarana</div>
    <table>
        <thead><tr><th>Indikator</th><th class="text-right">Nilai</th></tr></thead>
        <tr><td>Total Aset</td><td class="text-right">Rp {{ number_format($data['total_asset'], 0, ',', '.') }}</td></tr>
        <tr><td>NPL Ratio</td><td class="text-right"><strong>{{ $data['npl'] }}%</strong></td></tr>
        <tr><td>Rasio Simpanan terhadap Pinjaman</td><td class="text-right">{{ $data['total_pinjaman'] > 0 ? round(($data['total_simpanan'] / $data['total_pinjaman']) * 100) : 0 }}%</td></tr>
    </table>

    <div class="footer">
        Laporan ini digenerate otomatis oleh {{ config('app.name') }} &middot; {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>
