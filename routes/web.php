<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| All routes are wrapped in the 'tenant' middleware which resolves the
| current tenant from the subdomain and shares it with views.
|--------------------------------------------------------------------------
*/

Route::middleware(['tenant'])->group(function () {

    // ── Subscription expired fallback ─────────────────────────────────────
    Route::get('/expired', fn () => view('errors.subscription_expired'))
        ->name('subscription.expired');

    // ── Public frontend ───────────────────────────────────────────────────
    Route::middleware(['subscription'])->group(function () {

        // Homepage
        Route::get('/', [PageController::class, 'home'])->name('home');

        // Donation form
        Route::get('/donate', [DonationController::class, 'create'])->name('donate');
        Route::post('/donate', [DonationController::class, 'store'])->name('donate.store');

        // Dynamic page by slug — keep last to avoid swallowing other routes
        Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
    });

    // ── Admin panel ───────────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {

        // Guest routes (no auth required)
        Route::middleware('guest')->group(function () {
            Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login');
            Route::post('/login', [Admin\AuthController::class, 'login']);
        });

        // Authenticated + subscription required
        Route::middleware(['auth', 'subscription'])->group(function () {
            Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');

            Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

            // Pages CRUD
            Route::resource('pages', Admin\PageController::class);

            // Navigation
            Route::get('/navigation', [Admin\NavigationController::class, 'index'])->name('navigation.index');
            Route::post('/navigation', [Admin\NavigationController::class, 'store'])->name('navigation.store');
            Route::put('/navigation/{navigation}', [Admin\NavigationController::class, 'update'])->name('navigation.update');
            Route::delete('/navigation/{navigation}', [Admin\NavigationController::class, 'destroy'])->name('navigation.destroy');

            // Settings
            Route::get('/settings', [Admin\SettingsController::class, 'index'])->name('settings');
            Route::put('/settings', [Admin\SettingsController::class, 'update'])->name('settings.update');

            // Donations (read-only in admin)
            Route::get('/donations', [Admin\DonationController::class, 'index'])->name('donations.index');
        });
    });
});
