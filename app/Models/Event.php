<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'location_type',
        'image',
        'cta_text',
        'cta_url',
        'is_published',
    ];

    protected $casts = [
        'start_date'   => 'datetime',
        'end_date'     => 'datetime',
        'is_published' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeForTenant(Builder $query, int $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_date', '>=', now())->orderBy('start_date');
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where('start_date', '<', now())->orderByDesc('start_date');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isUpcoming(): bool
    {
        return $this->start_date->isFuture();
    }

    public function isOnline(): bool
    {
        return $this->location_type === 'online';
    }
}
