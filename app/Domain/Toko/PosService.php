<?php

namespace App\Domain\Toko;

use App\Domain\Akuntansi\JurnalService;
use App\Domain\Numbering\NumberingService;
use App\Models\Coa;
use App\Models\Kas;
use App\Models\Simpanan;
use App\Models\SimpananTransaksi;
use App\Models\TokoBarang;
use App\Models\TokoPenjualan;
use App\Support\Tenant\CurrentTenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PosService
{
    public const COA_PENDAPATAN_TOKO = '4.1.2';
    public const COA_HPP_TOKO        = '5.1.11';
    public const COA_PERSEDIAAN      = '1.1.4';
    public const COA_PIUTANG_DAGANG  = '1.1.3.06';
    public const COA_SIMPANAN_DEFAULT = '2.2.1.02';

    /**
     * Proses penjualan POS: kurangi stok, potong simpanan/piutang, dan buat jurnal otomatis.
     * Idempotent — transaksi yang sudah punya jurnal_id atau berstatus batal akan dilewati.
     */
    public static function proses(TokoPenjualan $penjualan): TokoPenjualan
    {
        if ($penjualan->status === 'batal' || $penjualan->jurnal_id) {
            return $penjualan;
        }

        $penjualan->loadMissing('detail.barang');

        if ($penjualan->detail->isEmpty()) {
            return $penjualan;
        }

        return DB::transaction(function () use ($penjualan) {
            $tanggal = Carbon::parse($penjualan->tanggal);
            $total   = (int) $penjualan->total;

            $totalHpp = (int) $penjualan->detail->sum(
                fn ($d) => (int) ($d->hpp ?? 0) * (int) $d->jumlah
            );

            self::kurangiStok($penjualan);

            $coaDebit = self::coaDebit($penjualan);

            $details = [
                ['coa_id' => $coaDebit->id, 'debit' => $total, 'kredit' => 0, 'keterangan' => "Penjualan toko {$penjualan->nomor}"],
                ['coa_id' => self::coa(self::COA_PENDAPATAN_TOKO)->id, 'debit' => 0, 'kredit' => $total, 'keterangan' => 'Pendapatan penjualan toko'],
            ];

            if ($totalHpp > 0) {
                $details[] = ['coa_id' => self::coa(self::COA_HPP_TOKO)->id, 'debit' => $totalHpp, 'kredit' => 0, 'keterangan' => 'Harga pokok penjualan'];
                $details[] = ['coa_id' => self::coa(self::COA_PERSEDIAAN)->id, 'debit' => 0, 'kredit' => $totalHpp, 'keterangan' => 'Pengurangan persediaan'];
            }

            $jurnal = JurnalService::create(
                "Penjualan toko {$penjualan->nomor}",
                $details,
                [
                    'referensi_type' => TokoPenjualan::class,
                    'referensi_id'   => $penjualan->id,
                    'tanggal'        => $tanggal->toDateString(),
                    'cabang_id'      => $penjualan->cabang_id,
                ]
            );

            $penjualan->update(['jurnal_id' => $jurnal->id]);

            return $penjualan->refresh();
        });
    }

    private static function kurangiStok(TokoPenjualan $penjualan): void
    {
        foreach ($penjualan->detail as $d) {
            $barang = $d->barang ?: TokoBarang::find($d->barang_id);
            if (! $barang || $barang->is_jasa) {
                continue;
            }

            $barang->decrement('stok', (int) $d->jumlah);
        }
    }

    private static function coaDebit(TokoPenjualan $penjualan): Coa
    {
        return match ($penjualan->metode_bayar) {
            'simpanan' => self::potongSimpanan($penjualan),
            'utang'    => self::coa(self::COA_PIUTANG_DAGANG),
            default    => self::coaKas($penjualan),
        };
    }

    private static function coaKas(TokoPenjualan $penjualan): Coa
    {
        if (! $penjualan->kas_id) {
            throw new InvalidArgumentException('Kas penerimaan wajib dipilih untuk metode pembayaran ini.');
        }

        $kas = Kas::findOrFail($penjualan->kas_id);

        if (! $kas->coa) {
            throw new InvalidArgumentException("Kas '{$kas->nama}' belum dipetakan ke COA.");
        }

        return $kas->coa;
    }

    private static function potongSimpanan(TokoPenjualan $penjualan): Coa
    {
        if (! $penjualan->simpanan_id) {
            throw new InvalidArgumentException('Rekening simpanan wajib dipilih untuk pembayaran potong simpanan.');
        }

        $simpanan = Simpanan::with('produk')->findOrFail($penjualan->simpanan_id);
        $total    = (int) $penjualan->total;

        if ($simpanan->saldoTersedia() < $total) {
            throw new InvalidArgumentException(
                'Saldo simpanan tidak cukup. Tersedia: Rp ' . number_format($simpanan->saldoTersedia(), 0, ',', '.')
            );
        }

        $saldoSebelum = $simpanan->saldo;
        $saldoSesudah = $saldoSebelum - $total;

        SimpananTransaksi::create([
            'tenant_id'     => $simpanan->tenant_id ?? CurrentTenant::id(),
            'simpanan_id'   => $simpanan->id,
            'nomor'         => NumberingService::next('simpanan_trx', 'STR-', '{prefix}{ymd}-{seq:5}'),
            'tanggal'       => $penjualan->tanggal,
            'jenis'         => 'tarik',
            'jumlah'        => $total,
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $saldoSesudah,
            'kas_id'        => $penjualan->kas_id,
            'metode_bayar'  => 'potong_belanja',
            'keterangan'    => "Potong simpanan untuk penjualan toko {$penjualan->nomor}",
            'user_id'       => auth()->id(),
        ]);

        $simpanan->update(['saldo' => $saldoSesudah]);

        $coaSimp = $simpanan->produk?->coa_simpanan_id
            ? Coa::find($simpanan->produk->coa_simpanan_id)
            : null;

        return $coaSimp ?: self::coa(self::COA_SIMPANAN_DEFAULT);
    }

    private static function coa(string $kode): Coa
    {
        $coa = Coa::where('kode', $kode)->first();
        if (! $coa) {
            throw new InvalidArgumentException("COA dengan kode {$kode} tidak ditemukan. Jalankan ChartOfAccountsSeeder.");
        }
        return $coa;
    }
}
