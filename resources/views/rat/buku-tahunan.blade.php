@extends('laporan.layout')

@section('title', 'BUKU TAHUNAN RAT — TAHUN BUKU ' . $tahun)
@section('periode', 'RAT tanggal ' . \Carbon\Carbon::parse($rat->tanggal)->format('d F Y') . ($rat->lokasi ? ' • ' . $rat->lokasi : ''))

@section('content')
    <div class="section-title">1. PENGESAHAN & QUORUM</div>
    <table>
        <tbody>
            <tr><td style="width:220px;">Tahun Buku</td><td><strong>{{ $tahun }}</strong></td></tr>
            <tr><td>Status RAT</td><td><strong>{{ strtoupper($rat->status) }}</strong></td></tr>
            <tr><td>Anggota Terdaftar</td><td>{{ number_format($rat->jumlah_anggota_terdaftar) }}</td></tr>
            <tr><td>Jumlah Hadir (tercatat sistem)</td><td><strong>{{ number_format($rat->jumlah_hadir) }} ({{ $rat->quorumAktualPersen() }}%)</strong></td></tr>
            <tr><td>Quorum Minimal</td><td>{{ $rat->quorum_persen }}% — <strong>{{ $rat->quorum_tercapai ? 'TERCAPAI ✅' : 'BELUM TERCAPAI ⏳' }}</strong></td></tr>
        </tbody>
    </table>

    <div class="section-title">2. AGENDA</div>
    <ol>
        @forelse((array) $rat->agenda as $a)
            <li>{{ is_array($a) ? ($a['item'] ?? json_encode($a)) : $a }}</li>
        @empty
            <li><em>Agenda belum diinput.</em></li>
        @endforelse
    </ol>

    <div class="section-title">3. LAPORAN POSISI KEUANGAN / NERACA (Rp) — per 31 Des {{ $tahun }}</div>
    <table>
        <thead><tr><th>Kode</th><th>Akun</th><th class="text-right">Saldo</th></tr></thead>
        <tbody>
            @foreach(array_merge($neraca['aset'], $neraca['kewajiban'], $neraca['ekuitas']) as $row)
                <tr><td style="width:80px;">{{ $row['kode'] }}</td><td>{{ $row['nama'] }}</td><td class="text-right">{{ number_format($row['saldo'], 0, ',', '.') }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">4. LAPORAN SHU (Rp) — 1 Jan s/d 31 Des {{ $tahun }}</div>
    <table>
        <tbody>
            <tr><td>Total Pendapatan</td><td class="text-right" style="width:180px;">{{ number_format($labaRugi['total_pendapatan'], 0, ',', '.') }}</td></tr>
            <tr><td>Total Beban</td><td class="text-right">{{ number_format($labaRugi['total_beban'], 0, ',', '.') }}</td></tr>
            <tr class="total"><td>SHU SEBELUM PAJAK</td><td class="text-right">{{ number_format($labaRugi['shu'], 0, ',', '.') }}</td></tr>
            <tr><td>Ekuitas Awal → Akhir</td><td class="text-right">{{ number_format($ekuitas['total_awal'], 0, ',', '.') }} → {{ number_format($ekuitas['total_akhir'], 0, ',', '.') }}</td></tr>
            <tr><td>Kas Bersih (masuk − keluar)</td><td class="text-right">{{ number_format($kas['net'], 0, ',', '.') }}</td></tr>
        </tbody>
    </table>

    <div class="section-title">5. DISTRIBUSI SHU</div>
    @if($shu)
        <table>
            <tbody>
                <tr><td>SHU Total ditetapkan</td><td class="text-right" style="width:180px;">{{ number_format($shu->shu_total, 0, ',', '.') }}</td></tr>
                <tr><td>Jasa Modal ({{ $shu->persen_jasa_modal }}%)</td><td class="text-right">{{ number_format($shu->jumlah_jasa_modal, 0, ',', '.') }}</td></tr>
                <tr><td>Jasa Anggota ({{ $shu->persen_jasa_anggota }}%)</td><td class="text-right">{{ number_format($shu->jumlah_jasa_anggota, 0, ',', '.') }}</td></tr>
                <tr><td>Dana Cadangan / Pendidikan / Sosial / Pengurus / Karyawan</td><td class="text-right">{{ number_format($shu->jumlah_dana_cadangan + $shu->jumlah_dana_pendidikan + $shu->jumlah_dana_sosial + $shu->jumlah_dana_pengurus + $shu->jumlah_dana_karyawan, 0, ',', '.') }}</td></tr>
                <tr><td>Status</td><td class="text-right"><strong>{{ strtoupper($shu->status) }}</strong></td></tr>
            </tbody>
        </table>
    @else
        <p><em>Belum ada perhitungan SHU tahun {{ $tahun }} di sistem.</em></p>
    @endif

    <div class="section-title">6. HASIL E-VOTING</div>
    @forelse($votingHasil as $vh)
        <p style="font-weight:bold;">{{ $vh['judul'] }} — {{ $vh['total'] }} suara</p>
        <table>
            <tbody>
                @foreach($vh['opsi'] as $o)
                    <tr><td>{{ $o['nama'] }}</td><td class="text-right" style="width:180px;">{{ $o['suara'] }} suara ({{ $o['persen'] }}%)</td></tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p><em>Tidak ada voting pada RAT ini.</em></p>
    @endforelse

    <div class="section-title">7. KEPUTUSAN RAT</div>
    <ol>
        @forelse((array) $rat->keputusan as $k)
            <li>{{ is_array($k) ? ($k['butir'] ?? json_encode($k)) : $k }}</li>
        @empty
            <li><em>Keputusan belum diinput.</em></li>
        @endforelse
    </ol>

    @if($rat->notulen)
        <div class="section-title">8. NOTULEN</div>
        <p style="white-space:pre-line;">{{ $rat->notulen }}</p>
    @endif

    <div class="section-title">9. DAFTAR HADIR ({{ $rat->kehadiran->count() }} orang)</div>
    <table>
        <thead><tr><th>No</th><th>Nomor Anggota</th><th>Nama</th><th class="text-center">Waktu</th><th class="text-center">Metode</th></tr></thead>
        <tbody>
            @foreach($rat->kehadiran as $i => $h)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $h->anggota->nomor_anggota ?? '—' }}</td>
                    <td>{{ $h->anggota->nama ?? '—' }}</td>
                    <td class="text-center">{{ $h->checkin_at?->format('d M H:i') }}</td>
                    <td class="text-center">{{ strtoupper($h->metode) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <div class="sig-box"><p>Ketua,</p><br><br><br><p style="border-top:1px solid #000;padding-top:5px;"><strong>( .................... )</strong></p></div>
        <div class="sig-box"><p>Sekretaris,</p><br><br><br><p style="border-top:1px solid #000;padding-top:5px;"><strong>( .................... )</strong></p></div>
        <div class="sig-box"><p>Pengawas,</p><br><br><br><p style="border-top:1px solid #000;padding-top:5px;"><strong>( .................... )</strong></p></div>
    </div>
@endsection
