<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        .header { text-align: center; border-bottom: 2px solid #047857; padding-bottom: 10px; margin-bottom: 14px; }
        .header h1 { font-size: 16px; margin: 0; color: #047857; }
        .header h2 { font-size: 13px; margin: 5px 0; }
        .header p { margin: 2px 0; font-size: 10px; }
        .filter-bar {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 6px 10px;
            margin-bottom: 12px;
            border-radius: 4px;
            font-size: 10px;
        }
        .filter-bar strong { color: #047857; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f0fdf4; text-align: left; padding: 6px; border-bottom: 1px solid #047857; }
        td { padding: 5px 6px; border-bottom: 1px solid #e5e7eb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total { font-weight: bold; background: #f0fdf4; }
        .grand-total { font-weight: bold; background: #047857; color: white; }
        .section-title { font-size: 12px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; color: #047857; }
        .footer { margin-top: 30px; font-size: 9px; color: #6b7280; text-align: center; }
        .signature { margin-top: 50px; }
        .sig-box { display: inline-block; width: 30%; text-align: center; vertical-align: top; }
    </style>
</head>
<body>
    <div class="header">
        @if(($tenant->logo_path ?? null))
            <img src="{{ public_path('storage/' . $tenant->logo_path) }}" style="max-height: 40px;">
        @endif
        <h1>{{ $tenant->nama ?? config('app.name') }}</h1>
        @if(($tenant->alamat ?? null))
            <p>{{ $tenant->alamat }} @if($tenant->telp ?? null) | Telp: {{ $tenant->telp }} @endif</p>
        @endif
        <h2>@yield('title')</h2>
        <p>@yield('periode')</p>
    </div>

    @if(($cabang ?? null) || (isset($produkSimpanan) && $produkSimpanan) || (isset($produkPinjaman) && $produkPinjaman))
        <div class="filter-bar">
            <strong>Filter aktif:</strong>
            @if($cabang ?? null) Cabang: <strong>{{ $cabang->nama }}</strong> @endif
            @if(isset($produkSimpanan) && $produkSimpanan) | Produk Simpanan: <strong>{{ $produkSimpanan->nama }}</strong> @endif
            @if(isset($produkPinjaman) && $produkPinjaman) | Produk Pinjaman: <strong>{{ $produkPinjaman->nama }}</strong> @endif
        </div>
    @else
        <div class="filter-bar"><strong>Konsolidasi:</strong> Semua Cabang &amp; Semua Produk</div>
    @endif

    @yield('content')

    <div class="footer">
        Dicetak {{ now()->format('d M Y H:i') }} oleh {{ auth()->user()->name ?? 'Sistem' }}
    </div>
</body>
</html>
