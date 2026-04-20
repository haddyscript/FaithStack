<?php

namespace App\Http\Controllers;

use App\Mail\DonationReceipt;
use App\Mail\NewDonationAlert;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function create(): View
    {
        $tenant = app('tenant');

        return view("themes.{$tenant->getThemeSlug()}.donate", compact('tenant'));
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant = app('tenant');

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:150'],
            'amount'     => ['required', 'numeric', 'min:1'],
            'frequency'  => ['required', 'in:one_time,monthly,yearly'],
            'notes'      => ['nullable', 'string', 'max:500'],
        ]);

        $donation = Donation::create([
            ...$validated,
            'tenant_id' => $tenant->id,
            'status'    => 'pending',
        ]);

        try {
            Mail::to($donation->email)->send(new DonationReceipt($donation, $tenant));
        } catch (\Throwable $e) {
            Log::warning('Donation receipt email failed', [
                'donation_id' => $donation->id,
                'error'       => $e->getMessage(),
            ]);
        }

        try {
            Mail::to($tenant->email)->send(new NewDonationAlert($donation, $tenant));
        } catch (\Throwable $e) {
            Log::warning('New donation admin alert failed', [
                'donation_id' => $donation->id,
                'error'       => $e->getMessage(),
            ]);
        }

        return redirect()->back()->with('success', 'Thank you for your donation!');
    }
}
