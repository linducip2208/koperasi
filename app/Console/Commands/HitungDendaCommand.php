<?php

namespace App\Console\Commands;

use App\Domain\Pinjaman\PinjamanService;
use App\Models\Pinjaman;
use Illuminate\Console\Command;

class HitungDendaCommand extends Command
{
    protected $signature = 'koperasi:hitung-denda';
    protected $description = 'Hitung denda harian untuk semua angsuran yang terlambat';

    public function handle(): int
    {
        $pinjamanAktif = Pinjaman::whereIn('status', ['aktif', 'macet'])->get();
        $count = 0;

        foreach ($pinjamanAktif as $pinjaman) {
            try {
                PinjamanService::hitungDendaHarian($pinjaman);
                $count++;
            } catch (\Throwable $e) {
                $this->warn("Gagal hitung denda {$pinjaman->nomor_akad}: {$e->getMessage()}");
            }
        }

        $this->info("Denda dihitung untuk {$count} pinjaman.");
        return self::SUCCESS;
    }
}
