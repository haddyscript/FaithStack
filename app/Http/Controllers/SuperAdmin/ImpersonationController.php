<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ImpersonationController extends Controller
{
    public function start(Tenant $tenant): RedirectResponse
    {
        $admin = User::where('tenant_id', $tenant->id)
            ->where('role', 'admin')
            ->firstOrFail();

        $token = Str::random(64);

        // One-time token — consumed on entry, expires in 5 minutes
        Cache::put("impersonate:{$token}", [
            'user_id'         => $admin->id,
            'impersonator_id' => auth()->id(),
            'tenant_name'     => $tenant->name,
        ], now()->addMinutes(5));

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
