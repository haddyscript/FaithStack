<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\StripeService;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct(private readonly StripeService $stripe) {}

    public function index(): View
    {
        $tenant = app('tenant')->load('plan');
        $plans  = Plan::public()->get();

        $trialDaysLeft = null;
        if ($tenant->subscription_status === 'trial' && $tenant->subscription_ends_at) {
            $trialDaysLeft = max(0, (int) now()->diffInDays($tenant->subscription_ends_at, false));
        }

        // Detect the tenant's saved payment method so the upgrade modal can
        // skip card entry and go straight to a one-click confirm step.
        $cardLast4 = null;
        $cardBrand = null;
        $hasCard   = false;

        if ($tenant->stripe_customer_id) {
            $pm = $this->stripe->getDefaultPaymentMethod($tenant->stripe_customer_id);
            if ($pm && isset($pm->card)) {
                $hasCard   = true;
                $cardLast4 = $pm->card->last4;
                $cardBrand = ucfirst($pm->card->brand);
            }
        }

        return view('admin.billing', compact(
            'tenant', 'plans', 'trialDaysLeft',
            'hasCard', 'cardLast4', 'cardBrand'
        ));
    }
}
