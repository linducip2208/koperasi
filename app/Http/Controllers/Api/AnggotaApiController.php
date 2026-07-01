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
}
