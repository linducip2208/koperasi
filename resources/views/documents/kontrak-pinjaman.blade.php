@extends('documents.layout')
@section('title', 'Kontrak Pinjaman #'.$p->nomor)
@section('content')
    <div class="doc-title">PERJANJIAN PEMBIAYAAN / KONTRAK PINJAMAN</div>

    <p style="font-size:11px;line-height:1.6;text-align:justify;">
        Pada hari ini, <strong>{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('l, d F Y') }}</strong>,
        bertempat di kantor <strong>{{ $tenant->nama ?? 'KoperasiApp' }}</strong>, telah dibuat dan disepakati
        Perjanjian Pembiayaan dengan Akad <strong>{{ strtoupper($p->produk->akad ?? 'KONVENSIONAL') }}</strong>
        antara para pihak sebagai berikut:
    </p>

    <table class="data" style="margin-top:8px;">
        <tr>
            <td style="width:30%;background:#f9fafb;font-weight:700;">PIHAK PERTAMA<br><span style="font-weight:400;font-size:10px;">(Pemberi Pinjaman)</span></td>
            <td>{{ $tenant->nama ?? 'KoperasiApp' }}<br>
                <span style="font-size:10px;color:#6b7280;">{{ $tenant->alamat ?? '' }}</span></td>
        </tr>
        <tr>
            <td style="background:#f9fafb;font-weight:700;">PIHAK KEDUA<br><span style="font-weight:400;font-size:10px;">(Penerima Pinjaman)</span></td>
            <td><strong>{{ $p->anggota->nama ?? '-' }}</strong> · No. Anggota: {{ $p->anggota->kode ?? '-' }}<br>
                <span style="font-size:10px;color:#6b7280;">{{ $p->anggota->alamat ?? '' }} · NIK: {{ $p->anggota->nik ?? '-' }}</span></td>
        </tr>
    </table>

    <h3 style="font-size:12px;margin-top:14px;color:#047857;">Pasal 1 — Pokok Perjanjian</h3>
    <table class="data">
        <tr><td style="width:40%;">Nomor Kontrak</td><td><strong>{{ $p->nomor }}</strong></td></tr>
        <tr><td>Produk Pembiayaan</td><td>{{ $p->produk->nama ?? '-' }} ({{ $p->produk->akad ?? '-' }})</td></tr>
        <tr><td>Plafon Pinjaman</td><td class="nominal">Rp {{ number_format($p->plafon, 0, ',', '.') }}</td></tr>
        <tr><td>Margin / Bagi Hasil / Bunga</td><td>{{ $p->margin_persen ?? $p->bunga_persen ?? 0 }}% per tahun</td></tr>
        <tr><td>Tenor</td><td>{{ $p->tenor }} bulan</td></tr>
        <tr><td>Angsuran per Bulan</td><td class="nominal">Rp {{ number_format($p->angsuran ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td>Total Pengembalian</td><td><strong>Rp {{ number_format(($p->angsuran ?? 0) * ($p->tenor ?? 0), 0, ',', '.') }}</strong></td></tr>
    </table>

    <div class="terbilang">
        <strong>Plafon terbilang:</strong> {{ ucwords(\App\Helpers\Terbilang::make($p->plafon)) }} rupiah
    </div>

    <h3 style="font-size:12px;color:#047857;">Pasal 2 — Kewajiban Pihak Kedua</h3>
    <ol style="font-size:11px;line-height:1.6;padding-left:18px;">
        <li>Membayar angsuran tepat waktu sebesar <strong>Rp {{ number_format($p->angsuran ?? 0, 0, ',', '.') }}</strong> setiap bulan.</li>
        <li>Memberitahukan kepada Pihak Pertama apabila terjadi perubahan alamat atau kontak.</li>
        <li>Menyerahkan jaminan sesuai dengan ketentuan yang disepakati (jika ada).</li>
        <li>Tidak menggunakan dana pinjaman untuk kegiatan yang melanggar hukum atau bertentangan dengan prinsip syariah (untuk akad syariah).</li>
    </ol>

    <h3 style="font-size:12px;color:#047857;">Pasal 3 — Sanksi Keterlambatan</h3>
    <p style="font-size:11px;line-height:1.6;">
        Apabila Pihak Kedua terlambat membayar angsuran, dikenakan denda sebesar <strong>0.1% per hari</strong>
        dari total angsuran yang terlambat. Keterlambatan lebih dari 90 hari akan menyebabkan pembiayaan
        masuk kategori <em>Kurang Lancar</em> dan akan ditindaklanjuti sesuai prosedur kolektibilitas.
    </p>

    <p style="font-size:11px;margin-top:12px;text-align:justify;">
        Demikian Perjanjian ini dibuat dan ditandatangani oleh kedua belah pihak dalam keadaan sehat jasmani dan rohani,
        tanpa paksaan dari pihak manapun, untuk dipergunakan sebagaimana mestinya.
    </p>

    <div class="signature">
        <div class="col">
            <div class="label">PIHAK KEDUA</div>
            <div class="name-line">{{ $p->anggota->nama ?? '' }}</div>
        </div>
        <div class="col">
            <div class="label">SAKSI</div>
            <div class="name-line">_________________</div>
        </div>
        <div class="col">
            <div class="label">PIHAK PERTAMA</div>
            <div class="name-line">{{ $tenant->nama ?? 'KoperasiApp' }}</div>
        </div>
    </div>
@endsection
