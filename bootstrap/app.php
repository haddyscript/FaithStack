<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth'         => \App\Http\Middleware\Authenticate::class,
            'tenant'       => \App\Http\Middleware\IdentifyTenant::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'superadmin'   => \App\Http\Middleware\SuperAdminOnly::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'webhooks/stripe',
            'webhooks/paypal',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Redirect unauthenticated users to the tenant admin login
        $exceptions->shouldRenderJsonWhen(fn ($request) => $request->expectsJson());

        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if (! $request->expectsJson()) {
                if ($request->is('superadmin*')) {
                    return redirect()->guest(route('superadmin.login'));
                }

                // In path mode the admin.login route requires {tenant_slug}.
                // URL::defaults is set by IdentifyTenant when the tenant was
                // resolved; if it was not (unlikely for auth errors), fall back
                // to the raw path so the user at least gets a valid response.
                try {
                    return redirect()->guest(route('admin.login'));
                } catch (\Throwable) {
                    $slug = $request->route('tenant_slug') ?? '';
                    return redirect()->guest($slug ? "/{$slug}/admin/login" : '/');
                }
            }
        });
    })->create();
