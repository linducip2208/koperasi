<?php

namespace App\Support\Tenant;

class CurrentTenant
{
    private static ?int $id = null;

    public static function id(): int
    {
        if (self::$id !== null) {
            return self::$id;
        }

        if (auth()->check() && auth()->user()->tenant_id) {
            return self::$id = (int) auth()->user()->tenant_id;
        }

        return self::$id = 1;
    }

    public static function set(int $id): void
    {
        self::$id = $id;
    }

    public static function reset(): void
    {
        self::$id = null;
    }
}
