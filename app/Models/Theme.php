<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    protected $fillable = ['name', 'slug', 'category', 'preview_image', 'config'];

    protected $casts = [
        'config' => 'array',
    ];

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function getConfigValue(string $key, mixed $default = null): mixed
    {
        return data_get($this->config, $key, $default);
    }

    /** The Blade layout slug (may differ from theme slug for shared layouts) */
    public function getLayoutSlug(): string
    {
        return $this->config['layout'] ?? $this->slug;
    }
}
