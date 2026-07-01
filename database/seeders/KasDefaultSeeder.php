<?php

namespace Database\Seeders;

use App\Models\Coa;
use App\Models\Kas;
use App\Support\Tenant\CurrentTenant;
use Illuminate\Database\Seeder;

class KasDefaultSeeder extends Seeder
{
    public function run(): void
    {
        CurrentTenant::set(1);

        $coaKasBesar = Coa::where('kode', '1.1.1.01')->first();
        $coaKasKecil = Coa::where('kode', '1.1.1.02')->first();

        if ($coaKasBesar) {
            Kas::firstOrCreate(
                ['kode' => 'KAS-BESAR'],
                [
                    'nama'   => 'Kas Besar',
                    'tipe'   => 'kas',
                    'coa_id' => $coaKasBesar->id,
                    'aktif'  => true,
                ]
            );
        }

        if ($coaKasKecil) {
            Kas::firstOrCreate(
                ['kode' => 'KAS-KECIL'],
                [
                    'nama'   => 'Kas Kecil',
                    'tipe'   => 'kas',
                    'coa_id' => $coaKasKecil->id,
                    'aktif'  => true,
                ]
            );
        }
    }
}
