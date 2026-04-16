<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(): View
    {
        $tenant    = app('tenant');
        $donations = Donation::forTenant($tenant->id)->latest()->paginate(20);

        return view('admin.donations.index', compact('donations', 'tenant'));
    }
}
