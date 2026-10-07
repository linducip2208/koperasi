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
            $existing = ShuPerhitungan::where('tahun', $tahun)->first();
            if ($existing && in_array($existing->status, ['disetujui', 'distribusi'], true)) {
                throw new \InvalidArgumentException("SHU tahun {$tahun} sudah {$existing->status} — snapshot dikunci, tidak boleh dihitung ulang.");
            }

            $snapshot = [
                'shu_total' => $shuTotal,
                'persen' => $persen,
                'dihitung_oleh' => auth()->id(),
                'dihitung_at' => now()->toDateTimeString(),
                'version' => config('product.version'),
            ];

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
                    'meta'                   => array_merge($existing->meta ?? [], ['snapshot_terakhir' => $snapshot]),
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

    /**
     * Bagikan SHU ke simpanan sukarela anggota + 1 jurnal ringkas per 500 baris.
     * Idempotent: baris 'dibayar' dilewati → aman di-retry. Hanya bila status disetujui.
     *
     * @return array{dibayar:int, total:int}
     */
    public static function bagikan(int $tahun): array
    {
        $perhitungan = ShuPerhitungan::where('tahun', $tahun)->firstOrFail();
        if ($perhitungan->status !== 'disetujui') {
            throw new \InvalidArgumentException("SHU tahun {$tahun} harus disetujui dulu (status: {$perhitungan->status}).");
        }

        $coaShu = \App\Models\Coa::where('tipe', 'ekuitas')->where('is_postable', true)
            ->where(function ($q) {
                $q->where('nama', 'like', '%SHU%')->orWhere('nama', 'like', '%laba%ditahan%')->orWhere('nama', 'like', '%laba ditahan%');
            })->first()
            ?? \App\Models\Coa::where('tipe', 'ekuitas')->where('is_postable', true)->where('is_aktif', true)->first();
        $coaSimpanan = \App\Models\Coa::where('kode', '2.2.1.01')->first()
            ?? \App\Models\Coa::where('tipe', 'kewajiban')->where('is_postable', true)->where('is_aktif', true)->first();
        if (! $coaShu || ! $coaSimpanan) {
            throw new \InvalidArgumentException('COA ekuitas/simpanan untuk distribusi SHU tidak ditemukan.');
        }

        $dibayar = 0;
        $antre = ShuDistribusi::where('shu_perhitungan_id', $perhitungan->id)
            ->whereIn('status', ['belum_dibagikan', 'pending'])
            ->where('total_shu', '>', 0)
            ->orderBy('id')->get();

        foreach ($antre->chunk(500) as $chunk) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($chunk, $perhitungan, $coaShu, $coaSimpanan, &$dibayar) {
                $totalChunk = 0;
                foreach ($chunk as $row) {
                    $simpanan = \App\Models\Simpanan::where('anggota_id', $row->anggota_id)
                        ->where('status', 'aktif')
                        ->whereHas('produk', fn ($q) => $q->where('jenis', 'sukarela'))
                        ->lockForUpdate()->first();

                    if (! $simpanan) {
                        $produk = \App\Models\ProdukSimpanan::where('jenis', 'sukarela')->where('aktif', true)->first();
                        if (! $produk) continue; // tanpa produk sukarela → lewati, admin atasi manual
                        $simpanan = \App\Domain\Simpanan\SimpananService::bukaRekening($row->anggota_id, $produk->id);
                        $simpanan = \App\Models\Simpanan::whereKey($simpanan->id)->lockForUpdate()->first();
                    }

                    $sebelum = $simpanan->saldo;
                    $trx = \App\Models\SimpananTransaksi::create([
                        'tenant_id' => $simpanan->tenant_id,
                        'simpanan_id' => $simpanan->id,
                        'nomor' => \App\Domain\Numbering\NumberingService::next('simpanan_trx', 'STR-', '{prefix}{ymd}-{seq:5}'),
                        'tanggal' => now()->toDateString(),
                        'jenis' => 'shu',
                        'jumlah' => (int) $row->total_shu,
                        'saldo_sebelum' => $sebelum,
                        'saldo_sesudah' => $sebelum + (int) $row->total_shu,
                        'metode_bayar' => 'internal',
                        'keterangan' => "SHU tahun {$perhitungan->tahun}",
                        'user_id' => auth()->id(),
                    ]);
                    $simpanan->update(['saldo' => $sebelum + (int) $row->total_shu]);
                    $totalChunk += (int) $row->total_shu;

                    $row->update(['status' => 'dibayar', 'distributed_at' => now()]);
                    $dibayar++;
                }

                if ($totalChunk > 0) {
                    \App\Domain\Akuntansi\JurnalService::create("Distribusi SHU {$perhitungan->tahun} (" . $chunk->count() . ' anggota)', [
                        ['coa_id' => $coaShu->id, 'debit' => $totalChunk, 'kredit' => 0, 'keterangan' => 'Distribusi SHU'],
                        ['coa_id' => $coaSimpanan->id, 'debit' => 0, 'kredit' => $totalChunk, 'keterangan' => 'SHU masuk simpanan'],
                    ], ['tipe' => 'otomatis', 'referensi_type' => ShuPerhitungan::class, 'referensi_id' => $perhitungan->id]);
                }
            });
        }

        if (! ShuDistribusi::where('shu_perhitungan_id', $perhitungan->id)->whereIn('status', ['belum_dibagikan', 'pending'])->exists()) {
            $perhitungan->update(['status' => 'distribusi', 'distributed_at' => now()]);
        }

        activity('shu')->causedBy(auth()->user())->performedOn($perhitungan)
            ->withProperties(['tahun' => $tahun, 'dibayar' => $dibayar])->log('shu_dibagikan');

        return ['dibayar' => $dibayar, 'total' => $antre->count()];
    }
}
