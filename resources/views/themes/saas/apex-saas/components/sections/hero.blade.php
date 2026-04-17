{{-- Apex SaaS hero — delegates to the right variant, passing $data + $sectionConfig --}}
@php
    $variant = $sectionConfig['variant'] ?? $config['hero_variant'] ?? 'centered';
    $heroVariantView = view()->exists("$vp.components.hero.$variant")
        ? "$vp.components.hero.$variant"
        : "themes.shared.components.hero.$variant";
@endphp
@includeIf($heroVariantView)
