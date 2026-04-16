<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        Theme::upsert([
            [
                'name'   => 'Church Classic',
                'slug'   => 'church-classic',
                'config' => json_encode([
                    'primary_color'   => '#1e3a8a',
                    'secondary_color' => '#f59e0b',
                    'font'            => 'Merriweather',
                ]),
            ],
            [
                'name'   => 'Modern Clean',
                'slug'   => 'modern-clean',
                'config' => json_encode([
                    'primary_color'   => '#0f172a',
                    'secondary_color' => '#6366f1',
                    'font'            => 'Inter',
                ]),
            ],
        ], ['slug'], ['name', 'config']);
    }
}
