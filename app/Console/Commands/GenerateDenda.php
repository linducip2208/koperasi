<?php

namespace App\Console\Commands;

use App\Domain\Pinjaman\PinjamanService;
use App\Models\Pinjaman;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerateDenda extends Command
{
    protected $signature = 'koperasi:generate-denda';
    protected $description = 'Hitung & generate denda pinjaman terlambat (alias konsisten ke PinjamanService)';

    public function handle(): int
    {
        // Single source of truth: PinjamanService::hitungDendaHarian
        // (dulu logika inline duplikat HitungDendaCommand — sekarang delegasi agar konsisten)
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

        $this->info("Updated denda untuk {$count} pinjaman.");
        return self::SUCCESS;
    }
}
