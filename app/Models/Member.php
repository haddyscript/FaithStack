<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Member extends Model
{
    public const STATUSES = [
        'visitor'    => ['label' => 'Visitor',    'color' => '#475569', 'bg' => '#f1f5f9'],
        'new_member' => ['label' => 'New Member', 'color' => '#2563eb', 'bg' => '#eff6ff'],
        'active'     => ['label' => 'Active',     'color' => '#059669', 'bg' => '#ecfdf5'],
        'inactive'   => ['label' => 'Inactive',   'color' => '#dc2626', 'bg' => '#fef2f2'],
    ];

    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'birthday',
        'address',
        'photo',
        'status',
        'custom_data',
    ];

    protected $casts = [
        'birthday'    => 'date',
        'custom_data' => 'array',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'member_group')->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'member_tag')->withTimestamps();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(MemberActivity::class)->latest();
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeForTenant(Builder $query, int $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId);
    }

    // ─── Accessors ─────────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? ucfirst($this->status);
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function getCustomValue(int|string $fieldId): mixed
    {
        return $this->custom_data[$fieldId] ?? null;
    }
}
