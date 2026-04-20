<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(): View
    {
        $tenant = app('tenant')->load('plan');
        $plans  = Plan::public()->get();

        $trialDaysLeft = null;
        if ($tenant->subscription_status === 'trial' && $tenant->subscription_ends_at) {
            $trialDaysLeft = max(0, (int) now()->diffInDays($tenant->subscription_ends_at, false));
        }

        return view('admin.billing', compact('tenant', 'plans', 'trialDaysLeft'));
    }
}
