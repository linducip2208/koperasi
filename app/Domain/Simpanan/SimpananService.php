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
        if ($simpanan->saldoTersedia() < $jumlah) {
            throw new InvalidArgumentException("Saldo tersedia tidak cukup. Tersedia: Rp " . number_format($simpanan->saldoTersedia(), 0, ',', '.'));
        }
        if (! $simpanan->produk->boleh_tarik) {
            throw new InvalidArgumentException('Produk simpanan ini tidak dapat ditarik.');
        }

        $tanggal ??= now();

        return DB::transaction(function () use ($simpanan, $jumlah, $kasId, $tanggal, $metode, $keterangan) {
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

    public static function bukaRekening(int $anggotaId, int $produkId, int $setoranAwal = 0, ?int $kasId = null): Simpanan
    {
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
}
