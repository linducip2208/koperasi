<?php

namespace App\Console\Commands;

use App\Domain\Pinjaman\PinjamanService;
use App\Models\Pinjaman;
use Illuminate\Console\Command;

class AutoDebetCommand extends Command
{
    protected $signature = 'koperasi:auto-debet';
    protected $description = 'Auto-debet simpanan anggota untuk membayar angsuran yang jatuh tempo';

    public function handle(): int
    {
        $pinjamanAktif = Pinjaman::where('status', 'aktif')
            ->whereHas('jadwal', fn ($q) => $q
                ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])
                ->where('tanggal_jatuh_tempo', '<=', now()->toDateString())
            )
            ->get();

        $sukses = 0;
        foreach ($pinjamanAktif as $pinjaman) {
            try {
                $pembayaran = PinjamanService::autoDebetAngsuran($pinjaman);
                if ($pembayaran) {
                    $this->info("Auto-debet {$pinjaman->nomor_akad}: Rp " . number_format($pembayaran->total_bayar, 0, ',', '.'));
                    $sukses++;
                }
            } catch (\Throwable $e) {
                $this->warn("Gagal auto-debet {$pinjaman->nomor_akad}: {$e->getMessage()}");
            }
        }

        $this->info("Auto-debet selesai: {$sukses} berhasil.");
        return self::SUCCESS;
    }
}
