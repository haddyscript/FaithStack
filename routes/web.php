<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Webhook;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing page — root domain only, no tenant context
|--------------------------------------------------------------------------
*/
Route::domain(config('app.base_domain', 'faithstack.test'))->group(function () {
    Route::get('/', [LandingPageController::class, 'index'])->name('landing');

    // Public self-serve registration
    Route::get('/register',                   [RegistrationController::class, 'show'])->name('register');
    Route::post('/register',                  [RegistrationController::class, 'store'])->name('register.store');
    Route::get('/register/check-subdomain',   [RegistrationController::class, 'checkSubdomain'])->name('register.check-subdomain');

    // Payment webhooks — no CSRF, no auth, no tenant context
    Route::post('/webhooks/stripe', [Webhook\StripeWebhookController::class, 'handle'])->name('webhooks.stripe');
    Route::post('/webhooks/paypal', [Webhook\PayPalWebhookController::class, 'handle'])->name('webhooks.paypal');
});

/*
|--------------------------------------------------------------------------
| Super-admin routes — root domain only, no tenant context
|--------------------------------------------------------------------------
*/
Route::domain(config('app.base_domain', 'faithstack.test'))
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/login', [SuperAdmin\AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [SuperAdmin\AuthController::class, 'login']);

        Route::middleware(['auth', 'superadmin'])->group(function () {
            Route::post('/logout', [SuperAdmin\AuthController::class, 'logout'])->name('logout');
            Route::get('/logout', fn () => redirect()->route('superadmin.dashboard'));
            Route::get('/', [SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
            Route::resource('tenants', SuperAdmin\TenantController::class);
            Route::post('/tenants/{tenant}/toggle-subscription', [SuperAdmin\TenantController::class, 'toggleSubscription'])
                ->name('tenants.toggle-subscription');
        });
    });

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

    // ── Public frontend (exact routes first) ─────────────────────────────
    Route::middleware(['subscription'])->group(function () {

        // Homepage
        Route::get('/', [PageController::class, 'home'])->name('home');

        // Donation form
        Route::get('/donate', [DonationController::class, 'create'])->name('donate');
        Route::post('/donate', [DonationController::class, 'store'])->name('donate.store');

        // /{slug} is registered AFTER admin routes (see bottom of file)
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

            // Themes
            Route::get('/themes', [Admin\ThemeController::class, 'index'])->name('themes.index');
            Route::post('/themes/activate', [Admin\ThemeController::class, 'activate'])->name('themes.activate');

            // Settings
            Route::get('/settings', [Admin\SettingsController::class, 'index'])->name('settings');
            Route::put('/settings', [Admin\SettingsController::class, 'update'])->name('settings.update');

            Route::get('/billing', [Admin\BillingController::class, 'index'])->name('billing');

            // Checkout
            Route::post('/billing/upgrade',           [Admin\CheckoutController::class, 'upgrade'])->name('billing.upgrade');
            Route::get('/billing/stripe/success',     [Admin\CheckoutController::class, 'stripeSuccess'])->name('billing.stripe.success');
            Route::get('/billing/paypal/capture',     [Admin\CheckoutController::class, 'paypalCapture'])->name('billing.paypal.capture');
            Route::get('/billing/cancel',             [Admin\CheckoutController::class, 'cancel'])->name('billing.cancel');
            Route::post('/billing/stripe/intent',     [Admin\CheckoutController::class, 'createIntent'])->name('billing.stripe.intent');
            Route::post('/billing/stripe/confirm',    [Admin\CheckoutController::class, 'confirmPayment'])->name('billing.stripe.confirm');

            // Donations (read-only in admin)
            Route::get('/donations', [Admin\DonationController::class, 'index'])->name('donations.index');
        });
    });

    // ── Slug catch-all — MUST be last so it never shadows /admin/* ────────
    Route::middleware(['subscription'])->group(function () {
        Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
    });
});
