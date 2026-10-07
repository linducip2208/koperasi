<?php

namespace App\Domain\Asset;

use App\Domain\Akuntansi\JurnalService;
use App\Models\Asset;
use App\Models\Coa;
use App\Models\Kas;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/** Pelepasan aset (jual/rusak/hapus) dengan jurnal laba-rugi pelepasan. */
class AssetService
{
    public static function lepas(Asset $aset, int $hargaJual, int $kasId, string $status = 'dijual', ?string $tanggal = null): Asset
    {
        if ($aset->status !== 'aktif') {
            throw new InvalidArgumentException('Hanya aset aktif yang bisa dilepas.');
        }
        if ($hargaJual < 0) {
            throw new InvalidArgumentException('Harga jual tidak boleh negatif.');
        }

        $kas = Kas::findOrFail($kasId);
        $tanggal ??= now()->toDateString();
        $nilaiBuku = (int) $aset->nilai_buku;
        $selisih = $hargaJual - $nilaiBuku; // >0 laba, <0 rugi

        return DB::transaction(function () use ($aset, $hargaJual, $kas, $status, $tanggal, $nilaiBuku, $selisih) {
            $lines = [
                ['coa_id' => $kas->coa_id, 'debit' => $hargaJual, 'kredit' => 0, 'keterangan' => 'Penerimaan pelepasan aset'],
            ];
            if ((int) $aset->akumulasi_susut > 0 && $aset->coa_akumulasi_id) {
                $lines[] = ['coa_id' => $aset->coa_akumulasi_id, 'debit' => (int) $aset->akumulasi_susut, 'kredit' => 0, 'keterangan' => 'Balik akumulasi'];
            }
            $lines[] = ['coa_id' => $aset->coa_aset_id, 'debit' => 0, 'kredit' => (int) $aset->harga_perolehan, 'keterangan' => 'Hapus nilai aset'];
            if ($selisih > 0) {
                $lines[] = ['coa_id' => self::coaLain('pendapatan')->id, 'debit' => 0, 'kredit' => $selisih, 'keterangan' => 'Laba pelepasan aset'];
            } elseif ($selisih < 0) {
                $lines[] = ['coa_id' => self::coaLain('beban')->id, 'debit' => -$selisih, 'kredit' => 0, 'keterangan' => 'Rugi pelepasan aset'];
            }

            $jurnal = JurnalService::create("Pelepasan aset {$aset->kode} ({$status})", $lines, [
                'tanggal' => $tanggal, 'tipe' => 'otomatis',
                'referensi_type' => Asset::class, 'referensi_id' => $aset->id,
            ]);

            $aset->update(['status' => $status, 'tanggal_dilepas' => $tanggal]);

            activity('asset')->causedBy(auth()->user())->performedOn($aset)->withProperties([
                'nilai_buku' => $nilaiBuku, 'harga_jual' => $hargaJual, 'jurnal' => $jurnal->nomor,
            ])->log('aset_dilepas');

            return $aset->refresh();
        });
    }

    protected static function coaLain(string $tipe): Coa
    {
        return Coa::where('tipe', $tipe)->where('is_postable', true)->where('is_aktif', true)
            ->where(function ($q) {
                $q->where('nama', 'like', '%lain%')->orWhere('nama', 'like', '%rugi%')->orWhere('nama', 'like', '%laba%');
            })->first()
            ?? Coa::where('tipe', $tipe)->where('is_postable', true)->where('is_aktif', true)->firstOrFail();
    }
}
