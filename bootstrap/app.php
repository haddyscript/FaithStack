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
            'tenant'       => \App\Http\Middleware\IdentifyTenant::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'superadmin'   => \App\Http\Middleware\SuperAdminOnly::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Redirect unauthenticated users to the tenant admin login
        $exceptions->shouldRenderJsonWhen(fn ($request) => $request->expectsJson());

        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if (! $request->expectsJson()) {
                // Super-admin routes redirect to the super-admin login
                if (str_starts_with($request->route()?->getName() ?? '', 'superadmin.')) {
                    return redirect()->guest(route('superadmin.login'));
                }
                return redirect()->guest(route('admin.login'));
            }
        });
    })->create();
