<?php

namespace App\Domain\Akuntansi;

use App\Models\Coa;
use App\Models\JurnalDetail;
use Illuminate\Support\Carbon;

class LaporanKeuanganService
{
    /**
     * Saldo akun untuk periode tertentu (untuk neraca/L-R).
     * Untuk akun aset/kewajiban/ekuitas: saldo akumulatif sampai $sampai.
     * Untuk akun pendapatan/beban: hanya pergerakan dari $dari sampai $sampai.
     */
    public static function saldoAkun(Coa $coa, ?string $dari, string $sampai, ?int $cabangId = null): int
    {
        $isLR = in_array($coa->tipe, ['pendapatan', 'beban']);

        $query = JurnalDetail::where('coa_id', $coa->id)
            ->whereHas('jurnal', function ($q) use ($dari, $sampai, $isLR, $cabangId) {
                $q->where('is_posted', true)->where('tanggal', '<=', $sampai);
                if ($isLR && $dari) $q->where('tanggal', '>=', $dari);
                if ($cabangId) $q->where('cabang_id', $cabangId);
            });

        $debit  = (int) $query->sum('debit');
        $kredit = (int) $query->sum('kredit');

        $saldo = $coa->saldo_normal === 'debit'
            ? ($isLR ? 0 : $coa->saldo_awal) + $debit - $kredit
            : ($isLR ? 0 : $coa->saldo_awal) + $kredit - $debit;

        return $saldo;
    }

    public static function neraca(string $sampai, ?int $cabangId = null): array
    {
        return [
            'tanggal'   => $sampai,
            'cabang_id' => $cabangId,
            'aset'      => self::groupSaldo('aset', null, $sampai, $cabangId),
            'kewajiban' => self::groupSaldo('kewajiban', null, $sampai, $cabangId),
            'ekuitas'   => self::groupSaldo('ekuitas', null, $sampai, $cabangId),
        ];
    }

    public static function labaRugi(string $dari, string $sampai, ?int $cabangId = null): array
    {
        $pendapatan = self::groupSaldo('pendapatan', $dari, $sampai, $cabangId);
        $beban      = self::groupSaldo('beban', $dari, $sampai, $cabangId);

        $totalPendapatan = collect($pendapatan)->sum('saldo');
        $totalBeban      = collect($beban)->sum('saldo');

        return [
            'periode'         => "{$dari} s/d {$sampai}",
            'pendapatan'      => $pendapatan,
            'beban'           => $beban,
            'total_pendapatan'=> $totalPendapatan,
            'total_beban'     => $totalBeban,
            'shu'             => $totalPendapatan - $totalBeban,
        ];
    }

    public static function arusKas(string $dari, string $sampai, ?int $cabangId = null): array
    {
        // Sederhana: ambil semua pergerakan akun kas/bank
        $kasBankIds = Coa::where(function ($q) {
            $q->where('is_kas', true)->orWhere('is_bank', true);
        })->pluck('id');
        $masuk = (int) JurnalDetail::whereIn('coa_id', $kasBankIds)
            ->whereHas('jurnal', fn ($q) => $q->where('is_posted', true)
                ->whereBetween('tanggal', [$dari, $sampai])
                ->when($cabangId, fn ($qq) => $qq->where('cabang_id', $cabangId)))
            ->sum('debit');

        $keluar = (int) JurnalDetail::whereIn('coa_id', $kasBankIds)
            ->whereHas('jurnal', fn ($q) => $q->where('is_posted', true)
                ->whereBetween('tanggal', [$dari, $sampai])
                ->when($cabangId, fn ($qq) => $qq->where('cabang_id', $cabangId)))
            ->sum('kredit');

        return [
            'periode'   => "{$dari} s/d {$sampai}",
            'masuk'     => $masuk,
            'keluar'    => $keluar,
            'net'       => $masuk - $keluar,
        ];
    }

    /**
     * Laporan Perubahan Ekuitas — SAK EP (Entitas Privat) untuk koperasi.
     * Komponen: Simpanan Pokok, Simpanan Wajib, Cadangan, SHU berjalan/ditahan.
     * Ekuitas awal = posisi s/d H-1 dari periode; mutasi = pergerakan dalam periode.
     */
    public static function perubahanEkuitas(string $dari, string $sampai, ?int $cabangId = null): array
    {
        $sebelum = Carbon::parse($dari)->subDay()->toDateString();

        $awal = self::groupSaldo('ekuitas', null, $sebelum, $cabangId);
        $akhir = self::groupSaldo('ekuitas', null, $sampai, $cabangId);
        $lr = self::labaRugi($dari, $sampai, $cabangId);

        $mapAwal = collect($awal)->keyBy('kode');
        $mapAkhir = collect($akhir)->keyBy('kode');
        $semuaKode = $mapAwal->keys()->merge($mapAkhir->keys())->unique()->values();

        $rincian = $semuaKode->map(function ($kode) use ($mapAwal, $mapAkhir) {
            $a = $mapAwal->get($kode);
            $b = $mapAkhir->get($kode);
            $saldoAwal = (int) ($a['saldo'] ?? 0);
            $saldoAkhir = (int) ($b['saldo'] ?? 0);

            return [
                'kode' => $kode,
                'nama' => $b['nama'] ?? $a['nama'] ?? $kode,
                'saldo_awal' => $saldoAwal,
                'mutasi' => $saldoAkhir - $saldoAwal,
                'saldo_akhir' => $saldoAkhir,
            ];
        })->filter(fn ($r) => $r['saldo_awal'] != 0 || $r['saldo_akhir'] != 0)->values()->all();

        $totalAwal = collect($rincian)->sum('saldo_awal');
        $totalMutasi = collect($rincian)->sum('mutasi');
        $totalAkhir = collect($rincian)->sum('saldo_akhir');

        return [
            'dari' => $dari,
            'sampai' => $sampai,
            'sebelum' => $sebelum,
            'cabang_id' => $cabangId,
            'rincian' => $rincian,
            'total_awal' => $totalAwal,
            'total_mutasi' => $totalMutasi,
            'total_akhir' => $totalAkhir,
            'shu_berjalan' => $lr['shu'] ?? 0,
            'total_pendapatan' => $lr['total_pendapatan'] ?? 0,
            'total_beban' => $lr['total_beban'] ?? 0,
        ];
    }

    /**
     * CALK — Catatan atas Laporan Keuangan (SAK EP, versi ringkas koperasi).
     * Disusun semi-otomatis dari data sistem: profil, kebijakan, rincian akun
     * material, segmen unit usaha, dan peristiwa penting.
     */
    public static function calk(string $dari, string $sampai, ?int $cabangId = null): array
    {
        $neraca = self::neraca($sampai, $cabangId);
        $lr = self::labaRugi($dari, $sampai, $cabangId);
        $kas = self::arusKas($dari, $sampai, $cabangId);
        $ekuitas = self::perubahanEkuitas($dari, $sampai, $cabangId);

        $totalAset = collect($neraca['aset'])->sum('saldo');
        $totalKewajiban = collect($neraca['kewajiban'])->sum('saldo');
        $totalEkuitas = collect($neraca['ekuitas'])->sum('saldo');

        // Akun material: 10 saldo terbesar per kelompok
        $material = function (array $rows, int $limit = 10): array {
            return collect($rows)->sortByDesc(fn ($r) => abs($r['saldo']))
                ->take($limit)->values()->all();
        };

        // Segmen unit usaha (ringkas, tanpa filter cabang ganda)
        $simpanan = \App\Models\Simpanan::query()
            ->selectRaw('COUNT(*) as jml, COALESCE(SUM(saldo),0) as total')
            ->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId))
            ->first();
        $pinjaman = \App\Models\Pinjaman::query()
            ->selectRaw('COUNT(*) as jml, COALESCE(SUM(saldo_pokok),0) as outstanding')
            ->where('status', 'aktif')
            ->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId))
            ->first();

        return [
            'periode' => "{$dari} s/d {$sampai}",
            'dari' => $dari,
            'sampai' => $sampai,
            'cabang_id' => $cabangId,
            'ringkasan' => [
                'total_aset' => $totalAset,
                'total_kewajiban' => $totalKewajiban,
                'total_ekuitas' => $totalEkuitas,
                'total_pendapatan' => $lr['total_pendapatan'],
                'total_beban' => $lr['total_beban'],
                'shu' => $lr['shu'],
                'kas_masuk' => $kas['masuk'],
                'kas_keluar' => $kas['keluar'],
                'kas_bersih' => $kas['net'],
                'ekuitas_awal' => $ekuitas['total_awal'],
                'ekuitas_akhir' => $ekuitas['total_akhir'],
            ],
            'akun_material' => [
                'aset' => $material($neraca['aset']),
                'kewajiban' => $material($neraca['kewajiban']),
                'ekuitas' => $material($neraca['ekuitas']),
                'pendapatan' => $material($lr['pendapatan']),
                'beban' => $material($lr['beban']),
            ],
            'segmen_usaha' => [
                'simpanan_rekening' => (int) ($simpanan->jml ?? 0),
                'simpanan_saldo' => (int) ($simpanan->total ?? 0),
                'pinjaman_aktif' => (int) ($pinjaman->jml ?? 0),
                'pinjaman_outstanding' => (int) ($pinjaman->outstanding ?? 0),
            ],
            'kebijakan' => [
                'basis' => 'Laporan disusun berdasarkan SAK EP (Standar Akuntansi Keuangan Entitas Privat) dengan basis akrual, kecuali laporan arus kas yang disusun dengan basis kas.',
                'mata_uang' => 'Mata uang pelaporan adalah Rupiah (Rp). Seluruh angka disajikan dalam Rupiah penuh tanpa desimal.',
                'simpanan' => 'Simpanan pokok, wajib, dan sukarela anggota diakui sebagai ekuitas sesuai karakteristik koperasi. Simpanan berjangka yang memiliki kewajiban pembayaran kembali diakui sebagai kewajiban.',
                'pendapatan_beban' => 'Pendapatan diakui pada saat jasa diberikan / bunga berjalan. Beban diakui secara akrual mengikuti periode manfaat.',
                'penyusutan' => 'Aset tetap disusutkan dengan metode garis lurus selama umur ekonomis. Lihat modul Aset Tetap untuk rincian per aset.',
            ],
        ];
    }

    private static function groupSaldo(string $tipe, ?string $dari, string $sampai, ?int $cabangId = null): array
    {
        $coas = Coa::where('tipe', $tipe)->where('is_aktif', true)
            ->whereNull('parent_id')
            ->orWhere(fn ($q) => $q->where('tipe', $tipe)->where('is_postable', true))
            ->get();

        return $coas->map(fn ($c) => [
            'kode'  => $c->kode,
            'nama'  => $c->nama,
            'saldo' => self::saldoAkun($c, $dari, $sampai, $cabangId),
        ])->filter(fn ($r) => $r['saldo'] != 0)->values()->all();
    }
}
