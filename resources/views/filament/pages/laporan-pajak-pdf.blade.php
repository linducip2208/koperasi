<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Laporan Pajak {{ $periode }}</title>
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:10px;color:#1f2937}
.header{text-align:center;border-bottom:2px solid #047857;padding-bottom:8px;margin-bottom:12px}
.header h1{font-size:15px;margin:0;color:#047857}
table{width:100%;border-collapse:collapse;margin-bottom:14px}
th{background:#f0fdf4;text-align:left;padding:6px;border-bottom:1px solid #047857;font-size:9px}
td{padding:5px 6px;border-bottom:1px solid #e5e7eb;font-size:10px}
.text-right{text-align:right}
.total{font-weight:bold;background:#f0fdf4}
</style></head>
<body>
<div class="header"><h1>LAPORAN PAJAK</h1><p>Periode: {{ $periode }}</p></div>

<h3 style="color:#047857;">PPh 21 — Karyawan</h3>
<table><thead><tr><th>NIP</th><th>Nama</th><th class="text-right">Gaji Bruto</th><th class="text-right">PPh 21</th><th class="text-right">Neto</th></tr></thead>
<tbody>
@foreach($pph21 as $r)
<tr><td>{{ $r['nip'] }}</td><td>{{ $r['nama'] }}</td><td class="text-right">{{ number_format($r['gaji_bruto'],0,',','.') }}</td><td class="text-right">{{ number_format($r['pph21'],0,',','.') }}</td><td class="text-right">{{ number_format($r['gaji_neto'],0,',','.') }}</td></tr>
@endforeach
</tbody></table>

<h3 style="color:#047857;margin-top:20px;">PPh 4(2) — Bunga Simpanan</h3>
<table><thead><tr><th>Nama</th><th class="text-right">Bunga</th><th class="text-right">PPh 10%</th></tr></thead>
<tbody>
@foreach($pphBunga['detail'] as $r)
<tr><td>{{ $r['nama'] }}</td><td class="text-right">{{ number_format($r['bunga'],0,',','.') }}</td><td class="text-right">{{ number_format($r['pph']??0,0,',','.') }}</td></tr>
@endforeach
<tr class="total"><td>TOTAL</td><td class="text-right">{{ number_format($pphBunga['total_bunga'],0,',','.') }}</td><td class="text-right">{{ number_format($pphBunga['total_pph'],0,',','.') }}</td></tr>
</tbody></table>
</body>
</html>
