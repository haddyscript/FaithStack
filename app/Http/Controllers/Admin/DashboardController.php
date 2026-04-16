<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Page;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tenant = app('tenant');

        $stats = [
            'pages'     => Page::forTenant($tenant->id)->count(),
            'published' => Page::forTenant($tenant->id)->published()->count(),
            'donations' => Donation::forTenant($tenant->id)->count(),
            'revenue'   => Donation::forTenant($tenant->id)->where('status', 'completed')->sum('amount'),
        ];

        return view('admin.dashboard', compact('tenant', 'stats'));
    }
}
