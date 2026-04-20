<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send 7-day expiry warning emails to tenants whose subscriptions are about to lapse.
Schedule::command('subscriptions:notify-expiring --days=7')->dailyAt('08:00');
