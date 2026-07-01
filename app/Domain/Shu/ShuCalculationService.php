<?php

namespace App\Domain\Shu;

use App\Models\Anggota;
use App\Models\PinjamanPembayaran;
use App\Models\ShuDistribusi;
use App\Models\ShuPerhitungan;
use App\Models\Simpanan;
use App\Models\TokoPenjualan;
use App\Support\Tenant\CurrentTenant;
use Illuminate\Support\Facades\DB;

class ShuCalculationService
{
    public static function hitung(int $tahun, int $shuTotal, array $persen): ShuPerhitungan
    {
        return DB::transaction(function () use ($tahun, $shuTotal, $persen) {
            $perhitungan = ShuPerhitungan::updateOrCreate(
                ['tahun' => $tahun],
                [
                    'shu_total'              => $shuTotal,
                    'persen_jasa_modal'      => $persen['jasa_modal']      ?? 25,
                    'persen_jasa_anggota'    => $persen['jasa_anggota']    ?? 25,
                    'persen_dana_cadangan'   => $persen['dana_cadangan']   ?? 25,
                    'persen_dana_pendidikan' => $persen['dana_pendidikan'] ?? 5,
                    'persen_dana_sosial'     => $persen['dana_sosial']     ?? 5,
                    'persen_dana_pengurus'   => $persen['dana_pengurus']   ?? 10,
                    'persen_dana_karyawan'   => $persen['dana_karyawan']   ?? 5,
                    'jumlah_jasa_modal'      => (int) round($shuTotal * (($persen['jasa_modal']      ?? 25) / 100)),
                    'jumlah_jasa_anggota'    => (int) round($shuTotal * (($persen['jasa_anggota']    ?? 25) / 100)),
                    'jumlah_dana_cadangan'   => (int) round($shuTotal * (($persen['dana_cadangan']   ?? 25) / 100)),
                    'jumlah_dana_pendidikan' => (int) round($shuTotal * (($persen['dana_pendidikan'] ?? 5) / 100)),
                    'jumlah_dana_sosial'     => (int) round($shuTotal * (($persen['dana_sosial']     ?? 5) / 100)),
                    'jumlah_dana_pengurus'   => (int) round($shuTotal * (($persen['dana_pengurus']   ?? 10) / 100)),
                    'jumlah_dana_karyawan'   => (int) round($shuTotal * (($persen['dana_karyawan']   ?? 5) / 100)),
                    'status'                 => 'draft',
                ]
            );

            self::distribusikan($perhitungan, $tahun);

            return $perhitungan->refresh();
        });
    }

    private static function distribusikan(ShuPerhitungan $perhitungan, int $tahun): void
    {
        $tanggalAwal  = "{$tahun}-01-01";
        $tanggalAkhir = "{$tahun}-12-31";

        $anggotas = Anggota::where('status', 'aktif')->get();

        $totalSimpananGlobal = (int) Simpanan::where('status', 'aktif')->sum('saldo');
        $totalTransaksiGlobal = self::totalTransaksiGlobal($tanggalAwal, $tanggalAkhir);

        if ($totalSimpananGlobal === 0 && $totalTransaksiGlobal === 0) {
            return;
        }

        ShuDistribusi::where('shu_perhitungan_id', $perhitungan->id)->delete();

        foreach ($anggotas as $anggota) {
            $totalSimpanan  = (int) Simpanan::where('anggota_id', $anggota->id)
                ->where('status', 'aktif')->sum('saldo');
            $totalTransaksi = self::totalTransaksiAnggota($anggota->id, $tanggalAwal, $tanggalAkhir);

            $jasaModal = $totalSimpananGlobal > 0
                ? (int) round($perhitungan->jumlah_jasa_modal * ($totalSimpanan / $totalSimpananGlobal))
                : 0;

            $jasaAnggota = $totalTransaksiGlobal > 0
                ? (int) round($perhitungan->jumlah_jasa_anggota * ($totalTransaksi / $totalTransaksiGlobal))
                : 0;

            $totalShu = $jasaModal + $jasaAnggota;

            if ($totalShu === 0 && $totalSimpanan === 0) continue;

            ShuDistribusi::create([
                'shu_perhitungan_id' => $perhitungan->id,
                'anggota_id'         => $anggota->id,
                'total_simpanan'     => $totalSimpanan,
                'total_transaksi'    => $totalTransaksi,
                'jasa_modal'         => $jasaModal,
                'jasa_anggota'       => $jasaAnggota,
                'total_shu'          => $totalShu,
                'metode_distribusi'  => 'simpanan_sukarela',
                'status'             => 'belum_dibagikan',
            ]);
        }
    }

    private static function totalTransaksiAnggota(int $anggotaId, string $awal, string $akhir): int
    {
        $marginPinjaman = (int) PinjamanPembayaran::whereHas('pinjaman', fn ($q) => $q->where('anggota_id', $anggotaId))
            ->whereBetween('tanggal', [$awal, $akhir])
            ->sum('alokasi_margin');
        $belanjaToko = (int) TokoPenjualan::where('anggota_id', $anggotaId)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->sum('total');
        return $marginPinjaman + (int) round($belanjaToko * 0.05); // bobot 5% dari total belanja
    }

    private static function totalTransaksiGlobal(string $awal, string $akhir): int
    {
        $marginPinjaman = (int) PinjamanPembayaran::whereBetween('tanggal', [$awal, $akhir])
            ->sum('alokasi_margin');
        $belanjaToko = (int) TokoPenjualan::whereBetween('tanggal', [$awal, $akhir])->sum('total');
        return $marginPinjaman + (int) round($belanjaToko * 0.05);
    }
}
