<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Webhook;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant mode
|
| 'subdomain' (default) — local dev / production with wildcard DNS (Forge, DO)
|   faithstack.test/          → landing + registration + webhooks
|   faithstack.test/superadmin → super admin
|   church1.faithstack.test/  → tenant site
|
| 'path' — single-domain deployments (Laravel Cloud free, shared hosting)
|   faithstack-main.laravel.cloud/            → landing + registration + webhooks
|   faithstack-main.laravel.cloud/superadmin  → super admin
|   faithstack-main.laravel.cloud/{slug}/     → tenant site
|
| Route NAMES are identical in both modes. In path mode, IdentifyTenant
| sets URL::defaults(['tenant_slug' => …]) so every route() call inside
| tenant context auto-injects the slug — no controller/view changes needed.
|--------------------------------------------------------------------------
*/
$tenantMode = config('app.tenant_mode', 'subdomain');

// ── Non-tenant routes (landing, registration, webhooks) ──────────────────
// Defined as a closure so they can be wrapped in Route::domain() for
// subdomain mode or registered at root level for path mode.
$rootRoutes = function () {
    Route::get('/', [LandingPageController::class, 'index'])->name('landing');

    Route::get('/register',                   [RegistrationController::class, 'show'])->name('register');
    Route::post('/register',                  [RegistrationController::class, 'store'])->name('register.store');
    Route::get('/register/check-subdomain',   [RegistrationController::class, 'checkSubdomain'])->name('register.check-subdomain');
    Route::get('/register/setup-intent',      [RegistrationController::class, 'setupIntent'])->name('register.setup-intent');
    Route::post('/register/validate-account', [RegistrationController::class, 'validateAccount'])->name('register.validate-account');

    // No CSRF, no auth, no tenant context
    Route::post('/webhooks/stripe', [Webhook\StripeWebhookController::class, 'handle'])->name('webhooks.stripe');
    Route::post('/webhooks/paypal', [Webhook\PayPalWebhookController::class, 'handle'])->name('webhooks.paypal');
};

// ── Super-admin routes ────────────────────────────────────────────────────
$superadminRoutes = function () {
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/login', [SuperAdmin\AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [SuperAdmin\AuthController::class, 'login']);

        Route::middleware(['auth', 'superadmin'])->group(function () {
            Route::post('/logout', [SuperAdmin\AuthController::class, 'logout'])->name('logout');
            Route::get('/logout', fn () => redirect()->route('superadmin.dashboard'));
            Route::get('/', [SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
            Route::resource('tenants', SuperAdmin\TenantController::class);
            Route::post('/tenants/{tenant}/toggle-subscription', [SuperAdmin\TenantController::class, 'toggleSubscription'])
                ->name('tenants.toggle-subscription');
            Route::resource('plans', SuperAdmin\PlanController::class);
            Route::post('/plans/{plan}/toggle', [SuperAdmin\PlanController::class, 'toggle'])->name('plans.toggle');
            Route::post('/tenants/{tenant}/impersonate', [SuperAdmin\ImpersonationController::class, 'start'])->name('tenants.impersonate');
        });
    });
};

// ── Tenant routes ─────────────────────────────────────────────────────────
// Registered under {tenant}.base_domain (subdomain mode) or
// under /{tenant_slug}/… (path mode). Names are identical either way.
$tenantRoutes = function () {

    Route::get('/expired', fn () => view('errors.subscription_expired'))
        ->name('subscription.expired');

    Route::middleware(['subscription'])->group(function () {
        Route::get('/', [PageController::class, 'home'])->name('home');
        Route::get('/donate', [DonationController::class, 'create'])->name('donate');
        Route::post('/donate', [DonationController::class, 'store'])->name('donate.store');
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    });

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::middleware('guest')->group(function () {
            Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login');
            Route::post('/login', [Admin\AuthController::class, 'login']);
        });

        // Token consumed here — no auth required (super admin is not yet logged in on this domain)
        Route::get('/impersonate/{token}', [Admin\ImpersonationController::class, 'enter'])->name('impersonate.enter');

        Route::middleware(['auth', 'subscription'])->group(function () {
            Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
            Route::post('/impersonate/stop', [Admin\ImpersonationController::class, 'stop'])->name('impersonate.stop');

            Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

            Route::post('/pages/preview', [Admin\PageController::class, 'preview'])->name('pages.preview');
            Route::resource('pages', Admin\PageController::class);

            Route::get('/navigation',                    [Admin\NavigationController::class, 'index'])->name('navigation.index');
            Route::post('/navigation',                   [Admin\NavigationController::class, 'store'])->name('navigation.store');
            Route::put('/navigation/{navigation}',       [Admin\NavigationController::class, 'update'])->name('navigation.update');
            Route::delete('/navigation/{navigation}',    [Admin\NavigationController::class, 'destroy'])->name('navigation.destroy');

            Route::get('/themes',            [Admin\ThemeController::class, 'index'])->name('themes.index');
            Route::post('/themes/activate',  [Admin\ThemeController::class, 'activate'])->name('themes.activate');

            Route::get('/settings',  [Admin\SettingsController::class, 'index'])->name('settings');
            Route::put('/settings',  [Admin\SettingsController::class, 'update'])->name('settings.update');

            Route::get('/billing', [Admin\BillingController::class, 'index'])->name('billing');

            Route::post('/billing/upgrade',              [Admin\CheckoutController::class, 'upgrade'])->name('billing.upgrade');
            Route::get('/billing/stripe/success',        [Admin\CheckoutController::class, 'stripeSuccess'])->name('billing.stripe.success');
            Route::get('/billing/paypal/capture',        [Admin\CheckoutController::class, 'paypalCapture'])->name('billing.paypal.capture');
            Route::get('/billing/cancel',                [Admin\CheckoutController::class, 'cancel'])->name('billing.cancel');
            Route::post('/billing/stripe/intent',        [Admin\CheckoutController::class, 'createIntent'])->name('billing.stripe.intent');
            Route::post('/billing/stripe/confirm',       [Admin\CheckoutController::class, 'confirmPayment'])->name('billing.stripe.confirm');
            Route::post('/billing/stripe/upgrade-saved', [Admin\CheckoutController::class, 'upgradeWithSavedCard'])->name('billing.stripe.upgrade-saved');

            Route::get('/donations', [Admin\DonationController::class, 'index'])->name('donations.index');

            // ── Events ──────────────────────────────────────────────────────
            Route::resource('events', Admin\EventController::class);

            // ── CRM: Members ────────────────────────────────────────────────
            Route::post('/members/bulk',                          [Admin\MemberController::class, 'bulkAction'])->name('members.bulk');
            Route::post('/members/{member}/notes',                [Admin\MemberController::class, 'addNote'])->name('members.notes.store');
            Route::delete('/members/activities/{activity}',       [Admin\MemberController::class, 'deleteActivity'])->name('members.activities.destroy');
            Route::post('/members/{member}/sync-groups',          [Admin\MemberController::class, 'syncGroups'])->name('members.sync-groups');
            Route::post('/members/{member}/toggle-tag/{tag}',     [Admin\MemberController::class, 'toggleTag'])->name('members.toggle-tag');
            Route::resource('members', Admin\MemberController::class);

            // ── CRM: Groups & Tags ──────────────────────────────────────────
            Route::get('/groups',                 [Admin\GroupController::class, 'index'])->name('groups.index');
            Route::post('/groups',                [Admin\GroupController::class, 'store'])->name('groups.store');
            Route::put('/groups/{group}',         [Admin\GroupController::class, 'update'])->name('groups.update');
            Route::delete('/groups/{group}',      [Admin\GroupController::class, 'destroy'])->name('groups.destroy');
            Route::post('/tags',                  [Admin\GroupController::class, 'storeTag'])->name('tags.store');
            Route::put('/tags/{tag}',             [Admin\GroupController::class, 'updateTag'])->name('tags.update');
            Route::delete('/tags/{tag}',          [Admin\GroupController::class, 'destroyTag'])->name('tags.destroy');

            // ── CRM: Custom Fields ──────────────────────────────────────────
            Route::get('/member-fields',                         [Admin\CustomFieldController::class, 'index'])->name('member-fields.index');
            Route::post('/member-fields',                        [Admin\CustomFieldController::class, 'store'])->name('member-fields.store');
            Route::put('/member-fields/{customField}',           [Admin\CustomFieldController::class, 'update'])->name('member-fields.update');
            Route::delete('/member-fields/{customField}',        [Admin\CustomFieldController::class, 'destroy'])->name('member-fields.destroy');
        });
    });

    // Slug catch-all — MUST be last
    Route::middleware(['subscription'])->group(function () {
        Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
    });
};

// ═════════════════════════════════════════════════════════════════════════
// SUBDOMAIN MODE  (default — local dev + wildcard DNS production)
// ═════════════════════════════════════════════════════════════════════════
if ($tenantMode !== 'path') {

    Route::domain(config('app.base_domain', 'faithstack.test'))->group(function () use ($rootRoutes, $superadminRoutes) {
        $rootRoutes();
        $superadminRoutes();
    });

    Route::middleware(['tenant'])->group($tenantRoutes);

// ═════════════════════════════════════════════════════════════════════════
// PATH MODE  (single-domain — Laravel Cloud, shared hosting)
//
// URLs become:  /church1/admin/billing  instead of  church1.faithstack.test/admin/billing
//
// IdentifyTenant reads the {tenant_slug} route parameter and calls
// URL::defaults(['tenant_slug' => …]) so all downstream route() helpers
// generate correct URLs without any controller or view changes.
// ═════════════════════════════════════════════════════════════════════════
} else {

    $rootRoutes();
    $superadminRoutes();

    Route::prefix('{tenant_slug}')->middleware(['tenant'])->group($tenantRoutes);

}
