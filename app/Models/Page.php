<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Theme;

class Page extends Model
{
    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'content',
        'is_published',
        'theme_id',
    ];

    protected $casts = [
        'content'      => 'array',
        'is_published' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
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

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function getSections(): array
    {
        return $this->content['sections'] ?? [];
    }

    public function getFooterEnabled(): bool
    {
        return (bool) ($this->content['footer_enabled'] ?? false);
    }

    public function getFooterContent(): string
    {
        return $this->content['footer_content'] ?? '';
    }
}
