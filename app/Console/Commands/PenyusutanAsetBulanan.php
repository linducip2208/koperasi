<?php

namespace App\Console\Commands;

use App\Domain\Akuntansi\JurnalService;
use App\Models\Asset;
use Illuminate\Console\Command;

class PenyusutanAsetBulanan extends Command
{
    protected $signature = 'koperasi:penyusutan-aset';
    protected $description = 'Hitung penyusutan aset bulanan + auto-jurnal';

    public function handle(): int
    {
        $asets = Asset::where('status', 'aktif')->get();
        $count = 0;

        foreach ($asets as $aset) {
            if ($aset->nilai_buku <= $aset->nilai_residu) continue;

            $susut = $aset->susutBulanan();
            $aset->akumulasi_susut += $susut;
            $aset->nilai_buku       = max($aset->nilai_residu, $aset->harga_perolehan - $aset->akumulasi_susut);
            $aset->save();

            if ($aset->coa_susut_id && $aset->coa_akumulasi_id) {
                JurnalService::create(
                    "Penyusutan bulanan aset {$aset->kode}",
                    [
                        ['coa_id' => $aset->coa_susut_id,     'debit' => $susut, 'kredit' => 0, 'keterangan' => 'Beban penyusutan'],
                        ['coa_id' => $aset->coa_akumulasi_id, 'debit' => 0, 'kredit' => $susut, 'keterangan' => 'Akumulasi penyusutan'],
                    ],
                    ['referensi_type' => Asset::class, 'referensi_id' => $aset->id]
                );
            }
            $count++;
        }

        $this->info("Penyusutan dihitung untuk {$count} aset.");
        return self::SUCCESS;
    }
}
