@extends('documents.layout')
@section('title', 'Kuitansi Setoran #'.$tx->id)
@section('content')
    <div class="doc-title">KUITANSI PENERIMAAN SETORAN SIMPANAN</div>

    <div class="grid">
        <div class="col" style="width:50%;">
            <div class="label">Nomor Kuitansi</div>
            <div class="value">KS-{{ str_pad($tx->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="col" style="width:50%;">
            <div class="label">Tanggal</div>
            <div class="value">{{ \Carbon\Carbon::parse($tx->tanggal)->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    <div class="grid">
        <div class="col" style="width:50%;">
            <div class="label">Diterima Dari</div>
            <div class="value">{{ $tx->simpanan->anggota->nama ?? '-' }}</div>
            <div style="font-size:10px;color:#6b7280;">No. Anggota: {{ $tx->simpanan->anggota->kode ?? '-' }}</div>
        </div>
        <div class="col" style="width:50%;">
            <div class="label">Jenis Simpanan</div>
            <div class="value">{{ $tx->simpanan->produk->nama ?? '-' }}</div>
            <div style="font-size:10px;color:#6b7280;">Rekening: {{ $tx->simpanan->nomor ?? '-' }}</div>
        </div>
    </div>

    <table class="data">
        <thead>
            <tr><th>Keterangan</th><th class="text-right">Jumlah</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ ucfirst($tx->jenis ?? 'setoran') }} {{ $tx->simpanan->produk->nama ?? '' }}</td>
                <td class="text-right nominal">Rp {{ number_format($tx->jumlah, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>TOTAL DITERIMA</td>
                <td class="text-right">Rp {{ number_format($tx->jumlah, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="terbilang">
        <strong>Terbilang:</strong> {{ ucwords(\App\Helpers\Terbilang::make($tx->jumlah)) }} rupiah
    </div>

    <div class="grid" style="margin-top:14px;">
        <div class="col"><div class="label">Saldo Sebelum</div><div class="value">Rp {{ number_format(max(0, ($tx->saldo_sesudah ?? 0) - $tx->jumlah), 0, ',', '.') }}</div></div>
        <div class="col"><div class="label">Saldo Setelah</div><div class="value">Rp {{ number_format($tx->saldo_sesudah ?? 0, 0, ',', '.') }}</div></div>
        <div class="col"><div class="label">Status</div><div class="value"><span class="stamp">LUNAS DITERIMA</span></div></div>
    </div>

    <div class="signature">
        <div class="col">
            <div class="label">Penyetor</div>
            <div class="name-line">{{ $tx->simpanan->anggota->nama ?? '' }}</div>
        </div>
        <div class="col">
            <div class="label">Mengetahui</div>
            <div class="name-line">Pengurus</div>
        </div>
        <div class="col">
            <div class="label">Kasir</div>
            <div class="name-line">{{ optional(auth()->user())->name ?? 'Petugas' }}</div>
        </div>
    </div>
@endsection
