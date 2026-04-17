@php
    $config  = $theme->config ?? [];
    $vp      = $theme->view_path;
    $shared  = 'themes.shared.components';

    $navStyle    = $config['nav_style']    ?? 'solid';
    $heroVariant = $config['hero_variant'] ?? 'centered';
    $footerStyle = $config['footer_style'] ?? 'multi-column';

    // Theme-specific component wins; fall back to shared
    $navView    = view()->exists("$vp.components.nav.$navStyle")       ? "$vp.components.nav.$navStyle"       : "$shared.nav.$navStyle";
    $heroView   = view()->exists("$vp.components.hero.$heroVariant")   ? "$vp.components.hero.$heroVariant"   : "$shared.hero.$heroVariant";
    $footerView = view()->exists("$vp.components.footer.$footerStyle") ? "$vp.components.footer.$footerStyle" : "$shared.footer.$footerStyle";
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($config['font_heading'] ?? 'Inter') }}:wght@400;500;600;700;800&family={{ urlencode($config['font_body'] ?? 'Inter') }}:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:   {{ $config['primary_color']   ?? '#0f0f23' }};
            --secondary: {{ $config['secondary_color'] ?? '#7c3aed' }};
        }
        body {
            font-family: '{{ $config['font_body'] ?? 'Inter' }}', sans-serif;
        }
        h1, h2, h3, h4, h5 {
            font-family: '{{ $config['font_heading'] ?? 'Inter' }}', sans-serif;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
        }
        .btn-primary:hover { opacity: 0.88; transform: translateY(-1px); }
        .text-primary  { color: var(--primary); }
        .bg-primary    { background-color: var(--primary); }
        .bg-secondary  { background-color: var(--secondary); }
        .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased" x-data="{ mobileOpen: false }">

@includeIf($navView)

<main>
    @includeIf($heroView)

    @foreach ($content['sections'] ?? [] as $section)
        @php
            $sectionView = view()->exists("$vp.components.sections.{$section['type']}")
                ? "$vp.components.sections.{$section['type']}"
                : "$shared.sections.{$section['type']}";
        @endphp
        @includeIf($sectionView, ['data' => $section['data'] ?? []])
    @endforeach
</main>

@if($page->getFooterEnabled() && $page->getFooterContent())
<section class="border-t border-white/10 py-8" style="background-color: var(--primary);">
    <div class="max-w-6xl mx-auto px-6 prose prose-sm prose-invert max-w-none
                [&_a]:text-purple-300 [&_p]:text-gray-300">
        {!! $page->getFooterContent() !!}
    </div>
</section>
@endif

@includeIf($footerView)

</body>
</html>
