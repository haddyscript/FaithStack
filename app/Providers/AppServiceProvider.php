<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Redirect unauthenticated users to the tenant admin login
        \Illuminate\Support\Facades\Auth::redirectUsing(function () {
            return route('admin.login');
        });
    }
}
