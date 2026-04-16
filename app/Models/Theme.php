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

    /** The Blade layout slug, with fallback to an existing view folder */
    public function getLayoutSlug(): string
    {
        $layout = $this->config['layout'] ?? $this->slug;

        if (view()->exists("themes.{$layout}.layouts.app")) {
            return $layout;
        }

        // Map layout tokens to the closest existing theme view folder
        $fallbacks = [
            'dashboard'  => 'contemporary',
            'split'      => 'contemporary',
            'grid'       => 'contemporary',
            'minimal'    => 'modern-clean',
            'classic'    => 'church-classic',
            'editorial'  => 'modern-clean',
            'magazine'   => 'modern-clean',
        ];

        $resolved = $fallbacks[$layout] ?? 'church-classic';

        return view()->exists("themes.{$resolved}.layouts.app") ? $resolved : 'church-classic';
    }
}
