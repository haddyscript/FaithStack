<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\PayPalService;
use App\Services\StripeService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly StripeService       $stripe,
        private readonly PayPalService       $paypal,
        private readonly SubscriptionService $subscription,
    ) {}

    /** POST /admin/billing/upgrade — initiate Stripe or PayPal checkout. */
    public function upgrade(Request $request): RedirectResponse
    {
        $request->validate([
            'plan_slug' => ['required', 'string', 'exists:plans,slug'],
            'provider'  => ['required', 'in:stripe,paypal'],
        ]);

        $tenant = app('tenant');
        $plan   = Plan::where('slug', $request->plan_slug)->where('is_active', true)->firstOrFail();

        try {
            if ($request->provider === 'stripe') {
                $session = $this->stripe->createCheckoutSession($tenant, $plan);
                return redirect($session->url);
            }

            // PayPal
            $order      = $this->paypal->createOrder($tenant, $plan);
            $approveUrl = collect($order['links'] ?? [])
                ->firstWhere('rel', 'approve')['href'] ?? null;

            if (! $approveUrl) {
                throw new \RuntimeException('PayPal did not return an approval URL.');
            }

            return redirect($approveUrl);

        } catch (\Throwable $e) {
            return back()->with('error', 'Could not initiate checkout: ' . $e->getMessage());
        }
    }

    /** GET /admin/billing/stripe/success — Stripe redirects here after payment. */
    public function stripeSuccess(Request $request): RedirectResponse
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect()->route('admin.billing')->with('error', 'Missing Stripe session ID.');
        }

        try {
            $session = $this->stripe->retrieveSession($sessionId);

            if ($session->payment_status !== 'paid') {
                return redirect()->route('admin.billing')
                    ->with('error', 'Payment has not completed. Please try again.');
            }

            $tenant = app('tenant');
            $plan   = Plan::findOrFail($session->metadata->plan_id);

            $this->subscription->activate($tenant, $plan, 'stripe', $sessionId);

            return redirect()->route('admin.billing')
                ->with('success', "🎉 Welcome to {$plan->name}! Your account is now fully activated.");

        } catch (\Throwable $e) {
            return redirect()->route('admin.billing')
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /** GET /admin/billing/paypal/capture — PayPal redirects here after approval. */
    public function paypalCapture(Request $request): RedirectResponse
    {
        $orderId = $request->query('token');

        if (! $orderId) {
            return redirect()->route('admin.billing')->with('error', 'Missing PayPal order token.');
        }

        try {
            $capture = $this->paypal->captureOrder($orderId);

            if (($capture['status'] ?? '') !== 'COMPLETED') {
                return redirect()->route('admin.billing')
                    ->with('error', 'PayPal payment did not complete. Please try again.');
            }

            // Parse tenant & plan from custom_id (format: t{id}_p{id})
            $customId = $capture['purchase_units'][0]['payments']['captures'][0]['custom_id'] ?? null;

            if (! $customId || ! preg_match('/^t(\d+)_p(\d+)$/', $customId, $m)) {
                throw new \RuntimeException('Could not identify order. Contact support.');
            }

            $tenant = app('tenant');
            $plan   = Plan::findOrFail($m[2]);

            $this->subscription->activate($tenant, $plan, 'paypal', $orderId);

            return redirect()->route('admin.billing')
                ->with('success', "🎉 Welcome to {$plan->name}! Your account is now fully activated.");

        } catch (\Throwable $e) {
            return redirect()->route('admin.billing')
                ->with('error', 'PayPal capture failed: ' . $e->getMessage());
        }
    }

    /** GET /admin/billing/cancel — user cancelled out of payment. */
    public function cancel(): RedirectResponse
    {
        return redirect()->route('admin.billing')
            ->with('error', 'Checkout was cancelled. No charges were made.');
    }

    /** POST /admin/billing/stripe/intent — create a PaymentIntent, return client_secret. */
    public function createIntent(Request $request): JsonResponse
    {
        $request->validate(['plan_slug' => ['required', 'string', 'exists:plans,slug']]);

        $tenant = app('tenant');
        $plan   = Plan::where('slug', $request->plan_slug)->where('is_active', true)->firstOrFail();

        try {
            $intent = $this->stripe->createPaymentIntent($tenant, $plan);
            return response()->json(['client_secret' => $intent->client_secret]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /** POST /admin/billing/stripe/confirm — activate after client-side confirmation. */
    public function confirmPayment(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => ['required', 'string'],
            'plan_slug'         => ['required', 'string', 'exists:plans,slug'],
        ]);

        $tenant = app('tenant');
        $plan   = Plan::where('slug', $request->plan_slug)->firstOrFail();

        try {
            $intent = $this->stripe->retrievePaymentIntent($request->payment_intent_id);

            if ($intent->status !== 'succeeded') {
                return response()->json(['error' => 'Payment has not succeeded yet.'], 422);
            }

            $this->subscription->activate($tenant, $plan, 'stripe', $intent->id);

            return response()->json([
                'success'  => true,
                'message'  => "🎉 Welcome to {$plan->name}! Your account is now active.",
                'redirect' => route('admin.billing'),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
