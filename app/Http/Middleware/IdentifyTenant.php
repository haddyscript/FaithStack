<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost(); // e.g. church1.faithstack.app

        // Extract subdomain: strip "faithstack.app" or whatever base domain
        $baseDomain = config('app.base_domain', 'faithstack.app');
        $subdomain  = str_replace('.' . $baseDomain, '', $host);

        // If the host equals the base domain (no subdomain), abort
        if ($subdomain === $host || empty($subdomain)) {
            abort(404, 'Tenant not found.');
        }

        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if (! $tenant) {
            abort(404, 'Tenant not found.');
        }

        // Bind tenant to the container so it's available globally
        app()->instance('tenant', $tenant);
        $request->merge(['tenant' => $tenant]);

        // Share tenant and nav with all views
        view()->share('tenant', $tenant);
        view()->share('navItems', $tenant->navigation);
        view()->share('themeSlug', $tenant->getThemeSlug());
        view()->share('themeConfig', $tenant->theme?->config ?? []);

        return $next($request);
    }
}
