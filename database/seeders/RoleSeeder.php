<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'anggota', 'simpanan', 'pinjaman', 'kas', 'bank', 'jurnal', 'coa',
            'shu', 'pos', 'produsen', 'jasa', 'tagihan', 'asuransi',
            'karyawan', 'gaji', 'asset', 'rat', 'laporan', 'setting',
            'user', 'role', 'tenant', 'license',
        ];

        $actions = ['view', 'create', 'update', 'delete', 'approve', 'export', 'print'];

        foreach ($modules as $m) {
            foreach ($actions as $a) {
                Permission::firstOrCreate(['name' => "{$m}.{$a}", 'guard_name' => 'web']);
            }
        }

        $roles = [
            'super-admin' => null, // semua permission
            'admin'       => null, // semua kecuali tenant/license
            'manajer'     => ['*.view', '*.approve', '*.export', '*.print', 'pinjaman.update'],
            'kasir'       => ['anggota.view', 'simpanan.*', 'kas.*', 'pinjaman.view', 'pinjaman.update', 'pos.*', 'tagihan.*'],
            'ao'          => ['anggota.view', 'pinjaman.*', 'simpanan.view', 'jurnal.view'],
            'kolektor'    => ['anggota.view', 'pinjaman.view', 'pinjaman.update'],
            'pengawas'    => ['*.view', 'laporan.*'],
            'akuntan'     => ['jurnal.*', 'coa.*', 'laporan.*', 'setting.view'],
            'anggota'     => [], // guard berbeda nanti
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            if ($perms === null) {
                $excluded = $roleName === 'super-admin' ? [] : ['tenant.*', 'license.*'];
                $allPermissions = Permission::where('guard_name', 'web')->get();
                $role->syncPermissions($allPermissions->reject(fn ($p) => self::matchAny($p->name, $excluded)));
            } else {
                $matched = collect();
                foreach ($perms as $pattern) {
                    $matched = $matched->merge(self::expandPattern($pattern));
                }
                $role->syncPermissions($matched->unique()->all());
            }
        }
    }

    private static function expandPattern(string $pattern): array
    {
        if (! str_contains($pattern, '*')) {
            return Permission::where('name', $pattern)->where('guard_name', 'web')->pluck('id')->all();
        }
        $regex = '/^' . str_replace('\*', '.*', preg_quote($pattern, '/')) . '$/';
        return Permission::where('guard_name', 'web')->get()->filter(fn ($p) => preg_match($regex, $p->name))->pluck('id')->all();
    }

    private static function matchAny(string $name, array $patterns): bool
    {
        foreach ($patterns as $p) {
            if (str_contains($p, '*')) {
                $regex = '/^' . str_replace('\*', '.*', preg_quote($p, '/')) . '$/';
                if (preg_match($regex, $name)) return true;
            } elseif ($name === $p) {
                return true;
            }
        }
        return false;
    }
}
