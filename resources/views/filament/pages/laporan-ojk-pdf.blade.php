<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Laporan OJK {{ $data['tanggal'] }}</title>
<style>body{font-family:DejaVu Sans,sans-serif;font-size:10px;color:#1f2937}.header{text-align:center;border-bottom:2px solid #047857;padding-bottom:8px;margin-bottom:12px}.header h1{font-size:15px;margin:0;color:#047857}table{width:100%;border-collapse:collapse;margin-bottom:14px}th{background:#f0fdf4;text-align:left;padding:6px;border-bottom:1px solid #047857;font-size:9px}td{padding:5px 6px;border-bottom:1px solid #e5e7eb}.text-right{text-align:right}.total{font-weight:bold;background:#f0fdf4}</style></head>
<body><div class="header"><h1>LAPORAN BULANAN KOPERASI — OJK</h1><p>Per {{ \Carbon\Carbon::parse($data['tanggal'])->format('d F Y') }}</p></div>
<table><thead><tr><th>Indikator</th><th class="text-right">Nilai</th></tr></thead>
<tbody>
<tr><td>Jumlah Anggota</td><td class="text-right">{{ number_format($data['total_anggota']) }}</td></tr>
<tr><td>Total Simpanan</td><td class="text-right">Rp {{ number_format($data['total_simpanan'],0,',','.') }}</td></tr>
<tr><td>Outstanding Pinjaman</td><td class="text-right">Rp {{ number_format($data['total_pinjaman'],0,',','.') }}</td></tr>
<tr><td>NPL Ratio</td><td class="text-right">{{ $data['npl'] }}%</td></tr>
<tr class="total"><td>Total Aset</td><td class="text-right">Rp {{ number_format($data['total_aset'],0,',','.') }}</td></tr>
</tbody></table></body></html>
