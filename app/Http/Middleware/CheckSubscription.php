<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\Tenant|null $tenant */
        $tenant = app('tenant');

        if (! $tenant || ! $tenant->hasAccess()) {
            return redirect()->route('subscription.expired');
        }

        return $next($request);
    }
}
