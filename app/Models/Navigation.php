<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Navigation extends Model
{
    protected $table = 'navigation';

    protected $fillable = [
        'tenant_id',
        'name',
        'url',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeForTenant(Builder $query, int $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId)->orderBy('order');
    }
}
