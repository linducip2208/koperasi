<?php

namespace Database\Seeders;

use App\Support\Tenant\CurrentTenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NumberingSettingSeeder extends Seeder
{
    public function run(): void
    {
        CurrentTenant::set(1);

        $items = [
            ['anggota',         'AGT-',  '{prefix}{ymd}{seq:5}'],
            ['jurnal',          'JU-',   '{prefix}{ymd}-{seq:5}'],
            ['simpanan_rek',    '',      '{prefix}{ym}{seq:6}'],
            ['simpanan_trx',    'STR-',  '{prefix}{ymd}-{seq:5}'],
            ['pinjaman',        'PJM-',  '{prefix}{ymd}-{seq:5}'],
            ['pinjaman_bayar',  'PB-',   '{prefix}{ymd}-{seq:5}'],
            ['kas_trx',         'KS-',   '{prefix}{ymd}-{seq:5}'],
            ['toko_jual',       'INV-',  '{prefix}{ymd}-{seq:5}'],
            ['toko_beli',       'PO-',   '{prefix}{ymd}-{seq:5}'],
            ['tagihan',         'TG-',   '{prefix}{ymd}-{seq:5}'],
        ];

        foreach ($items as [$kode, $prefix, $format]) {
            DB::table('numbering_setting')->updateOrInsert(
                ['tenant_id' => 1, 'kode' => $kode],
                [
                    'prefix'      => $prefix,
                    'format'      => $format,
                    'panjang_seq' => 5,
                    'next_number' => 1,
                    'updated_at'  => now(),
                    'created_at'  => now(),
                ]
            );
        }
    }
}
