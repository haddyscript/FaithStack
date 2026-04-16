<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Page;
use App\Models\Tenant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_tenants'  => Tenant::count(),
            'active'         => Tenant::where('subscription_status', 'active')->count(),
            'trial'          => Tenant::where('subscription_status', 'trial')->count(),
            'expired'        => Tenant::whereNotIn('subscription_status', ['active', 'trial'])->count(),
            'total_pages'    => Page::count(),
            'total_donations'=> Donation::count(),
            'total_revenue'  => Donation::where('status', 'completed')->sum('amount'),
        ];

        $recentTenants = Tenant::with('theme')
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recentTenants'));
    }
}
