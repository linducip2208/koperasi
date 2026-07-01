<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DefaultTenantSeeder::class,
            RoleSeeder::class,
            AdminUserSeeder::class,
            ChartOfAccountsSeeder::class,
            KasDefaultSeeder::class,
            ProdukSimpananSeeder::class,
            ProdukPinjamanSeeder::class,
            NumberingSettingSeeder::class,
            BlogSeeder::class,
            PaymentProviderSeeder::class,
            PpobSeeder::class,
        ]);
    }
}
