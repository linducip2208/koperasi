<?php

namespace App\Filament\Concerns;

use Illuminate\Contracts\Support\Htmlable;

/** Label navigasi/judul halaman custom lewat lang (id/en). Pasang `protected static ?string $navKey = '...';` */
trait HasTranslatedNav
{
    public static function getNavigationLabel(): string
    {
        if (! isset(static::$navKey)) return static::$navigationLabel ?? '';
        $t = __('nav.'.static::$navKey);
        return $t === 'nav.'.static::$navKey ? static::$navigationLabel ?? static::$navKey : $t;
    }

    public function getTitle(): string | Htmlable
    {
        if (! isset(static::$navKey)) return static::$title ?? '';
        $t = __('nav.'.static::$navKey);
        return $t === 'nav.'.static::$navKey ? (static::$title ?? static::$navKey) : $t;
    }
}
