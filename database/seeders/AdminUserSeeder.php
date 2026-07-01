<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@koperasi.local'],
            [
                'tenant_id' => 1,
                'name'      => 'Super Admin',
                'nip'       => 'ADM001',
                'password'  => Hash::make('admin123'),
                'aktif'     => true,
            ]
        );

        $admin->assignRole('super-admin');
    }
}
