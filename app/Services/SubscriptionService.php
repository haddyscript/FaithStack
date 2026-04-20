<?php

namespace App\Services;

use App\Mail\SubscriptionConfirmed;
use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionService
{
    /**
     * Activate (or renew) a tenant's subscription after a confirmed payment.
     */
    public function activate(Tenant $tenant, Plan $plan, string $provider, ?string $externalId = null): void
    {
        $tenant->update([
            'plan_id'              => $plan->id,
            'subscription_status'  => 'active',
            'subscription_ends_at' => now()->addMonth(),
        ]);

        Log::info('Subscription activated', [
            'tenant_id'   => $tenant->id,
            'plan'        => $plan->slug,
            'provider'    => $provider,
            'external_id' => $externalId,
        ]);

        try {
            Mail::to($tenant->email)->send(new SubscriptionConfirmed($tenant, $plan));
        } catch (\Throwable $e) {
            Log::warning('Subscription confirmation email failed', [
                'tenant_id' => $tenant->id,
                'error'     => $e->getMessage(),
            ]);
        }
    }

    /**
     * Extend an active subscription by one billing cycle (used on recurring invoice.paid).
     */
    public function renew(Tenant $tenant): void
    {
        $tenant->update([
            'subscription_status'  => 'active',
            'subscription_ends_at' => now()->addMonth(),
        ]);

        Log::info('Subscription renewed', ['tenant_id' => $tenant->id]);
    }
}
