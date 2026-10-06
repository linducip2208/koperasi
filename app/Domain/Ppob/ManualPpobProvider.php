<?php

namespace App\Domain\Ppob;

/**
 * Provider manual: transaksi dicatat sukses-pending verifikasi admin,
 * SN diisi saat admin memproses ke supplier fisik. Cocok untuk koperasi
 * yang melayani PPOB via agen/mitra manual sebelum integrasi API supplier.
 */
class ManualPpobProvider implements PpobProviderInterface
{
    public function name(): string { return 'manual'; }

    public function beli(\App\Models\PpobProduk $produk, string $noTujuan): array
    {
        return [
            'sukses' => true,
            'sn' => 'MNL-'.strtoupper(substr(md5($produk->id.$noTujuan.microtime(true)), 0, 12)),
            'keterangan' => 'Diproses via jalur manual — verifikasi admin bila diperlukan.',
        ];
    }
}
