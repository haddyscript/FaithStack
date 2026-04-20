<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiring;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyExpiringSubscriptions extends Command
{
    protected $signature   = 'subscriptions:notify-expiring
                              {--days=7 : Number of days before expiry to send the warning}';

    protected $description = 'Send expiry warning emails to tenants whose subscriptions expire within the given number of days.';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $tenants = Tenant::query()
            ->whereIn('subscription_status', ['active', 'trial'])
            ->whereNotNull('subscription_ends_at')
            ->whereBetween('subscription_ends_at', [now(), now()->addDays($days)])
            ->get();

        if ($tenants->isEmpty()) {
            $this->info("No expiring subscriptions found within {$days} days.");
            return self::SUCCESS;
        }

        $sent   = 0;
        $failed = 0;

        foreach ($tenants as $tenant) {
            $daysRemaining = (int) now()->diffInDays($tenant->subscription_ends_at, false);
            $daysRemaining = max(0, $daysRemaining);

            try {
                Mail::to($tenant->email)->send(new SubscriptionExpiring($tenant, $daysRemaining));
                $sent++;

                Log::info('Subscription expiry warning sent', [
                    'tenant_id'      => $tenant->id,
                    'days_remaining' => $daysRemaining,
                ]);
            } catch (\Throwable $e) {
                $failed++;

                Log::warning('Failed to send subscription expiry warning', [
                    'tenant_id' => $tenant->id,
                    'error'     => $e->getMessage(),
                ]);
            }
        }

        $this->info("Done — {$sent} warning(s) sent, {$failed} failed.");

        return self::SUCCESS;
    }
}
