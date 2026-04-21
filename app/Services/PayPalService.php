<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Support\Facades\Http;

class PayPalService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.paypal.mode', 'sandbox') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    private function accessToken(): string
    {
        $response = Http::withBasicAuth(
            config('services.paypal.client_id'),
            config('services.paypal.client_secret')
        )->asForm()->post("{$this->baseUrl}/v1/oauth2/token", [
            'grant_type' => 'client_credentials',
        ]);

        return $response->json('access_token');
    }

    /** Create a PayPal Order and return the full response (includes 'links'). */
    public function createOrder(Tenant $tenant, Plan $plan): array
    {
        $token = $this->accessToken();
        $price = number_format((float) $plan->price_monthly, 2, '.', '');

        [$returnUrl, $cancelUrl] = $this->callbackUrls($tenant);

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => 'USD',
                        'value'         => $price,
                    ],
                    'description' => "FaithStack {$plan->name} Plan — Monthly",
                    'custom_id'   => "t{$tenant->id}_p{$plan->id}",
                ]],
                'application_context' => [
                    'brand_name'  => 'FaithStack',
                    'user_action' => 'PAY_NOW',
                    'return_url'  => $returnUrl,
                    'cancel_url'  => $cancelUrl,
                ],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('PayPal order creation failed: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Return [returnUrl, cancelUrl] aware of subdomain vs path-prefix mode.
     * In path mode, URL::defaults(['tenant_slug' => …]) set by IdentifyTenant
     * means route() automatically includes the slug.
     */
    private function callbackUrls(Tenant $tenant): array
    {
        if (config('app.tenant_mode', 'subdomain') === 'path') {
            return [
                route('admin.billing.paypal.capture'),
                route('admin.billing.cancel'),
            ];
        }

        $scheme     = request()->isSecure() ? 'https' : 'http';
        $baseDomain = config('app.base_domain', 'faithstack.test');
        $sub        = $tenant->subdomain;

        return [
            "{$scheme}://{$sub}.{$baseDomain}/admin/billing/paypal/capture",
            "{$scheme}://{$sub}.{$baseDomain}/admin/billing/cancel",
        ];
    }

    /** Capture an approved order and return the full response. */
    public function captureOrder(string $orderId): array
    {
        $token    = $this->accessToken();
        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

        if ($response->failed()) {
            throw new \RuntimeException('PayPal capture failed: ' . $response->body());
        }

        return $response->json();
    }

    /** Verify an incoming webhook signature via PayPal's verify-webhook-signature API. */
    public function verifyWebhook(array $headers, string $body): bool
    {
        $token = $this->accessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v1/notifications/verify-webhook-signature", [
                'auth_algo'         => $headers['paypal-auth-algo']         ?? '',
                'cert_url'          => $headers['paypal-cert-url']          ?? '',
                'transmission_id'   => $headers['paypal-transmission-id']   ?? '',
                'transmission_sig'  => $headers['paypal-transmission-sig']  ?? '',
                'transmission_time' => $headers['paypal-transmission-time'] ?? '',
                'webhook_id'        => config('services.paypal.webhook_id', ''),
                'webhook_event'     => json_decode($body, true),
            ]);

        return $response->json('verification_status') === 'SUCCESS';
    }
}
