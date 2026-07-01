<?php

namespace App\Http\Middleware;

use App\Support\Tenant\CurrentTenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantFromUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->tenant_id) {
            CurrentTenant::set((int) auth()->user()->tenant_id);
        }

        return $next($request);
    }
}
