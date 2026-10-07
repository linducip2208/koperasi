<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Locale dari settings koperasi (general.locale), fallback config. */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $locale = \App\Models\Setting::get('locale', config('app.locale', 'id'), 'general');
            if (in_array($locale, ['id', 'en'], true)) {
                app()->setLocale($locale);
                \Carbon\Carbon::setLocale($locale === 'id' ? 'id' : 'en');
            }
        } catch (\Throwable) {
        }
        return $next($request);
    }
}
