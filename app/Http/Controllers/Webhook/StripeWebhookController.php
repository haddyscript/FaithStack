<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Mail\PaymentFailed;
use App\Mail\SubscriptionCancelled;
use App\Models\Plan;
use App\Models\Tenant;
use App\Services\StripeService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            'checkout.session.completed'   => $this->onCheckoutCompleted($event->data->object, $subscription),
            'invoice.paid'                 => $this->onInvoicePaid($event->data->object, $subscription),
            'invoice.payment_failed'       => $this->onInvoicePaymentFailed($event->data->object),
            'customer.subscription.deleted'=> $this->onSubscriptionDeleted($event->data->object),
            default                        => null,
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

    private function onInvoicePaymentFailed(object $invoice): void
    {
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

        Log::warning('Stripe invoice payment failed', ['tenant_id' => $tenant->id]);

        try {
            Mail::to($tenant->email)->send(new PaymentFailed($tenant));
        } catch (\Throwable $e) {
            Log::warning('PaymentFailed email could not be sent', [
                'tenant_id' => $tenant->id,
                'error'     => $e->getMessage(),
            ]);
        }
    }

    private function onSubscriptionDeleted(object $subscription): void
    {
        $tenantId = $subscription->metadata->tenant_id ?? null;

        if (! $tenantId) {
            return;
        }

        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            return;
        }

        $tenant->update(['subscription_status' => 'cancelled']);

        Log::info('Stripe subscription deleted — tenant marked cancelled', ['tenant_id' => $tenant->id]);

        try {
            Mail::to($tenant->email)->send(new SubscriptionCancelled($tenant));
        } catch (\Throwable $e) {
            Log::warning('SubscriptionCancelled email could not be sent', [
                'tenant_id' => $tenant->id,
                'error'     => $e->getMessage(),
            ]);
        }
    }
}
