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
        'logo',
        'phone',
        'address',
        'theme_id',
        'subscription_status',
        'subscription_ends_at',
    ];

    protected $casts = [
        'subscription_ends_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

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

    public function getThemeSlug(): string
    {
        return $this->theme?->slug ?? 'church-classic';
    }
}
