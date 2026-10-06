<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Permission granular Report Center. Aman di-rerun: firstOrCreate + grant
 * tanpa sync (tidak mereset kustomisasi role production).
 */
class ReportPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            'reports.view', 'reports.export', 'reports.pdf', 'reports.excel', 'reports.csv',
            'reports.create', 'reports.update', 'reports.delete',
            'reports.schedule', 'reports.archive',
            'reports.import', 'reports.import_financial',
            'reports.ai',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        $grant = [
            'super-admin' => $perms,
            'admin' => $perms,
            'manajer' => ['reports.view', 'reports.export', 'reports.pdf', 'reports.excel', 'reports.csv', 'reports.schedule', 'reports.ai'],
            'akuntan' => ['reports.view', 'reports.export', 'reports.pdf', 'reports.excel', 'reports.csv', 'reports.archive', 'reports.schedule'],
            'pengawas' => ['reports.view', 'reports.export', 'reports.pdf', 'reports.excel', 'reports.csv'],
            'auditor' => ['reports.view', 'reports.export', 'reports.pdf', 'reports.excel', 'reports.csv'],
            'kasir' => ['reports.view'],
            'staff' => ['reports.view'],
        ];

        foreach ($grant as $roleName => $names) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if (! $role) continue;
            foreach ($names as $n) {
                if (! $role->hasPermissionTo($n)) $role->givePermissionTo($n);
            }
        }
    }
}
