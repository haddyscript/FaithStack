<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomField extends Model
{
    public const TYPES = ['text', 'number', 'date', 'select'];

    protected $fillable = [
        'tenant_id',
        'label',
        'name',
        'type',
        'options',
        'sort_order',
    ];

    protected $casts = [
        'options'    => 'array',
        'sort_order' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeForTenant(Builder $query, int $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId);
    }
}
