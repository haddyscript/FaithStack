<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Tenant;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Event;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Webhook;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(Tenant $tenant, Plan $plan): Session
    {
        $scheme     = request()->isSecure() ? 'https' : 'http';
        $baseDomain = config('app.base_domain', 'faithstack.test');
        $sub        = $tenant->subdomain;

        return Session::create([
            'mode'                  => 'subscription',
            'payment_method_types'  => ['card'],
            'line_items'            => [[
                'price'    => $plan->stripe_price_id,
                'quantity' => 1,
            ]],
            'metadata' => [
                'tenant_id' => (string) $tenant->id,
                'plan_id'   => (string) $plan->id,
            ],
            'subscription_data' => [
                'metadata' => [
                    'tenant_id' => (string) $tenant->id,
                    'plan_id'   => (string) $plan->id,
                ],
            ],
            'customer_email' => $tenant->email,
            'success_url'    => "{$scheme}://{$sub}.{$baseDomain}/admin/billing/stripe/success?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url'     => "{$scheme}://{$sub}.{$baseDomain}/admin/billing/cancel",
        ]);
    }

    public function retrieveSession(string $sessionId): Session
    {
        return Session::retrieve(['id' => $sessionId, 'expand' => ['subscription']]);
    }

    public function createPaymentIntent(Tenant $tenant, Plan $plan): PaymentIntent
    {
        return PaymentIntent::create([
            'amount'      => (int) round((float) $plan->price_monthly * 100),
            'currency'    => 'usd',
            'description' => "FaithStack {$plan->name} Plan — Monthly",
            'metadata'    => [
                'tenant_id' => (string) $tenant->id,
                'plan_id'   => (string) $plan->id,
            ],
            'receipt_email' => $tenant->email,
        ]);
    }

    public function retrievePaymentIntent(string $id): PaymentIntent
    {
        return PaymentIntent::retrieve($id);
    }

    public function constructWebhookEvent(string $payload, string $signature): Event
    {
        return Webhook::constructEvent(
            $payload,
            $signature,
            config('services.stripe.webhook_secret')
        );
    }

    // ─── Registration payment flow ────────────────────────────────────────────

    /**
     * Create a Stripe Customer for a new registrant.
     */
    public function createCustomer(string $email, string $name, array $address = []): Customer
    {
        $data = ['email' => $email, 'name' => $name];

        if (! empty($address)) {
            $data['address'] = array_filter($address); // strip nulls
        }

        return Customer::create($data);
    }

    /**
     * Create an off-session SetupIntent so the frontend can securely collect card details.
     */
    public function createSetupIntent(?string $customerId = null): SetupIntent
    {
        $params = ['usage' => 'off_session'];

        if ($customerId) {
            $params['customer'] = $customerId;
        }

        return SetupIntent::create($params);
    }

    /**
     * Attach a PaymentMethod to a Customer and set it as their default.
     */
    public function attachPaymentMethod(string $customerId, string $paymentMethodId): void
    {
        $pm = PaymentMethod::retrieve($paymentMethodId);
        $pm->attach(['customer' => $customerId]);

        Customer::update($customerId, [
            'invoice_settings' => ['default_payment_method' => $paymentMethodId],
        ]);
    }

    /**
     * Create a Stripe Subscription for the Customer, with an optional trial period.
     * If trialDays > 0 the card is saved but not charged until the trial ends.
     */
    public function createSubscription(
        string $customerId,
        string $priceId,
        int    $trialDays,
        string $paymentMethodId,
    ): Subscription {
        $params = [
            'customer'               => $customerId,
            'items'                  => [['price' => $priceId]],
            'default_payment_method' => $paymentMethodId,
        ];

        if ($trialDays > 0) {
            $params['trial_period_days'] = $trialDays;
        }

        return Subscription::create($params);
    }

    // ─── Saved-card upgrade helpers ───────────────────────────────────────────

    /**
     * Retrieve the customer's default payment method (card brand + last4).
     * Returns null if the customer has no default PM on file.
     */
    public function getDefaultPaymentMethod(string $customerId): ?PaymentMethod
    {
        try {
            $customer = \Stripe\Customer::retrieve([
                'id'     => $customerId,
                'expand' => ['invoice_settings.default_payment_method'],
            ]);

            $pm = $customer->invoice_settings->default_payment_method ?? null;

            // May be an expanded object or just an ID string
            if (is_string($pm)) {
                return PaymentMethod::retrieve($pm);
            }

            return $pm instanceof PaymentMethod ? $pm : null;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Upgrade a customer to a new Stripe price, reusing their saved payment method.
     *
     * - If a trialing/active subscription exists → swap the price and end any trial immediately.
     * - If no subscription exists → create a new one charged today.
     */
    public function upgradeSubscription(string $customerId, string $newPriceId, string $paymentMethodId): Subscription
    {
        // Look for an existing subscription that can be swapped
        $existing = Subscription::all([
            'customer' => $customerId,
            'status'   => 'all',
            'limit'    => 10,
            'expand'   => ['data.items'],
        ]);

        $activeSub = collect($existing->data)
            ->first(fn ($s) => in_array($s->status, ['trialing', 'active', 'past_due'], true));

        if ($activeSub) {
            // Swap price — end trial immediately so the user is charged now
            return Subscription::update($activeSub->id, [
                'items' => [[
                    'id'    => $activeSub->items->data[0]->id,
                    'price' => $newPriceId,
                ]],
                'trial_end'              => 'now',
                'proration_behavior'     => 'create_prorations',
                'default_payment_method' => $paymentMethodId,
            ]);
        }

        // No existing subscription — create fresh, charge immediately
        return Subscription::create([
            'customer'               => $customerId,
            'items'                  => [['price' => $newPriceId]],
            'default_payment_method' => $paymentMethodId,
        ]);
    }
}
