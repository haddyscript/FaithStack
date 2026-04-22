<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Member;
use App\Models\Page;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tenant = app('tenant');

        $stats = [
            'pages'              => Page::forTenant($tenant->id)->count(),
            'published'          => Page::forTenant($tenant->id)->published()->count(),
            'donations'          => Donation::forTenant($tenant->id)->count(),
            'revenue'            => Donation::forTenant($tenant->id)->where('status', 'completed')->sum('amount'),
            'members_total'      => Member::forTenant($tenant->id)->count(),
            'members_active'     => Member::forTenant($tenant->id)->where('status', 'active')->count(),
            'members_new'        => Member::forTenant($tenant->id)->where('status', 'new_member')->count(),
            'members_visitor'    => Member::forTenant($tenant->id)->where('status', 'visitor')->count(),
            'members_inactive'   => Member::forTenant($tenant->id)->where('status', 'inactive')->count(),
            'members_this_month' => Member::forTenant($tenant->id)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        $recentMembers = Member::forTenant($tenant->id)
            ->with('groups')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('tenant', 'stats', 'recentMembers'));
    }
}
