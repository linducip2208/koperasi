<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $jual->nomor ?? $jual->id }}</title>
    <style>
        @page { size: {{ $width }}mm auto; margin: 2mm; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', Consolas, monospace;
            font-size: 10px;
            line-height: 1.35;
            margin: 0;
            padding: 4mm;
            width: {{ $width }}mm;
            color: #000;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: 700; }
        .big { font-size: 13px; font-weight: 700; }
        hr.dash { border: none; border-top: 1px dashed #000; margin: 4px 0; }
        hr.solid { border: none; border-top: 1px solid #000; margin: 4px 0; }
        .row { display: flex; justify-content: space-between; gap: 4px; }
        .row .label { flex: 1; }
        .row .val { white-space: nowrap; }
        table.items { width: 100%; border-collapse: collapse; font-size: 10px; }
        table.items td { padding: 1px 0; vertical-align: top; }
        .total-row { font-weight: 700; font-size: 11px; }
        .footer { text-align: center; font-size: 9px; margin-top: 6px; }
        @media screen {
            body { background: #f3f4f6; padding: 8mm; box-shadow: 0 4px 16px rgba(0,0,0,0.1); margin: 12mm auto; border-radius: 4px; }
        }
        .auto-print-info { background: #fef3c7; border: 1px dashed #f59e0b; padding: 8px; margin-bottom: 10px; font-size: 11px; text-align: center; border-radius: 4px; }
        .toolbar { background: #ecfeff; border: 1px solid #67e8f9; padding: 6px; margin-bottom: 10px; font-size: 11px; text-align: center; border-radius: 4px; }
        .toolbar button { background: #0891b2; color: white; border: 0; padding: 4px 10px; border-radius: 3px; cursor: pointer; font-size: 11px; margin: 0 2px; }
        .toolbar button:hover { background: #0e7490; }
        @media print { .auto-print-info, .toolbar, .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="auto-print-info no-print">
        🖨️ Tekan <strong>Ctrl+P</strong> · pilih printer thermal · ukuran kertas <strong>{{ $width }}mm</strong>
    </div>
    <div class="toolbar no-print">
        <button onclick="window.print()">🖨 Cetak Lagi</button>
        <button onclick="window.location.href='?size=58'">58mm</button>
        <button onclick="window.location.href='?size=80'">80mm</button>
    </div>

    <div class="center">
        <div class="big">{{ $tenant->nama ?? 'KoperasiApp' }}</div>
        @if($tenant && $tenant->alamat)
            <div>{{ $tenant->alamat }}</div>
        @endif
        @if($tenant && $tenant->telp)
            <div>Telp: {{ $tenant->telp }}</div>
        @endif
    </div>

    <hr class="solid">

    <div class="row"><div class="label">No</div><div class="val">: {{ $jual->nomor ?? $jual->id }}</div></div>
    <div class="row"><div class="label">Tgl</div><div class="val">: {{ \Carbon\Carbon::parse($jual->tanggal)->format('d/m/y') }} {{ $jual->created_at?->format('H:i') }}</div></div>
    <div class="row"><div class="label">Kasir</div><div class="val">: {{ optional(auth()->user())->name ?? 'Petugas' }}</div></div>
    <div class="row"><div class="label">Pembeli</div><div class="val">: {{ $jual->anggota->nama ?? 'Umum' }}</div></div>

    <hr class="dash">

    <table class="items">
        @foreach($jual->detail as $d)
            <tr>
                <td colspan="2">{{ $d->barang->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;{{ $d->qty }} x {{ number_format($d->harga ?? 0, 0, ',', '.') }}</td>
                <td class="right">{{ number_format(($d->qty ?? 0) * ($d->harga ?? 0), 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <hr class="dash">

    <div class="row"><div class="label">Subtotal</div><div class="val">{{ number_format($jual->subtotal ?? $jual->total ?? 0, 0, ',', '.') }}</div></div>
    @if(($jual->diskon ?? 0) > 0)
        <div class="row"><div class="label">Diskon</div><div class="val">-{{ number_format($jual->diskon, 0, ',', '.') }}</div></div>
    @endif
    @if(($jual->pajak ?? 0) > 0)
        <div class="row"><div class="label">Pajak</div><div class="val">{{ number_format($jual->pajak, 0, ',', '.') }}</div></div>
    @endif
    <hr class="solid">
    <div class="row total-row"><div class="label">TOTAL</div><div class="val">Rp {{ number_format($jual->total ?? 0, 0, ',', '.') }}</div></div>
    <div class="row"><div class="label">Bayar</div><div class="val">{{ ucfirst($jual->metode_bayar ?? 'tunai') }}</div></div>
    @if(($jual->bayar ?? 0) > 0)
        <div class="row"><div class="label">Tunai</div><div class="val">{{ number_format($jual->bayar, 0, ',', '.') }}</div></div>
        <div class="row"><div class="label">Kembali</div><div class="val">{{ number_format($jual->kembali ?? max(0, ($jual->bayar ?? 0) - ($jual->total ?? 0)), 0, ',', '.') }}</div></div>
    @endif

    <hr class="dash">

    <div class="footer">
        Terima kasih atas kunjungan Anda<br>
        Barang yang sudah dibeli<br>
        tidak dapat ditukar/dikembalikan<br>
        <br>
        <strong>{{ $jual->nomor ?? $jual->id }}</strong>
    </div>

    <script>
        window.addEventListener('load', () => {
            setTimeout(() => window.print(), 300);
        });
    </script>
</body>
</html>
