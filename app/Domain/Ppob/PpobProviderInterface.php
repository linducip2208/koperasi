<?php

namespace App\Domain\Ppob;

/** Kontrak supplier PPOB — implementasi baru (Digiflazz dsb) tinggal tambah class. */
interface PpobProviderInterface
{
    public function name(): string;

    /**
     * Eksekusi pembelian. Return ['sukses' => bool, 'sn' => ?string, 'keterangan' => ?string].
     */
    public function beli(\App\Models\PpobProduk $produk, string $noTujuan): array;
}
