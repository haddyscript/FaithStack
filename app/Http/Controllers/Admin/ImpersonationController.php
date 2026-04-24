<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImpersonationController extends Controller
{
    /**
     * Consume the one-time token and log the super admin in as the tenant user.
     * This route has NO auth middleware — the user isn't logged in yet.
     */
    public function enter(string $token): RedirectResponse
    {
        $record = DB::table('impersonation_tokens')
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            abort(403, 'This impersonation link is invalid or has already been used.');
        }

        // Consume the token immediately (one-time use)
        DB::table('impersonation_tokens')->where('token', $token)->delete();

        $user = User::findOrFail($record->user_id);

        Auth::login($user);

        session([
            'impersonating'   => true,
            'impersonator_id' => $record->impersonator_id,
            'tenant_name'     => $record->tenant_name,
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
