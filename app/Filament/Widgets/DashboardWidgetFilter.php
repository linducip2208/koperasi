<?php

namespace App\Filament\Widgets;

trait DashboardWidgetFilter
{
    public static function canView(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return static::isVisibleToRole($user);
    }

    protected static function isVisibleToRole($user): bool
    {
        return true;
    }
}
