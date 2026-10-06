<?php

namespace App\Domain\Simpanan;

use App\Domain\Akuntansi\JurnalService;
use App\Domain\Numbering\NumberingService;
use App\Models\Coa;
use App\Models\Kas;
use App\Models\Simpanan;
use App\Models\SimpananTransaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SimpananService
{
    public static function setor(Simpanan $simpanan, int $jumlah, int $kasId, ?Carbon $tanggal = null, string $metode = 'cash', ?string $keterangan = null): SimpananTransaksi
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah setoran harus > 0');
        }

        $tanggal ??= now();

        return DB::transaction(function () use ($simpanan, $jumlah, $kasId, $tanggal, $metode, $keterangan) {
            // Row lock: cegah double-setor concurrent pada rekening yang sama.
            $simpanan = Simpanan::whereKey($simpanan->id)->lockForUpdate()->firstOrFail();
            $saldoSebelum = $simpanan->saldo;
            $saldoSesudah = $saldoSebelum + $jumlah;

            $trx = SimpananTransaksi::create([
                'tenant_id'     => $simpanan->tenant_id,
                'simpanan_id'   => $simpanan->id,
                'nomor'         => NumberingService::next('simpanan_trx', 'STR-', '{prefix}{ymd}-{seq:5}'),
                'tanggal'       => $tanggal,
                'jenis'         => 'setor',
                'jumlah'        => $jumlah,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
                'kas_id'        => $kasId,
                'metode_bayar'  => $metode,
                'keterangan'    => $keterangan ?? 'Setoran',
                'user_id'       => auth()->id(),
            ]);

            $simpanan->update(['saldo' => $saldoSesudah]);

            $kas = Kas::findOrFail($kasId);
            $coaSimp = $simpanan->produk->coa_simpanan_id
                ? Coa::find($simpanan->produk->coa_simpanan_id)
                : Coa::where('kode', '2.2.1.01')->first(); // default Simpanan Anggota

            $jurnal = JurnalService::create(
                "Setoran simpanan {$simpanan->nomor_rekening}",
                [
                    ['coa_id' => $kas->coa_id,    'debit' => $jumlah, 'kredit' => 0, 'keterangan' => 'Penerimaan setoran'],
                    ['coa_id' => $coaSimp->id,    'debit' => 0, 'kredit' => $jumlah, 'keterangan' => 'Simpanan anggota'],
                ],
                ['referensi_type' => SimpananTransaksi::class, 'referensi_id' => $trx->id, 'tanggal' => $tanggal->toDateString()]
            );

            $trx->update(['jurnal_id' => $jurnal->id]);

            return $trx;
        });
    }

    public static function tarik(Simpanan $simpanan, int $jumlah, int $kasId, ?Carbon $tanggal = null, string $metode = 'cash', ?string $keterangan = null): SimpananTransaksi
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah tarikan harus > 0');
        }
        if (! $simpanan->produk->boleh_tarik) {
            throw new InvalidArgumentException('Produk simpanan ini tidak dapat ditarik.');
        }

        $tanggal ??= now();

        return DB::transaction(function () use ($simpanan, $jumlah, $kasId, $tanggal, $metode, $keterangan) {
            // Row lock + cek saldo DI DALAM transaksi (anti race double-withdrawal).
            $simpanan = Simpanan::whereKey($simpanan->id)->lockForUpdate()->firstOrFail();
            if ($simpanan->saldoTersedia() < $jumlah) {
                throw new InvalidArgumentException('Saldo tersedia tidak cukup. Tersedia: Rp ' . number_format($simpanan->saldoTersedia(), 0, ',', '.'));
            }
            $saldoSebelum = $simpanan->saldo;
            $saldoSesudah = $saldoSebelum - $jumlah;

            $trx = SimpananTransaksi::create([
                'tenant_id'     => $simpanan->tenant_id,
                'simpanan_id'   => $simpanan->id,
                'nomor'         => NumberingService::next('simpanan_trx', 'STR-', '{prefix}{ymd}-{seq:5}'),
                'tanggal'       => $tanggal,
                'jenis'         => 'tarik',
                'jumlah'        => $jumlah,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
                'kas_id'        => $kasId,
                'metode_bayar'  => $metode,
                'keterangan'    => $keterangan ?? 'Penarikan',
                'user_id'       => auth()->id(),
            ]);

            $simpanan->update(['saldo' => $saldoSesudah]);

            $kas = Kas::findOrFail($kasId);
            $coaSimp = $simpanan->produk->coa_simpanan_id
                ? Coa::find($simpanan->produk->coa_simpanan_id)
                : Coa::where('kode', '2.2.1.01')->first();

            $jurnal = JurnalService::create(
                "Penarikan simpanan {$simpanan->nomor_rekening}",
                [
                    ['coa_id' => $coaSimp->id, 'debit' => $jumlah, 'kredit' => 0, 'keterangan' => 'Penarikan anggota'],
                    ['coa_id' => $kas->coa_id, 'debit' => 0, 'kredit' => $jumlah, 'keterangan' => 'Pengeluaran kas'],
                ],
                ['referensi_type' => SimpananTransaksi::class, 'referensi_id' => $trx->id, 'tanggal' => $tanggal->toDateString()]
            );

            $trx->update(['jurnal_id' => $jurnal->id]);

            return $trx;
        });
    }

    public static function bukaRekening(int $anggotaId, int $produkId, int $setoranAwal = 0, ?int $kasId = null): Simpanan    {
        $produk = \App\Models\ProdukSimpanan::findOrFail($produkId);

        return DB::transaction(function () use ($anggotaId, $produk, $setoranAwal, $kasId) {
            $simpanan = Simpanan::create([
                'anggota_id'     => $anggotaId,
                'produk_id'      => $produk->id,
                'nomor_rekening' => NumberingService::next('simpanan_rek', $produk->kode . '-', '{prefix}{ym}{seq:6}'),
                'saldo'          => 0,
                'tanggal_buka'   => now()->toDateString(),
                'status'         => 'aktif',
            ]);

            if ($setoranAwal > 0 && $kasId) {
                self::setor($simpanan, $setoranAwal, $kasId, null, 'cash', 'Setoran awal pembukaan rekening');
            }

            return $simpanan->refresh();
        });
    }

    /**
     * Mutasi antar rekening simpanan (satu transaksi atomic).
     * Mencatat 2 baris transaksi (keluar + masuk) + 1 jurnal antar-COA simpanan.
     */
    public static function transfer(Simpanan $asal, Simpanan $tujuan, int $jumlah, ?Carbon $tanggal = null, ?string $keterangan = null): array
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah transfer harus > 0');
        }
        if ($asal->id === $tujuan->id) {
            throw new InvalidArgumentException('Rekening asal dan tujuan tidak boleh sama.');
        }
        if ($asal->tenant_id !== $tujuan->tenant_id) {
            throw new InvalidArgumentException('Transfer antar koperasi tidak diperbolehkan.');
        }
        if (! $asal->produk->boleh_tarik) {
            throw new InvalidArgumentException('Produk simpanan asal tidak dapat ditarik.');
        }

        $tanggal ??= now();

        return DB::transaction(function () use ($asal, $tujuan, $jumlah, $tanggal, $keterangan) {
            // Lock berurutan by id — cegah deadlock concurrent.
            $ids = [$asal->id, $tujuan->id];
            sort($ids);
            $locked = Simpanan::whereIn('id', $ids)->lockForUpdate()->get()->keyBy('id');
            $asal = $locked[$asal->id];
            $tujuan = $locked[$tujuan->id];

            if ($asal->saldoTersedia() < $jumlah) {
                throw new InvalidArgumentException('Saldo tersedia rekening asal tidak cukup.');
            }

            $ket = $keterangan ?? "Mutasi {$asal->nomor_rekening} → {$tujuan->nomor_rekening}";

            $trxKeluar = SimpananTransaksi::create([
                'tenant_id' => $asal->tenant_id,
                'simpanan_id' => $asal->id,
                'nomor' => NumberingService::next('simpanan_trx', 'STR-', '{prefix}{ymd}-{seq:5}'),
                'tanggal' => $tanggal,
                'jenis' => 'mutasi_keluar',
                'jumlah' => $jumlah,
                'saldo_sebelum' => $asal->saldo,
                'saldo_sesudah' => $asal->saldo - $jumlah,
                'metode_bayar' => 'internal',
                'keterangan' => $ket,
                'user_id' => auth()->id(),
            ]);

            $trxMasuk = SimpananTransaksi::create([
                'tenant_id' => $tujuan->tenant_id,
                'simpanan_id' => $tujuan->id,
                'nomor' => NumberingService::next('simpanan_trx', 'STR-', '{prefix}{ymd}-{seq:5}'),
                'tanggal' => $tanggal,
                'jenis' => 'mutasi_masuk',
                'jumlah' => $jumlah,
                'saldo_sebelum' => $tujuan->saldo,
                'saldo_sesudah' => $tujuan->saldo + $jumlah,
                'metode_bayar' => 'internal',
                'keterangan' => $ket,
                'user_id' => auth()->id(),
            ]);

            $asal->update(['saldo' => $asal->saldo - $jumlah]);
            $tujuan->update(['saldo' => $tujuan->saldo + $jumlah]);

            $coaAsal = $asal->produk->coa_simpanan_id
                ? Coa::find($asal->produk->coa_simpanan_id)
                : Coa::where('kode', '2.2.1.01')->first();
            $coaTujuan = $tujuan->produk->coa_simpanan_id
                ? Coa::find($tujuan->produk->coa_simpanan_id)
                : Coa::where('kode', '2.2.1.01')->first();

            $jurnal = JurnalService::create(
                "Mutasi simpanan {$asal->nomor_rekening} → {$tujuan->nomor_rekening}",
                [
                    ['coa_id' => $coaAsal->id, 'debit' => $jumlah, 'kredit' => 0, 'keterangan' => 'Mutasi keluar'],
                    ['coa_id' => $coaTujuan->id, 'debit' => 0, 'kredit' => $jumlah, 'keterangan' => 'Mutasi masuk'],
                ],
                ['referensi_type' => SimpananTransaksi::class, 'referensi_id' => $trxKeluar->id, 'tanggal' => $tanggal->toDateString()]
            );

            $trxKeluar->update(['jurnal_id' => $jurnal->id]);
            $trxMasuk->update(['jurnal_id' => $jurnal->id]);

            return [$trxKeluar, $trxMasuk, $jurnal];
        });
    }
}
