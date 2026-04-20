<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Tenant;
use App\Services\StripeService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(
        Request             $request,
        StripeService       $stripe,
        SubscriptionService $subscription,
    ): Response {
        $payload   = $request->getContent();
        $signature = $request->header('Stripe-Signature', '');

        try {
            $event = $stripe->constructWebhookEvent($payload, $signature);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook: invalid signature', ['error' => $e->getMessage()]);
            return response('Forbidden', 403);
        } catch (\Throwable $e) {
            Log::error('Stripe webhook: construction error', ['error' => $e->getMessage()]);
            return response('Bad Request', 400);
        }

        match ($event->type) {
            'checkout.session.completed' => $this->onCheckoutCompleted($event->data->object, $subscription),
            'invoice.paid'               => $this->onInvoicePaid($event->data->object, $subscription),
            default                      => null,
        };

        return response('OK', 200);
    }

    private function onCheckoutCompleted(object $session, SubscriptionService $subscription): void
    {
        if ($session->payment_status !== 'paid') {
            return;
        }

        $tenantId = $session->metadata->tenant_id ?? null;
        $planId   = $session->metadata->plan_id   ?? null;

        if (! $tenantId || ! $planId) {
            Log::warning('Stripe checkout.session.completed: missing metadata', [
                'session_id' => $session->id,
            ]);
            return;
        }

        $tenant = Tenant::find($tenantId);
        $plan   = Plan::find($planId);

        if (! $tenant || ! $plan) {
            Log::warning('Stripe webhook: tenant or plan not found', compact('tenantId', 'planId'));
            return;
        }

        $subscription->activate($tenant, $plan, 'stripe', $session->id);
    }

    private function onInvoicePaid(object $invoice, SubscriptionService $subscription): void
    {
        // Handles recurring monthly renewal
        $tenantId = $invoice->subscription_details->metadata->tenant_id
            ?? $invoice->metadata->tenant_id
            ?? null;

        if (! $tenantId) {
            return;
        }

        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            return;
        }

        $subscription->renew($tenant);
    }
}
