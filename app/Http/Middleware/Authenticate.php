<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('superadmin*')) {
            return route('superadmin.login');
        }

        try {
            return route('admin.login');
        } catch (\Throwable) {
            // In path mode, route('admin.login') requires {tenant_slug} via
            // URL::defaults — if that wasn't set yet, build the path directly.
            $slug = $request->route('tenant_slug') ?? '';
            return $slug ? "/{$slug}/admin/login" : null;
        }
    }
}
