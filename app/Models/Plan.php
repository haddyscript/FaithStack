<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price_monthly',
        'trial_days',
        'is_featured',
        'is_active',
        'cta_label',
        'badge',
        'description',
        'features',
        'missing_features',
        'limits',
        'stripe_price_id',
    ];

    protected $casts = [
        'price_monthly'    => 'decimal:2',
        'is_featured'      => 'boolean',
        'is_active'        => 'boolean',
        'features'         => 'array',
        'missing_features' => 'array',
        'limits'           => 'array',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_active', true)->orderBy('price_monthly');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function isFree(): bool
    {
        return $this->price_monthly == 0;
    }

    public function requiresPayment(): bool
    {
        return $this->price_monthly > 0;
    }

    public function formattedPrice(): string
    {
        if ($this->price_monthly == 0) {
            return 'Free';
        }

        return '$' . number_format((float) $this->price_monthly, 0) . '/mo';
    }

    /** Return the trial duration this plan provides for a new signup. */
    public function effectiveTrialDays(): int
    {
        if ($this->trial_days) {
            return $this->trial_days;
        }

        // Paid plans get a 14-day grace period until payment integration is live
        return $this->requiresPayment() ? 14 : 0;
    }

    /** Convert to the legacy array shape expected by pricing.blade.php. */
    public function toLandingArray(): array
    {
        return [
            'name'             => $this->name,
            'slug'             => $this->slug,
            'price'            => (float) $this->price_monthly,
            'featured'         => $this->is_featured,
            'badge'            => $this->badge,
            'cta'              => $this->cta_label,
            'description'      => $this->description,
            'trial_days'       => $this->trial_days,
            'features'         => $this->features ?? [],
            'missing'          => $this->missing_features ?? [],
        ];
    }
}
