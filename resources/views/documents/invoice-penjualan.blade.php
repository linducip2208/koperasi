@extends('documents.layout')
@section('title', 'Invoice #'.$jual->nomor)
@section('content')
    <div class="doc-title">INVOICE / FAKTUR PENJUALAN</div>

    <div class="grid">
        <div class="col" style="width:50%;">
            <div class="label">No. Faktur</div>
            <div class="value">{{ $jual->nomor ?? 'INV-'.str_pad($jual->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="col" style="width:50%;">
            <div class="label">Tanggal</div>
            <div class="value">{{ \Carbon\Carbon::parse($jual->tanggal)->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    <div class="grid">
        <div class="col" style="width:60%;">
            <div class="label">Pembeli</div>
            <div class="value">{{ $jual->anggota->nama ?? 'Pelanggan Umum' }}</div>
            <div style="font-size:10px;color:#6b7280;">{{ $jual->anggota->alamat ?? '' }}</div>
        </div>
        <div class="col" style="width:40%;">
            <div class="label">Metode Bayar</div>
            <div class="value">{{ ucfirst($jual->metode_bayar ?? 'tunai') }}</div>
        </div>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th>Barang</th>
                <th class="text-center" style="width:8%;">Qty</th>
                <th class="text-right" style="width:18%;">Harga</th>
                <th class="text-right" style="width:18%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jual->detail as $i => $d)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $d->barang->nama ?? '-' }}<br><span style="font-size:9px;color:#6b7280;">{{ $d->barang->kode ?? '' }}</span></td>
                    <td class="text-center">{{ $d->qty ?? 0 }}</td>
                    <td class="text-right">Rp {{ number_format($d->harga ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format(($d->qty ?? 0) * ($d->harga ?? 0), 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="4" class="text-right">TOTAL</td>
                <td class="text-right">Rp {{ number_format($jual->total ?? 0, 0, ',', '.') }}</td>
            </tr>
            @if(($jual->diskon ?? 0) > 0)
                <tr>
                    <td colspan="4" class="text-right">Diskon</td>
                    <td class="text-right">- Rp {{ number_format($jual->diskon, 0, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td colspan="4" class="text-right">GRAND TOTAL</td>
                    <td class="text-right">Rp {{ number_format(($jual->total ?? 0) - ($jual->diskon ?? 0), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="terbilang">
        <strong>Terbilang:</strong> {{ ucwords(\App\Helpers\Terbilang::make(($jual->total ?? 0) - ($jual->diskon ?? 0))) }} rupiah
    </div>

    <div class="signature">
        <div class="col">
            <div class="label">Pembeli</div>
            <div class="name-line">{{ $jual->anggota->nama ?? '_________________' }}</div>
        </div>
        <div class="col">
            <div class="label">Hormat Kami</div>
            <div class="name-line">{{ $tenant->nama ?? 'KoperasiApp' }}</div>
        </div>
        <div class="col">
            <div class="label">Status</div>
            <div style="margin-top:50px;"><span class="stamp">{{ strtoupper($jual->status ?? 'LUNAS') }}</span></div>
        </div>
    </div>
@endsection
