<?php

namespace App\Domain\Ppob;

use App\Models\PpobProduk;
use App\Models\PpobTransaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Pembelian PPOB atomic + idempotent.
 * Lifecycle: pending → processing → sukses | gagal (→ refund manual bila perlu).
 */
class PpobService
{
    public static function provider(): PpobProviderInterface
    {
        $name = \App\Models\Setting::get('ppob_provider', 'manual', 'ppob');
        return match ($name) {
            default => new ManualPpobProvider(),
        };
    }

    public static function beli(int $anggotaId, int $tenantId, int $produkId, string $noTujuan, ?string $idempotencyKey = null): PpobTransaksi
    {
        $produk = PpobProduk::where('id', $produkId)->where('aktif', true)->firstOrFail();
        $noTujuan = trim($noTujuan);
        if ($noTujuan === '' || strlen($noTujuan) > 30) {
            throw new InvalidArgumentException('Nomor tujuan tidak valid.');
        }

        // Idempotency: kunci dari klien (double-submit aman) atau generate unik.
        $key = $idempotencyKey ?: 'PPOB-'.now()->format('YmdHis').'-'.strtoupper(Str::random(6));

        return DB::transaction(function () use ($anggotaId, $tenantId, $produk, $noTujuan, $key) {
            $existing = PpobTransaksi::where('nomor', $key)->first();
            if ($existing) return $existing; // retry aman — tidak dobel

            $trx = PpobTransaksi::create([
                'tenant_id' => $tenantId,
                'anggota_id' => $anggotaId,
                'ppob_produk_id' => $produk->id,
                'nomor' => $key,
                'no_tujuan' => $noTujuan,
                'harga' => $produk->harga_jual,
                'harga_beli' => $produk->harga_beli,
                'laba' => $produk->harga_jual - $produk->harga_beli,
                'status' => 'processing',
            ]);

            try {
                $hasil = static::provider()->beli($produk, $noTujuan);
            } catch (\Throwable $e) {
                $trx->update(['status' => 'gagal', 'keterangan' => 'Supplier error: '.class_basename($e)]);
                return $trx;
            }

            $trx->update([
                'status' => ($hasil['sukses'] ?? false) ? 'sukses' : 'gagal',
                'sn' => $hasil['sn'] ?? null,
                'keterangan' => $hasil['keterangan'] ?? null,
            ]);

            return $trx->refresh();
        });
    }
}
