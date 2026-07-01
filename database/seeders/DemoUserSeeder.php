<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['role' => 'manajer',     'email' => 'manager@koperasi.local', 'name' => 'Manajer Koperasi',  'nip' => 'MGR001'],
            ['role' => 'kasir',       'email' => 'kasir@koperasi.local',   'name' => 'Kasir Koperasi',    'nip' => 'KSR001'],
            ['role' => 'ao',          'email' => 'ao@koperasi.local',      'name' => 'Account Officer',   'nip' => 'AOF001'],
            ['role' => 'kolektor',    'email' => 'kolektor@koperasi.local','name' => 'Kolektor Lapangan', 'nip' => 'KLT001'],
            ['role' => 'pengawas',    'email' => 'pengawas@koperasi.local','name' => 'Pengawas Koperasi', 'nip' => 'PWS001'],
            ['role' => 'akuntan',     'email' => 'akuntan@koperasi.local', 'name' => 'Akuntan Koperasi',  'nip' => 'AKT001'],
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'tenant_id' => 1,
                    'name'      => $u['name'],
                    'nip'       => $u['nip'],
                    'password'  => Hash::make('admin123'),
                    'aktif'     => true,
                ]
            );
            $user->assignRole($u['role']);
        }
    }
}
