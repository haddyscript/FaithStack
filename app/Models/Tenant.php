<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'subdomain',
        'email',
        'stripe_customer_id',
        'logo',
        'phone',
        'address',
        'plan_id',
        'theme_id',
        'branding',
        'subscription_status',
        'subscription_ends_at',
    ];

    protected $casts = [
        'subscription_ends_at' => 'datetime',
        'branding'             => 'array',
    ];

    public static array $brandingDefaults = [
        'sidebar_bg'   => '#0f172a',
        'sidebar_text' => '#94a3b8',
        'primary'      => '#6366f1',
        'accent'       => '#a78bfa',
        'nav_cta_text' => 'Get Started',
        'nav_cta_url'  => '/donate',
    ];

    public function getBranding(): array
    {
        return array_merge(self::$brandingDefaults, $this->branding ?? []);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function navigation(): HasMany
    {
        return $this->hasMany(Navigation::class)->orderBy('order');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isSubscriptionActive(): bool
    {
        return $this->subscription_status === 'active'
            && ($this->subscription_ends_at === null || $this->subscription_ends_at->isFuture());
    }

    public function isOnTrial(): bool
    {
        return $this->subscription_status === 'trial'
            && ($this->subscription_ends_at === null || $this->subscription_ends_at->isFuture());
    }

    public function hasAccess(): bool
    {
        return $this->isSubscriptionActive() || $this->isOnTrial();
    }

    /** Check if this tenant's plan allows more pages to be created. */
    public function canAddPage(): bool
    {
        $max = $this->plan?->limits['max_pages'] ?? null;
        if ($max === null) {
            return true;
        }

        return $this->pages()->count() < $max;
    }

    public function getThemeSlug(): string
    {
        return $this->theme?->getLayoutSlug() ?? 'church-classic';
    }
}
