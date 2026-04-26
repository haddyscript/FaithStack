<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeTenant;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
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

    public function __construct(private readonly StripeService $stripe) {}

    // ─── Subdomain availability check ────────────────────────────────────────

    public function checkSubdomain(Request $request): JsonResponse
    {
        $subdomain = strtolower(trim($request->query('subdomain', '')));

        if (strlen($subdomain) < 2) {
            return response()->json(['available' => false, 'message' => 'Too short']);
        }

        if (in_array($subdomain, self::RESERVED_SUBDOMAINS)) {
            return response()->json(['available' => false, 'message' => 'Reserved name']);
        }

        if (!preg_match('/^[a-z0-9][a-z0-9\-]*[a-z0-9]$/', $subdomain)) {
            return response()->json(['available' => false, 'message' => 'Invalid format']);
        }

        $taken = Tenant::where('subdomain', $subdomain)->exists();

        return response()->json([
            'available' => !$taken,
            'message'   => $taken ? 'Already taken' : 'Available',
        ]);
    }

    // ─── Registration form ────────────────────────────────────────────────────

    public function show(Request $request): View
    {
        $slug = $request->query('plan', 'free-trial');

        $plan = Plan::active()->where('slug', $slug)->first()
            ?? Plan::active()->orderBy('price_monthly')->first();

        $allPlans = Plan::public()->get();

        $stripeKey = config('services.stripe.key');

        return view('register', compact('plan', 'allPlans', 'stripeKey'));
    }

    // ─── Stripe SetupIntent (AJAX) ────────────────────────────────────────────

    /**
     * GET /register/setup-intent
     * Returns a Stripe SetupIntent client_secret for the frontend to confirm card details.
     * No auth required — the SetupIntent has no customer attached yet.
     */
    public function setupIntent(): JsonResponse
    {
        try {
            $intent = $this->stripe->createSetupIntent();
            return response()->json(['client_secret' => $intent->client_secret]);
        } catch (\Throwable $e) {
            Log::error('Could not create SetupIntent during registration', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Could not initialise payment form. Please try again.'], 500);
        }
    }

    // ─── AJAX step-1 validation (prevents full-page reload for account errors) ──

    /**
     * POST /register/validate-account
     * Validates step-1 fields (uniqueness checks) via AJAX before the user
     * advances to step 2, so a server error never clears their password.
     */
    public function validateAccount(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'org_name'  => ['required', 'string', 'min:2', 'max:80'],
            'subdomain' => [
                'required', 'string', 'min:2', 'max:63',
                'regex:/^[a-z0-9][a-z0-9\-]*[a-z0-9]$/',
                Rule::unique('tenants', 'subdomain'),
                Rule::notIn(self::RESERVED_SUBDOMAINS),
            ],
            'email'    => ['required', 'email', 'max:191', Rule::unique('tenants', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'subdomain.regex'    => 'Subdomain may only contain lowercase letters, numbers, and hyphens.',
            'subdomain.not_in'   => 'That subdomain is reserved. Please choose another.',
            'subdomain.unique'   => 'That subdomain is already taken.',
            'email.unique'       => 'An account with that email already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['ok' => true]);
    }

    // ─── Registration submit ──────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Step 1 — Account info
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

            // Step 2 — Billing address
            'cardholder_name' => ['required', 'string', 'max:100'],
            'billing_line1'   => ['required', 'string', 'max:191'],
            'billing_line2'   => ['nullable', 'string', 'max:191'],
            'billing_city'    => ['required', 'string', 'max:100'],
            'billing_state'   => ['nullable', 'string', 'max:100'],
            'billing_zip'     => ['required', 'string', 'max:20'],
            'billing_country' => ['required', 'string', 'size:2'],

            // Step 3 — Card (Stripe payment method ID confirmed by Stripe.js)
            'payment_method_id' => ['required', 'string', 'starts_with:pm_'],
        ], [
            'subdomain.regex'          => 'Subdomain may only contain lowercase letters, numbers, and hyphens.',
            'subdomain.not_in'         => 'That subdomain is reserved. Please choose another.',
            'subdomain.unique'         => 'That subdomain is already taken.',
            'email.unique'             => 'An account with that email already exists.',
            'payment_method_id.required' => 'Please complete the card details.',
            'payment_method_id.starts_with' => 'Invalid payment method. Please re-enter your card.',
        ]);

        $plan = Plan::where('slug', $validated['plan_slug'])->firstOrFail();

        // ── 1. Stripe: create customer + attach payment method + optional subscription ──

        try {
            $customer = $this->stripe->createCustomer(
                email: $validated['email'],
                name:  $validated['cardholder_name'],
                address: [
                    'line1'       => $validated['billing_line1'],
                    'line2'       => $validated['billing_line2'] ?? null,
                    'city'        => $validated['billing_city'],
                    'state'       => $validated['billing_state'] ?? null,
                    'postal_code' => $validated['billing_zip'],
                    'country'     => $validated['billing_country'],
                ],
            );

            $this->stripe->attachPaymentMethod($customer->id, $validated['payment_method_id']);

            // Create a subscription if the plan has a Stripe price.
            // Trial plans: card is saved now, charged only when trial ends.
            if ($plan->stripe_price_id) {
                $this->stripe->createSubscription(
                    customerId:      $customer->id,
                    priceId:         $plan->stripe_price_id,
                    trialDays:       $plan->effectiveTrialDays(),
                    paymentMethodId: $validated['payment_method_id'],
                );
            }

        } catch (\Throwable $e) {
            Log::error('Stripe setup failed during registration', [
                'email' => $validated['email'],
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['payment_method_id' => 'Card setup failed: ' . $e->getMessage()]);
        }

        // ── 2. Database: create Tenant + admin User ───────────────────────────

        $tenant = DB::transaction(function () use ($validated, $plan, $customer): Tenant {
            $trialDays   = $plan->effectiveTrialDays();
            $trialEndsAt = $trialDays > 0 ? now()->addDays($trialDays) : null;

            $tenant = Tenant::create([
                'name'                => $validated['org_name'],
                'subdomain'           => $validated['subdomain'],
                'email'               => $validated['email'],
                'plan_id'             => $plan->id,
                'stripe_customer_id'  => $customer->id,
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

        // ── 3. Welcome email ──────────────────────────────────────────────────

        try {
            Mail::to($tenant->email)->send(new WelcomeTenant($tenant, $plan));
        } catch (\Throwable $e) {
            Log::warning('Welcome email failed after registration', [
                'tenant_id' => $tenant->id,
                'error'     => $e->getMessage(),
            ]);
        }

        // ── 4. Redirect to tenant admin login ─────────────────────────────────

        $baseDomain = config('app.base_domain', 'faithstack.test');
        $scheme     = $request->isSecure() ? 'https' : 'http';
        $tenantMode = config('app.tenant_mode', 'subdomain');

        $loginUrl = $tenantMode === 'path'
            ? "{$scheme}://{$baseDomain}/{$tenant->subdomain}/admin/login"
            : "{$scheme}://{$tenant->subdomain}.{$baseDomain}/admin/login";

        return redirect($loginUrl)
            ->with('success', "Welcome to FaithStack! Your {$plan->name} account is ready.");
    }
}
