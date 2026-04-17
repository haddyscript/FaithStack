{{-- Dispatches to the theme's hero variant component based on config['hero_variant'] --}}
@php
    $variant = $sectionConfig['variant'] ?? $config['hero_variant'] ?? 'centered';

    $heroVariantView = view()->exists("$vp.components.hero.$variant")
        ? "$vp.components.hero.$variant"
        : "themes.shared.components.hero.$variant";
@endphp
@includeIf($heroVariantView)
