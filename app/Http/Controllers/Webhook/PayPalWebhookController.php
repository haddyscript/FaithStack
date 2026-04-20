<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Tenant;
use App\Services\PayPalService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PayPalWebhookController extends Controller
{
    public function handle(
        Request             $request,
        PayPalService       $paypal,
        SubscriptionService $subscription,
    ): Response {
        $body    = $request->getContent();
        $headers = collect($request->headers->all())
            ->map(fn ($v) => is_array($v) ? $v[0] : $v)
            ->mapWithKeys(fn ($v, $k) => [strtolower($k) => $v])
            ->all();

        // Skip signature verification in local/sandbox to ease development
        if (config('app.env') !== 'local') {
            try {
                if (! $paypal->verifyWebhook($headers, $body)) {
                    Log::warning('PayPal webhook: signature verification failed');
                    return response('Forbidden', 403);
                }
            } catch (\Throwable $e) {
                Log::error('PayPal webhook: verification error', ['error' => $e->getMessage()]);
                return response('Bad Request', 400);
            }
        }

        $event     = json_decode($body, true);
        $eventType = $event['event_type'] ?? '';

        if (in_array($eventType, ['PAYMENT.CAPTURE.COMPLETED', 'PAYMENT.SALE.COMPLETED'], true)) {
            $this->onPaymentCompleted($event['resource'] ?? [], $subscription);
        }

        return response('OK', 200);
    }

    private function onPaymentCompleted(array $resource, SubscriptionService $subscription): void
    {
        $customId = $resource['custom_id'] ?? $resource['invoice_id'] ?? null;

        if (! $customId || ! preg_match('/^t(\d+)_p(\d+)$/', $customId, $m)) {
            Log::warning('PayPal webhook: cannot parse custom_id', ['custom_id' => $customId]);
            return;
        }

        $tenant = Tenant::find($m[1]);
        $plan   = Plan::find($m[2]);

        if (! $tenant || ! $plan) {
            Log::warning('PayPal webhook: tenant or plan not found', [
                'tenant_id' => $m[1],
                'plan_id'   => $m[2],
            ]);
            return;
        }

        $subscription->activate($tenant, $plan, 'paypal', $resource['id'] ?? null);
    }
}
