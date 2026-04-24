<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImpersonationController extends Controller
{
    public function start(Tenant $tenant): RedirectResponse
    {
        $admin = User::where('tenant_id', $tenant->id)
            ->where('role', 'admin')
            ->firstOrFail();

        // Clean up any stale tokens for this user before issuing a new one
        DB::table('impersonation_tokens')
            ->where('user_id', $admin->id)
            ->orWhere('expires_at', '<', now())
            ->delete();

        $token = Str::random(64);

        DB::table('impersonation_tokens')->insert([
            'token'           => $token,
            'user_id'         => $admin->id,
            'impersonator_id' => auth()->id(),
            'tenant_name'     => $tenant->name,
            'expires_at'      => now()->addMinutes(5),
        ]);

        return redirect()->away($this->tenantImpersonateUrl($tenant, $token));
    }

    private function tenantImpersonateUrl(Tenant $tenant, string $token): string
    {
        if (config('app.tenant_mode', 'subdomain') === 'path') {
            return url("/{$tenant->subdomain}/admin/impersonate/{$token}");
        }

        $scheme = request()->isSecure() ? 'https' : 'http';
        $base   = config('app.base_domain', 'faithstack.test');

        return "{$scheme}://{$tenant->subdomain}.{$base}/admin/impersonate/{$token}";
    }
}
