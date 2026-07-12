<?php

namespace App\Filament\Concerns;

trait HasRoleAccess
{
    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        if ($user->hasRole('super-admin')) {
            return true;
        }

        $module = static::$permissionModule ?? null;
        if (! $module) {
            return true;
        }

        return $user->can("{$module}.view");
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
}
