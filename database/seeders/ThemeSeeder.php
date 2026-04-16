<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            // ── Church Themes ──────────────────────────────────────────────
            [
                'name'     => 'Church Classic',
                'slug'     => 'church-classic',
                'category' => 'church',
                'config'   => [
                    'layout'          => 'church-classic',
                    'primary_color'   => '#1e3a8a',
                    'secondary_color' => '#f59e0b',
                    'font_heading'    => 'Merriweather',
                    'font_body'       => 'Merriweather',
                    'button_radius'   => 'rounded-full',
                ],
            ],
            [
                'name'     => 'Grace Sanctuary',
                'slug'     => 'grace-sanctuary',
                'category' => 'church',
                'config'   => [
                    'layout'          => 'church-classic',
                    'primary_color'   => '#7c1d3f',
                    'secondary_color' => '#d4a847',
                    'font_heading'    => 'Playfair Display',
                    'font_body'       => 'Lato',
                    'button_radius'   => 'rounded-lg',
                ],
            ],
            [
                'name'     => 'Harvest Chapel',
                'slug'     => 'harvest-chapel',
                'category' => 'church',
                'config'   => [
                    'layout'          => 'church-classic',
                    'primary_color'   => '#78350f',
                    'secondary_color' => '#f97316',
                    'font_heading'    => 'Lora',
                    'font_body'       => 'Source Sans Pro',
                    'button_radius'   => 'rounded-md',
                ],
            ],
            [
                'name'     => 'Living Waters',
                'slug'     => 'living-waters',
                'category' => 'church',
                'config'   => [
                    'layout'          => 'contemporary',
                    'primary_color'   => '#0f4c75',
                    'secondary_color' => '#00b4d8',
                    'font_heading'    => 'Nunito',
                    'font_body'       => 'Nunito',
                    'button_radius'   => 'rounded-2xl',
                ],
            ],
            [
                'name'     => 'Cornerstone',
                'slug'     => 'cornerstone',
                'category' => 'church',
                'config'   => [
                    'layout'          => 'contemporary',
                    'primary_color'   => '#1b4332',
                    'secondary_color' => '#52b788',
                    'font_heading'    => 'DM Serif Display',
                    'font_body'       => 'DM Sans',
                    'button_radius'   => 'rounded-lg',
                ],
            ],
            // ── Business Themes ────────────────────────────────────────────
            [
                'name'     => 'Modern Clean',
                'slug'     => 'modern-clean',
                'category' => 'business',
                'config'   => [
                    'layout'          => 'modern-clean',
                    'primary_color'   => '#0f172a',
                    'secondary_color' => '#6366f1',
                    'font_heading'    => 'Inter',
                    'font_body'       => 'Inter',
                    'button_radius'   => 'rounded-lg',
                ],
            ],
            [
                'name'     => 'Apex Pro',
                'slug'     => 'apex-pro',
                'category' => 'business',
                'config'   => [
                    'layout'          => 'modern-clean',
                    'primary_color'   => '#0a0a0a',
                    'secondary_color' => '#2563eb',
                    'font_heading'    => 'Space Grotesk',
                    'font_body'       => 'Inter',
                    'button_radius'   => 'rounded-none',
                ],
            ],
            [
                'name'     => 'Bloom Studio',
                'slug'     => 'bloom-studio',
                'category' => 'business',
                'config'   => [
                    'layout'          => 'modern-clean',
                    'primary_color'   => '#831843',
                    'secondary_color' => '#f9a8d4',
                    'font_heading'    => 'DM Sans',
                    'font_body'       => 'DM Sans',
                    'button_radius'   => 'rounded-full',
                ],
            ],
            // ── Nonprofit Themes ───────────────────────────────────────────
            [
                'name'     => 'Unity Community',
                'slug'     => 'unity-community',
                'category' => 'nonprofit',
                'config'   => [
                    'layout'          => 'contemporary',
                    'primary_color'   => '#4c1d95',
                    'secondary_color' => '#a3e635',
                    'font_heading'    => 'Outfit',
                    'font_body'       => 'Outfit',
                    'button_radius'   => 'rounded-xl',
                ],
            ],
            [
                'name'     => 'Hope Rising',
                'slug'     => 'hope-rising',
                'category' => 'nonprofit',
                'config'   => [
                    'layout'          => 'contemporary',
                    'primary_color'   => '#9a3412',
                    'secondary_color' => '#fbbf24',
                    'font_heading'    => 'Raleway',
                    'font_body'       => 'Raleway',
                    'button_radius'   => 'rounded-lg',
                ],
            ],
        ];

        foreach ($themes as $theme) {
            Theme::updateOrCreate(
                ['slug' => $theme['slug']],
                [
                    'name'     => $theme['name'],
                    'category' => $theme['category'],
                    'config'   => $theme['config'],
                ]
            );
        }
    }
}
