@php
    $config  = $theme->config ?? [];
    $vp      = $theme->view_path;
    $shared  = 'themes.shared.components';

    $navStyle    = $config['nav_style']                                    ?? 'solid';
    $footerStyle = $config['sections']['footer']['style'] ?? $config['footer_style'] ?? 'multi-column';

    // Theme-specific component wins; fall back to shared
    $navView    = view()->exists("$vp.components.nav.$navStyle")       ? "$vp.components.nav.$navStyle"       : "$shared.nav.$navStyle";
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
    @include('themes.shared._animations')
    <style>
        :root {
            --primary:    {{ $config['primary_color']        ?? '#0f0f23' }};
            --secondary:  {{ $config['secondary_color']      ?? '#7c3aed' }};
            --text-pri:   {{ $config['text_primary']         ?? '#111827' }};
            --text-sec:   {{ $config['text_secondary']       ?? '#6b7280' }};
            --text-inv:   {{ $config['text_inverse']         ?? '#ffffff' }};
            --bg-pri:     {{ $config['background_primary']   ?? '#ffffff' }};
            --bg-sec:     {{ $config['background_secondary'] ?? '#f9fafb' }};
        }
        body      { font-family: '{{ $config['font_body']    ?? 'Inter' }}', sans-serif; background: var(--bg-pri); color: var(--text-pri); }
        h1,h2,h3,h4,h5 { font-family: '{{ $config['font_heading'] ?? 'Inter' }}', sans-serif; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: var(--text-inv); }
        .btn-primary:hover { opacity: 0.88; transform: translateY(-1px); }
    </style>
</head>
<body class="antialiased" x-data="{ mobileOpen: false }">

@includeIf($navView)

<main>
    @foreach ($content['sections'] ?? [] as $section)
        @php
            $type         = $section['type'] ?? '';
            $sectionConfig = $config['sections'][$type] ?? [];
            $sectionView  = view()->exists("$vp.components.sections.$type")
                ? "$vp.components.sections.$type"
                : "$shared.sections.$type";
        @endphp
        @includeIf($sectionView, ['data' => $section, 'sectionConfig' => $sectionConfig])
    @endforeach
</main>

@if($page->getFooterEnabled() && $page->getFooterContent())
<section style="background:var(--primary); color:var(--text-inv);" class="border-t border-white/10 py-10">
    <div class="max-w-6xl mx-auto px-6 prose prose-sm prose-invert max-w-none">
        {!! $page->getFooterContent() !!}
    </div>
</section>
@endif

@includeIf($footerView)

</body>
</html>
