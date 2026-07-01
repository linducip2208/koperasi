<?php

namespace App\Console\Commands;

use App\Domain\Notifikasi\NotifikasiService;
use App\Models\PinjamanJadwal;
use Illuminate\Console\Command;

class SendReminderAngsuran extends Command
{
    protected $signature = 'koperasi:reminder-angsuran {--days=3 : H- berapa hari sebelum jatuh tempo}';
    protected $description = 'Kirim reminder angsuran via WhatsApp/Email';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $tanggalTarget = now()->addDays($days)->toDateString();

        $jadwal = PinjamanJadwal::with('pinjaman.anggota')
            ->where('status', 'belum_jatuh_tempo')
            ->whereDate('tanggal_jatuh_tempo', $tanggalTarget)
            ->get();

        $sent = 0;
        foreach ($jadwal as $j) {
            if (! $j->pinjaman?->anggota) continue;

            NotifikasiService::reminderAngsuran($j->pinjaman->anggota, [
                'nomor_akad'  => $j->pinjaman->nomor_akad,
                'jumlah'      => $j->total_angsuran - $j->terbayar_pokok - $j->terbayar_margin,
                'jatuh_tempo' => $j->tanggal_jatuh_tempo->format('d M Y'),
            ]);
            $sent++;
        }

        $this->info("Reminder dikirim untuk {$sent} angsuran (H-{$days}).");
        return self::SUCCESS;
    }
}
