<?php

namespace App\Console\Commands;

use App\Models\Anggota;
use App\Models\Rat;
use Illuminate\Console\Command;

class RatSyncQuorum extends Command
{
    protected $signature = 'koperasi:rat-sync-quorum';
    protected $description = 'Sinkronisasi quorum RAT: isi terdaftar yg kosong + hitung ulang flag quorum';

    public function handle(): int
    {
        $aktif = (int) Anggota::where('status', 'aktif')->count();
        $n = 0;

        foreach (Rat::all() as $rat) {
            if ($rat->jumlah_anggota_terdaftar <= 0 && $aktif > 0) {
                $rat->forceFill(['jumlah_anggota_terdaftar' => $aktif])->saveQuietly();
            }
            $rat->refreshQuorum();
            $n++;
        }

        $this->info("Quorum disinkron untuk {$n} RAT (anggota aktif: {$aktif}).");
        return self::SUCCESS;
    }
}
