<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ImpersonationController extends Controller
{
    /**
     * Consume the one-time token and log the super admin in as the tenant user.
     * This route has NO auth middleware — the user isn't logged in yet.
     */
    public function enter(string $token): RedirectResponse
    {
        $data = Cache::pull("impersonate:{$token}");

        if (! $data) {
            abort(403, 'This impersonation link is invalid or has already been used.');
        }

        $user = User::findOrFail($data['user_id']);

        Auth::login($user);

        session([
            'impersonating'   => true,
            'impersonator_id' => $data['impersonator_id'],
            'tenant_name'     => $data['tenant_name'],
        ]);

        return redirect()->route('admin.dashboard');
    }

    /**
     * End the impersonation session, log out, and send the super admin back home.
     */
    public function stop(): RedirectResponse
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->away($this->superAdminTenantsUrl());
    }

    private function superAdminTenantsUrl(): string
    {
        if (config('app.tenant_mode', 'subdomain') === 'path') {
            return route('superadmin.tenants.index');
        }

        $scheme = request()->isSecure() ? 'https' : 'http';
        $base   = config('app.base_domain', 'faithstack.test');

        return "{$scheme}://{$base}/superadmin/tenants";
    }
}
