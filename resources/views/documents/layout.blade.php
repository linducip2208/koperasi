<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dokumen')</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 18px; }
        .header { display: table; width: 100%; border-bottom: 2px solid #047857; padding-bottom: 8px; margin-bottom: 14px; }
        .header-left { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; font-size: 9px; color: #6b7280; }
        .brand { font-size: 18px; font-weight: 800; color: #047857; letter-spacing: -0.5px; }
        .tagline { font-size: 9px; color: #6b7280; margin-top: 2px; }
        .doc-title { font-size: 14px; font-weight: 700; text-align: center; padding: 8px 0; background: #f0fdf4; border-radius: 4px; margin-bottom: 12px; color: #064e3b; }
        .grid { display: table; width: 100%; margin-bottom: 12px; }
        .grid .col { display: table-cell; vertical-align: top; padding: 4px 8px; }
        .label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; font-weight: 700; }
        .value { font-size: 12px; font-weight: 600; color: #111827; margin-top: 2px; }
        table.data { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table.data th, table.data td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; font-size: 11px; }
        table.data th { background: #f9fafb; font-size: 10px; text-transform: uppercase; letter-spacing: 0.4px; color: #4b5563; }
        table.data tr.total td { background: #f0fdf4; font-weight: 700; color: #064e3b; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .nominal { font-weight: 700; color: #047857; font-size: 14px; }
        .signature { display: table; width: 100%; margin-top: 28px; }
        .signature .col { display: table-cell; width: 33.33%; text-align: center; padding: 0 10px; vertical-align: top; }
        .signature .name-line { border-top: 1px solid #1f2937; margin-top: 50px; padding-top: 4px; font-weight: 600; }
        .footer { position: fixed; bottom: 12px; left: 18px; right: 18px; text-align: center; font-size: 8px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 4px; }
        .stamp { display: inline-block; padding: 4px 10px; background: #047857; color: #fff; font-size: 9px; font-weight: 700; border-radius: 3px; letter-spacing: 0.5px; }
        .terbilang { font-style: italic; font-size: 10px; color: #4b5563; padding: 6px 10px; background: #fffbeb; border-left: 3px solid #f59e0b; margin: 8px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="brand">{{ $tenant->nama ?? 'KoperasiApp' }}</div>
            <div class="tagline">{{ $tenant->slogan ?? 'Sejahtera Bersama Anggota' }}</div>
        </div>
        <div class="header-right">
            @if($tenant && $tenant->alamat)
                {{ $tenant->alamat }}<br>
            @endif
            @if($tenant && $tenant->telepon)
                Telp: {{ $tenant->telepon }}
            @endif
        </div>
    </div>

    @yield('content')

    <div class="footer">
        Dicetak otomatis pada {{ now()->format('d/m/Y H:i') }} · Dokumen sah tanpa tanda tangan basah · {{ $tenant->nama ?? 'KoperasiApp' }}
    </div>
</body>
</html>
