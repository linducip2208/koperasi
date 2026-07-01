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
