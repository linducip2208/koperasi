<?php

namespace Database\Seeders;

use App\Models\Rat;
use App\Models\RatVoting;
use Illuminate\Database\Seeder;

class RatVotingSeeder extends Seeder
{
    public function run(): void
    {
        $rat = Rat::whereIn('status', ['rencana', 'berlangsung'])
            ->orderByDesc('tahun_buku')->first()
            ?? Rat::orderByDesc('tahun_buku')->first();

        if (! $rat) return;

        $contoh = [
            [
                'judul' => "Persetujuan Laporan Keuangan Tahun Buku {$rat->tahun_buku}",
                'deskripsi' => 'Menyetujui neraca, laporan SHU, dan CALK yang telah diaudit pengawas.',
                'opsi' => ['Setuju', 'Tidak Setuju', 'Abstain'],
            ],
            [
                'judul' => "Pemilihan Ketua Koperasi Periode Berikutnya",
                'deskripsi' => 'Satu anggota satu suara. Hasil tertinggi ditetapkan sebagai ketua terpilih.',
                'opsi' => ['Calon 1', 'Calon 2', 'Kotak Kosong'],
            ],
        ];

        foreach ($contoh as $c) {
            RatVoting::firstOrCreate(
                ['rat_id' => $rat->id, 'judul' => $c['judul']],
                [
                    'tenant_id' => $rat->tenant_id,
                    'deskripsi' => $c['deskripsi'],
                    'opsi' => $c['opsi'],
                    'mulai' => now(),
                    'selesai' => now()->addDays(7),
                    'is_aktif' => true,
                ]
            );
        }
    }
}
