<?php

namespace Database\Seeders;

use App\Models\Coa;
use App\Models\ProdukSimpanan;
use App\Support\Tenant\CurrentTenant;
use Illuminate\Database\Seeder;

class ProdukSimpananSeeder extends Seeder
{
    public function run(): void
    {
        CurrentTenant::set(1);

        $items = [
            ['SP-POKOK',    'Simpanan Pokok',    'pokok',     'konvensional', 50000,  0,      0,    0,    'pokok'],
            ['SP-WAJIB',    'Simpanan Wajib',    'wajib',     'konvensional', 50000,  50000,  0,    0,    'wajib'],
            ['SP-SUKARELA', 'Simpanan Sukarela', 'sukarela',  'konvensional', 10000,  0,      0,    3,    'sukarela'],
            ['SP-DEPOSITO', 'Simpanan Berjangka','berjangka', 'konvensional', 1000000,0,      0,    7,    'berjangka'],
            ['SP-WADIAH',   'Simpanan Wadiah',   'sukarela',  'wadiah',       10000,  0,      0,    0,    'wadiah'],
            ['SP-MUDHA',    'Simpanan Mudharabah','sukarela', 'mudharabah',   100000, 0,      0,    0,    'mudharabah'],
        ];

        foreach ($items as [$kode, $nama, $jenis, $akad, $minSetor, $wajib, $minSaldo, $bunga, $kategori]) {
            $coa = match ($kategori) {
                'pokok'      => Coa::where('kode', '3.1')->first(),
                'wajib'      => Coa::where('kode', '2.2.1.01')->first(),
                'sukarela'   => Coa::where('kode', '2.2.1.02')->first(),
                'berjangka'  => Coa::where('kode', '2.2.1.03')->first(),
                'wadiah'     => Coa::where('kode', '2.2.1.05')->first(),
                'mudharabah' => Coa::where('kode', '2.2.1.06')->first(),
                default      => null,
            };

            ProdukSimpanan::firstOrCreate(
                ['kode' => $kode],
                [
                    'nama'                => $nama,
                    'jenis'               => $jenis,
                    'akad_type'           => $akad,
                    'setoran_minimum'     => $minSetor,
                    'setoran_wajib'       => $wajib,
                    'saldo_minimum'       => $minSaldo,
                    'bunga_persen_tahun'  => $bunga,
                    'metode_bunga'        => 'saldo_rata2',
                    'nisbah_anggota'      => $akad === 'mudharabah' ? 70 : 0,
                    'nisbah_koperasi'     => $akad === 'mudharabah' ? 30 : 0,
                    'boleh_tarik'         => $jenis !== 'pokok',
                    'berjangka'           => $jenis === 'berjangka',
                    'tenor_bulan'         => $jenis === 'berjangka' ? 12 : null,
                    'coa_simpanan_id'     => $coa?->id,
                    'aktif'               => true,
                ]
            );
        }
    }
}
