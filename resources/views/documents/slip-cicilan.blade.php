@extends('documents.layout')
@section('title', 'Slip Cicilan #'.$bayar->id)
@section('content')
    <div class="doc-title">SLIP PEMBAYARAN ANGSURAN PINJAMAN</div>

    <div class="grid">
        <div class="col" style="width:50%;">
            <div class="label">No. Slip</div>
            <div class="value">SC-{{ str_pad($bayar->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="col" style="width:50%;">
            <div class="label">Tanggal Bayar</div>
            <div class="value">{{ \Carbon\Carbon::parse($bayar->tanggal)->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    <div class="grid">
        <div class="col" style="width:50%;">
            <div class="label">Nasabah</div>
            <div class="value">{{ $bayar->pinjaman->anggota->nama ?? '-' }}</div>
            <div style="font-size:10px;color:#6b7280;">No. Anggota: {{ $bayar->pinjaman->anggota->kode ?? '-' }}</div>
        </div>
        <div class="col" style="width:50%;">
            <div class="label">No. Pinjaman</div>
            <div class="value">{{ $bayar->pinjaman->nomor ?? '-' }}</div>
            <div style="font-size:10px;color:#6b7280;">Produk: {{ $bayar->pinjaman->produk->nama ?? '-' }}</div>
        </div>
    </div>

    <table class="data">
        <thead><tr><th>Komponen</th><th class="text-right">Jumlah</th></tr></thead>
        <tbody>
            <tr><td>Angsuran Pokok</td><td class="text-right">Rp {{ number_format($bayar->pokok ?? 0, 0, ',', '.') }}</td></tr>
            <tr><td>Margin / Bagi Hasil / Bunga</td><td class="text-right">Rp {{ number_format($bayar->margin ?? $bayar->bunga ?? 0, 0, ',', '.') }}</td></tr>
            @if(($bayar->denda ?? 0) > 0)
                <tr><td>Denda Keterlambatan</td><td class="text-right">Rp {{ number_format($bayar->denda, 0, ',', '.') }}</td></tr>
            @endif
            <tr class="total">
                <td>TOTAL DIBAYARKAN</td>
                <td class="text-right">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="terbilang">
        <strong>Terbilang:</strong> {{ ucwords(\App\Helpers\Terbilang::make($bayar->jumlah)) }} rupiah
    </div>

    <div class="grid" style="margin-top:14px;">
        <div class="col"><div class="label">Sisa Pokok</div><div class="value">Rp {{ number_format($bayar->pinjaman->saldo_pokok ?? 0, 0, ',', '.') }}</div></div>
        <div class="col"><div class="label">Metode</div><div class="value">{{ ucfirst(str_replace('_', ' ', $bayar->metode ?? 'cash')) }}</div></div>
        <div class="col"><div class="label">Status</div><div class="value"><span class="stamp">DITERIMA</span></div></div>
    </div>

    <div class="signature">
        <div class="col">
            <div class="label">Nasabah</div>
            <div class="name-line">{{ $bayar->pinjaman->anggota->nama ?? '' }}</div>
        </div>
        <div class="col">
            <div class="label">Validasi</div>
            <div class="name-line">_________________</div>
        </div>
        <div class="col">
            <div class="label">Kasir</div>
            <div class="name-line">{{ optional(auth()->user())->name ?? 'Petugas' }}</div>
        </div>
    </div>
@endsection
