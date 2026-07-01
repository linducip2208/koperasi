<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Tenant;
use App\Support\Tenant\CurrentTenant;
use Illuminate\Database\Seeder;

class DefaultTenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['id' => 1],
            [
                'nama'           => 'Koperasi Default',
                'operation_mode' => 'dual',
                'mata_uang'      => 'IDR',
                'tahun_buku'     => (int) date('Y'),
                'status'         => 'aktif',
                'plan'           => 'enterprise',
                'alamat'         => 'Jl. Contoh No. 1, Jakarta',
                'telp'           => '021-1234567',
                'email'          => 'admin@koperasi.local',
            ]
        );

        CurrentTenant::set($tenant->id);

        Cabang::firstOrCreate(
            ['kode' => 'KP01'],
            [
                'tenant_id' => $tenant->id,
                'nama'      => 'Kantor Pusat',
                'tipe'      => 'kantor_pusat',
                'aktif'     => true,
            ]
        );
    }
}
