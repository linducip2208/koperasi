<?php

namespace App\Support;

use App\Models\Tenant;
use App\Support\Tenant\CurrentTenant;

/**
 * CooperativeContext — identitas koperasi instalasi tunggal.
 *
 * Standalone: 1 installation = 1 cooperative = 1 database = 1 license = 1 brand.
 * JANGAN gunakan ini untuk multi-tenant SaaS. Seluruh aplikasi membaca profil
 * koperasi lewat abstraction ini, bukan Tenant::find(1) yang tersebar.
 */
class CooperativeContext
{
    public static function current(): ?Tenant
    {
        try {
            return Tenant::current();
        } catch (\Throwable) {
            return null;
        }
    }

    public static function id(): int
    {
        return CurrentTenant::id();
    }

    public static function name(): string
    {
        return self::current()?->displayName() ?? config('product.name');
    }

    public static function operationMode(): string
    {
        return self::current()?->operation_mode ?? 'konvensional';
    }

    public static function isSyariah(): bool
    {
        return in_array(self::operationMode(), ['syariah', 'dual'], true);
    }

    public static function currency(): string
    {
        return self::current()?->mata_uang ?? 'IDR';
    }

    public static function timezone(): string
    {
        return self::current()?->timezone ?? config('app.timezone', 'Asia/Jakarta');
    }

    public static function fiscalYear(): int
    {
        return (int) (self::current()?->tahun_buku ?? now()->year);
    }
}
