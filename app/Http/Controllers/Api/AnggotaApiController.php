<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnggotaApiController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

        return response()->json([
            'data' => [
                'nomor_anggota' => $anggota->nomor_anggota,
                'nama'          => $anggota->nama,
                'email'         => $anggota->email,
                'telp'          => $anggota->telp,
                'foto'          => $anggota->foto_path,
                'status'        => $anggota->status,
                'kategori'      => $anggota->kategori,
                'total_simpanan' => $anggota->totalSimpanan(),
            ],
        ]);
    }

    public function simpanan(Request $request): JsonResponse
    {
        $user = $request->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

        $simpanan = Simpanan::with('produk')->where('anggota_id', $anggota->id)->get()
            ->map(fn ($s) => [
                'id'             => $s->id,
                'nomor_rekening' => $s->nomor_rekening,
                'produk'         => $s->produk?->nama,
                'saldo'          => $s->saldo,
                'saldo_blokir'   => $s->saldo_blokir,
                'status'         => $s->status,
            ]);

        return response()->json([
            'data'  => $simpanan,
            'total' => $simpanan->sum('saldo'),
        ]);
    }

    public function pinjaman(Request $request): JsonResponse
    {
        $user = $request->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();
        $pinjaman = Pinjaman::with(['produk', 'jadwal' => fn ($q) => $q->where('status', '!=', 'lunas')->orderBy('angsuran_ke')->limit(3)])
            ->where('anggota_id', $anggota->id)
            ->where('status', 'aktif')
            ->get()
            ->map(fn ($p) => [
                'id'              => $p->id,
                'nomor_akad'      => $p->nomor_akad,
                'produk'          => $p->produk?->nama,
                'plafon'          => $p->plafon,
                'tenor'           => $p->tenor,
                'saldo_pokok'     => $p->saldo_pokok,
                'saldo_margin'    => $p->saldo_margin,
                'tunggakan_hari'  => $p->tunggakan_hari,
                'kolektabilitas'  => $p->kolektabilitas,
                'jadwal_terdekat' => $p->jadwal->map(fn ($j) => [
                    'angsuran_ke'         => $j->angsuran_ke,
                    'tanggal_jatuh_tempo' => $j->tanggal_jatuh_tempo->format('Y-m-d'),
                    'total_angsuran'      => $j->total_angsuran,
                    'sisa'                => $j->sisa(),
                ]),
            ]);

        return response()->json(['data' => $pinjaman]);
    }

    /** Notifikasi anggota: pengumuman published + angsuran jatuh tempo 7 hari. */
    public function notifikasi(Request $request): JsonResponse
    {
        $user = $request->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

        $pengumuman = \App\Models\Pengumuman::published()->orderByDesc('published_at')->limit(10)->get()
            ->map(fn ($p) => ['tipe' => 'pengumuman', 'judul' => $p->judul, 'isi' => $p->isi, 'waktu' => $p->published_at]);

        $jatuhTempo = Pinjaman::with('jadwal')->where('anggota_id', $anggota->id)->where('status', 'aktif')->get()
            ->flatMap(fn ($p) => $p->jadwal->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo'])
                ->filter(fn ($j) => \Carbon\Carbon::parse($j->tanggal_jatuh_tempo)->between(now(), now()->addDays(7)))
                ->map(fn ($j) => ['tipe' => 'angsuran', 'judul' => "Angsuran {$p->nomor_akad} jatuh tempo",
                    'isi' => 'Rp '.number_format($j->total_angsuran, 0, ',', '.').' pada '.$j->tanggal_jatuh_tempo->format('d M Y'),
                    'waktu' => $j->tanggal_jatuh_tempo]));

        return response()->json(['data' => $pengumuman->concat($jatuhTempo)->sortByDesc('waktu')->values()]);
    }

    /** Riwayat gabungan (simpanan + angsuran) milik sendiri — paginasi. */
    public function transaksi(Request $request): JsonResponse
    {
        $user = $request->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();
        $perPage = min(100, max(10, (int) $request->input('per_page', 20)));

        $simpananIds = \App\Models\Simpanan::where('anggota_id', $anggota->id)->pluck('id');
        $pinjamanIds = Pinjaman::where('anggota_id', $anggota->id)->pluck('id');

        $simpananTrx = \App\Models\SimpananTransaksi::whereIn('simpanan_id', $simpananIds)
            ->select('id', 'tanggal', 'jenis', 'jumlah', 'keterangan')
            ->selectRaw("'simpanan' as kategori");
        $pinjamanTrx = \App\Models\PinjamanPembayaran::whereIn('pinjaman_id', $pinjamanIds)
            ->where('status', 'disetujui')
            ->select('id', 'tanggal', 'jenis', 'total_bayar as jumlah', 'keterangan')
            ->selectRaw("'pinjaman' as kategori");

        $semua = $simpananTrx->get()->concat($pinjamanTrx->get())
            ->sortByDesc('tanggal')->values();
        $page = max(1, (int) $request->input('page', 1));
        $items = $semua->forPage($page, $perPage)->values();

        return response()->json([
            'data' => $items,
            'meta' => ['current_page' => $page, 'per_page' => $perPage, 'total' => $semua->count()],
        ]);
    }
}
