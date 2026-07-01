<?php

namespace Database\Seeders;

use App\Models\Coa;
use App\Models\ProdukPinjaman;
use App\Support\Tenant\CurrentTenant;
use Illuminate\Database\Seeder;

class ProdukPinjamanSeeder extends Seeder
{
    public function run(): void
    {
        CurrentTenant::set(1);

        $coaPokokKonv  = Coa::where('kode', '1.1.3.01')->first();
        $coaPokokMurab = Coa::where('kode', '1.1.3.02')->first();
        $coaPokokMudha = Coa::where('kode', '1.1.3.03')->first();
        $coaPokokMusya = Coa::where('kode', '1.1.3.04')->first();
        $coaPokokIjarah = Coa::where('kode', '1.1.3.05')->first();
        $coaBunga      = Coa::where('kode', '4.1.1.01')->first();
        $coaMargin     = Coa::where('kode', '4.1.1.02')->first();
        $coaDenda      = Coa::where('kode', '4.2.1.03')->first();

        $items = [
            // Konvensional
            ['PJ-KONV-FLAT',     'Pinjaman Bunga Flat',    'konvensional', 'flat',    1.5, $coaPokokKonv,  $coaBunga],
            ['PJ-KONV-EFK',      'Pinjaman Bunga Menurun', 'konvensional', 'efektif', 2.0, $coaPokokKonv,  $coaBunga],
            ['PJ-KONV-ANUITAS',  'Pinjaman Anuitas',       'konvensional', 'anuitas', 1.8, $coaPokokKonv,  $coaBunga],
            // Syariah
            ['PJ-SYAR-MURAB',    'Pembiayaan Murabahah',   'murabahah',    'murabahah',  12.0, $coaPokokMurab,  $coaMargin],
            ['PJ-SYAR-MUDHA',    'Pembiayaan Mudharabah',  'mudharabah',   'mudharabah', 0,    $coaPokokMudha,  $coaMargin],
            ['PJ-SYAR-MUSYA',    'Pembiayaan Musyarakah',  'musyarakah',   'musyarakah', 0,    $coaPokokMusya,  $coaMargin],
            ['PJ-SYAR-IJARAH',   'Pembiayaan Ijarah',      'ijarah',       'ijarah',     0,    $coaPokokIjarah, $coaMargin],
            ['PJ-SYAR-IJARAHMB', 'Pembiayaan Ijarah MB',   'ijarah_mb',    'ijarah_mb',  0,    $coaPokokIjarah, $coaMargin],
            ['PJ-SYAR-QARDH',    'Pinjaman Kebajikan',     'qardh',        'qardh',      0,    $coaPokokKonv,   null],
        ];

        foreach ($items as [$kode, $nama, $akad, $metode, $rate, $coaPokok, $coaBungaProduk]) {
            $isSyariah = ! in_array($akad, ['konvensional']);
            $isJualBeli = $akad === 'murabahah';

            ProdukPinjaman::firstOrCreate(
                ['kode' => $kode],
                [
                    'nama'                  => $nama,
                    'jenis'                 => 'multiguna',
                    'akad_type'             => $akad,
                    'metode_perhitungan'    => $metode,
                    'plafon_minimum'        => 500_000,
                    'plafon_maksimum'       => 100_000_000,
                    'tenor_minimum'         => 1,
                    'tenor_maksimum'        => 60,
                    'bunga_persen'          => $isSyariah ? 0 : $rate,
                    'margin_persen'         => $isJualBeli ? $rate : 0,
                    'nisbah_anggota'        => in_array($akad, ['mudharabah', 'musyarakah']) ? 70 : 0,
                    'nisbah_koperasi'       => in_array($akad, ['mudharabah', 'musyarakah']) ? 30 : 0,
                    'biaya_admin_persen'    => 0,
                    'biaya_admin_flat'      => 25_000,
                    'biaya_provisi_persen'  => 1.0,
                    'biaya_asuransi_persen' => 0.5,
                    'biaya_materai'         => 10_000,
                    'denda_persen_per_hari' => 0.05,
                    'butuh_jaminan'         => true,
                    'butuh_simpanan_blokir' => false,
                    'rasio_simpanan_blokir' => 0,
                    'coa_pokok_id'          => $coaPokok?->id,
                    'coa_bunga_id'          => $coaBungaProduk?->id,
                    'coa_denda_id'          => $coaDenda?->id,
                    'aktif'                 => true,
                ]
            );
        }
    }
}
