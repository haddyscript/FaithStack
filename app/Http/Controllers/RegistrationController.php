<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /** Reserved subdomains that cannot be used for tenant accounts. */
    private const RESERVED_SUBDOMAINS = [
        'www', 'mail', 'smtp', 'admin', 'superadmin', 'api', 'app',
        'static', 'cdn', 'assets', 'media', 'support', 'help', 'docs',
        'status', 'staging', 'dev', 'test', 'demo', 'billing',
    ];

    public function show(Request $request): View
    {
        $slug = $request->query('plan', 'free-trial');

        $plan = Plan::active()->where('slug', $slug)->first()
            ?? Plan::active()->orderBy('price_monthly')->first();

        $allPlans = Plan::public()->get();

        return view('register', compact('plan', 'allPlans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan_slug' => [
                'required',
                'string',
                Rule::exists('plans', 'slug')->where('is_active', true),
            ],
            'org_name'  => ['required', 'string', 'min:2', 'max:80'],
            'subdomain' => [
                'required',
                'string',
                'min:2',
                'max:63',
                'regex:/^[a-z0-9][a-z0-9\-]*[a-z0-9]$/',
                Rule::unique('tenants', 'subdomain'),
                Rule::notIn(self::RESERVED_SUBDOMAINS),
            ],
            'email'    => [
                'required',
                'email',
                'max:191',
                Rule::unique('tenants', 'email'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'subdomain.regex'    => 'Subdomain may only contain lowercase letters, numbers, and hyphens, and must not start or end with a hyphen.',
            'subdomain.not_in'   => 'That subdomain is reserved. Please choose another.',
            'subdomain.unique'   => 'That subdomain is already taken.',
            'email.unique'       => 'An account with that email already exists.',
        ]);

        $plan = Plan::where('slug', $validated['plan_slug'])->firstOrFail();

        $tenant = DB::transaction(function () use ($validated, $plan): Tenant {
            $trialDays = $plan->effectiveTrialDays();
            $trialEndsAt = $trialDays > 0 ? now()->addDays($trialDays) : null;

            $tenant = Tenant::create([
                'name'                => $validated['org_name'],
                'subdomain'           => $validated['subdomain'],
                'email'               => $validated['email'],
                'plan_id'             => $plan->id,
                'subscription_status' => 'trial',
                'subscription_ends_at'=> $trialEndsAt,
            ]);

            User::create([
                'tenant_id' => $tenant->id,
                'name'      => $validated['org_name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'role'      => 'admin',
            ]);

            return $tenant;
        });

        $baseDomain = config('app.base_domain', 'faithstack.test');
        $scheme     = $request->isSecure() ? 'https' : 'http';

        return redirect("{$scheme}://{$tenant->subdomain}.{$baseDomain}/admin/login")
            ->with('success', "Welcome to FaithStack! Your {$plan->name} account is ready.");
    }
}
