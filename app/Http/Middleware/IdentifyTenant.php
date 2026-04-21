<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = config('app.tenant_mode', 'subdomain') === 'path'
            ? $this->resolveFromPath($request)
            : $this->resolveFromSubdomain($request);

        if (! $tenant) {
            abort(404, 'Tenant not found.');
        }

        // Bind to the container for controllers and services
        app()->instance('tenant', $tenant);
        $request->merge(['tenant' => $tenant]);

        // Share with all Blade views
        view()->share('tenant',      $tenant);
        view()->share('navItems',    $tenant->navigation);
        view()->share('themeSlug',   $tenant->getThemeSlug());
        view()->share('theme',       $tenant->theme);
        view()->share('themeConfig', $tenant->theme?->config ?? []);

        // In path mode, bake the tenant slug into every route() helper call
        // automatically so no controller or view code needs to change.
        if (config('app.tenant_mode', 'subdomain') === 'path') {
            URL::defaults(['tenant_slug' => $tenant->subdomain]);
        }

        return $next($request);
    }

    // ── Path mode: tenant slug is a route parameter /{tenant_slug}/… ────────
    private function resolveFromPath(Request $request): ?Tenant
    {
        $slug = $request->route('tenant_slug');

        if (! $slug) {
            return null;
        }

        return Tenant::where('subdomain', $slug)->first();
    }

    // ── Subdomain mode: extract from host e.g. church1.faithstack.test ──────
    private function resolveFromSubdomain(Request $request): ?Tenant
    {
        $host       = $request->getHost();
        $baseDomain = config('app.base_domain', 'faithstack.app');
        $subdomain  = str_replace('.' . $baseDomain, '', $host);

        // Host equals base domain — no subdomain present
        if ($subdomain === $host || empty($subdomain)) {
            return null;
        }

        return Tenant::where('subdomain', $subdomain)->first();
    }
}
