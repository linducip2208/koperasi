<?php

namespace App\Console\Commands;

use App\Models\Pinjaman;
use App\Models\PinjamanJadwal;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateKolektabilitas extends Command
{
    protected $signature = 'koperasi:update-kolektabilitas';
    protected $description = 'Update kolektabilitas pinjaman berdasarkan tunggakan hari';

    public function handle(): int
    {
        $pinjamen = Pinjaman::whereIn('status', ['aktif', 'macet'])->get();

        foreach ($pinjamen as $p) {
            $jadwalTunggak = PinjamanJadwal::where('pinjaman_id', $p->id)
                ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])
                ->where('tanggal_jatuh_tempo', '<', now())
                ->orderBy('tanggal_jatuh_tempo')
                ->first();

            if (! $jadwalTunggak) {
                $p->update(['kolektabilitas' => 'lancar', 'tunggakan_hari' => 0]);
                continue;
            }

            $hari = (int) Carbon::parse($jadwalTunggak->tanggal_jatuh_tempo)->diffInDays(now());

            $kol = match (true) {
                $hari <= 0   => 'lancar',
                $hari <= 30  => 'lancar',
                $hari <= 90  => 'dpk',
                $hari <= 180 => 'kurang_lancar',
                $hari <= 270 => 'diragukan',
                default      => 'macet',
            };

            $p->update([
                'kolektabilitas' => $kol,
                'tunggakan_hari' => $hari,
                'status'         => $kol === 'macet' ? 'macet' : 'aktif',
            ]);
        }

        $this->info('Updated kolektabilitas untuk ' . $pinjamen->count() . ' pinjaman.');
        return self::SUCCESS;
    }
}
