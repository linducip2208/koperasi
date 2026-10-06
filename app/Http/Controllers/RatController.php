<?php

namespace App\Http\Controllers;

use App\Domain\Akuntansi\LaporanKeuanganService;
use App\Models\Anggota;
use App\Models\Rat;
use App\Models\RatKehadiran;
use App\Models\RatVotingSuara;
use App\Models\ShuPerhitungan;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RatController extends Controller
{
    /** Halaman check-in kehadiran (dibuka via QR signed URL atau manual admin). */
    public function checkin(Rat $rat)
    {
        $rat->loadCount('kehadiran');

        return view('rat.checkin', [
            'rat' => $rat,
            'persen' => $rat->quorumAktualPersen(),
        ]);
    }

    /** Simpan check-in: cegah double, update quorum otomatis via model event. */
    public function storeCheckin(Request $request, Rat $rat)
    {
        $validated = $request->validate([
            'nomor_anggota' => ['required', 'string', 'max:50'],
        ]);

        $anggota = Anggota::where('nomor_anggota', $validated['nomor_anggota'])
            ->where('status', 'aktif')
            ->first();

        if (! $anggota) {
            return back()->withErrors(['nomor_anggota' => 'Anggota tidak ditemukan / tidak aktif.']);
        }

        $sudah = RatKehadiran::where('rat_id', $rat->id)
            ->where('anggota_id', $anggota->id)->exists();

        if ($sudah) {
            return back()->with('flash', "✅ {$anggota->nama} sudah tercatat hadir.");
        }

        RatKehadiran::create([
            'tenant_id' => $rat->tenant_id,
            'rat_id' => $rat->id,
            'anggota_id' => $anggota->id,
            'checkin_at' => now(),
            'metode' => $request->hasValidSignature() ? 'qr' : 'manual',
        ]);

        $rat->refresh();

        return back()->with('flash', "✅ Kehadiran {$anggota->nama} tercatat. Total hadir: {$rat->jumlah_hadir} ({$rat->quorumAktualPersen()}%).");
    }

    /** Buku Tahunan RAT — paket PDF: pengesahan, quorum, laporan SAK EP, SHU, voting, keputusan, daftar hadir. */
    public function bukuTahunan(Request $request, Rat $rat)
    {
        $tahun = $rat->tahun_buku;
        $dari = "{$tahun}-01-01";
        $sampai = "{$tahun}-12-31";

        $neraca = LaporanKeuanganService::neraca($sampai);
        $labaRugi = LaporanKeuanganService::labaRugi($dari, $sampai);
        $ekuitas = LaporanKeuanganService::perubahanEkuitas($dari, $sampai);
        $kas = LaporanKeuanganService::arusKas($dari, $sampai);

        $shu = ShuPerhitungan::where('tahun', $tahun)->with('distribusi')->first();

        $rat->load(['kehadiran.anggota', 'votings.suara']);

        $votingHasil = $rat->votings->map(function ($v) {
            $total = $v->suara->count();
            $perOpsi = [];
            foreach ((array) $v->opsi as $i => $nama) {
                $c = $v->suara->where('opsi_index', $i)->count();
                $perOpsi[] = ['nama' => $nama, 'suara' => $c, 'persen' => $total > 0 ? round($c * 100 / $total, 1) : 0];
            }

            return ['judul' => $v->judul, 'total' => $total, 'opsi' => $perOpsi];
        });

        $data = [
            'rat' => $rat,
            'tahun' => $tahun,
            'tenant' => \App\Support\CooperativeContext::current(),
            'cabang' => null,
            'neraca' => $neraca,
            'labaRugi' => $labaRugi,
            'ekuitas' => $ekuitas,
            'kas' => $kas,
            'shu' => $shu,
            'votingHasil' => $votingHasil,
            'totalSuaraMasuk' => RatVotingSuara::whereIn('voting_id', $rat->votings->pluck('id'))->count(),
        ];

        $pdf = Pdf::loadView('rat.buku-tahunan', $data)->setPaper('a4');

        return $request->boolean('download')
            ? $pdf->download("buku-tahunan-rat-{$tahun}.pdf")
            : $pdf->stream("buku-tahunan-rat-{$tahun}.pdf");
    }
}
