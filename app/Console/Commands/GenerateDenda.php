<?php

namespace App\Console\Commands;

use App\Models\PinjamanJadwal;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerateDenda extends Command
{
    protected $signature = 'koperasi:generate-denda';
    protected $description = 'Hitung & generate denda pinjaman terlambat';

    public function handle(): int
    {
        $jadwalTelat = PinjamanJadwal::with('pinjaman.produk')
            ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])
            ->where('tanggal_jatuh_tempo', '<', now())
            ->get();

        $count = 0;
        foreach ($jadwalTelat as $j) {
            $produk = $j->pinjaman->produk;
            if (! $produk) continue;

            $hari = (int) Carbon::parse($j->tanggal_jatuh_tempo)->diffInDays(now());
            if ($hari <= 0) continue;

            $sisaAngsuran = max(0, $j->total_angsuran - $j->terbayar_pokok - $j->terbayar_margin);
            $dendaPersen  = (float) $produk->denda_persen_per_hari;
            $dendaFlat    = (int) $produk->denda_flat_per_hari;

            $dendaBaru = (int) round($sisaAngsuran * ($dendaPersen / 100) * $hari) + ($dendaFlat * $hari);

            if ($dendaBaru > $j->denda) {
                $j->update([
                    'denda'  => $dendaBaru,
                    'status' => 'telat',
                ]);
                $count++;
            }
        }

        $this->info("Updated denda untuk {$count} jadwal angsuran.");
        return self::SUCCESS;
    }
}
