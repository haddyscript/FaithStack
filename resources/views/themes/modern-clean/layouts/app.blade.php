<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        :root {
            --primary:   {{ $themeConfig['primary_color']   ?? '#0f172a' }};
            --secondary: {{ $themeConfig['secondary_color'] ?? '#6366f1' }};
        }
        .btn-primary { background-color: var(--secondary); color: #fff; }
        .btn-primary:hover { opacity: 0.9; }
        .text-accent { color: var(--secondary); }
        .bg-accent   { background-color: var(--secondary); }
        .border-accent { border-color: var(--secondary); }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

{{-- Header --}}
<header class="border-b border-gray-100 sticky top-0 bg-white/90 backdrop-blur z-50">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-lg font-bold tracking-tight" style="color: var(--primary)">
            @if($tenant->logo)
                <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="h-8">
            @else
                {{ $tenant->name }}
            @endif
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
            @foreach($navItems as $item)
                <a href="{{ $item->url }}" class="hover:text-gray-900 transition">{{ $item->name }}</a>
            @endforeach
            <a href="{{ route('donate') }}" class="btn-primary px-5 py-2 rounded-lg text-sm font-semibold transition">
                Donate
            </a>
        </nav>
    </div>
</header>

{{-- Sections --}}
<main>
    @foreach($page->getSections() as $section)
        @include("themes.modern-clean.sections.{$section['type']}", ['section' => $section])
    @endforeach
</main>

{{-- Page-level footer (only if enabled for this page) --}}
@if($page->getFooterEnabled() && $page->getFooterContent())
<section class="border-t border-gray-100 bg-gray-50 py-8 mt-0">
    <div class="max-w-6xl mx-auto px-6 text-gray-500 text-sm leading-relaxed
                [&_a]:text-indigo-600 [&_a:hover]:text-indigo-800 [&_a]:underline
                [&_strong]:text-gray-800
                [&_hr]:border-gray-200 [&_hr]:my-4
                [&_p]:mb-2 [&_p:last-child]:mb-0
                [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1
                [&_h2]:text-gray-800 [&_h2]:font-bold [&_h2]:text-base [&_h2]:mb-3
                [&_h3]:text-gray-700 [&_h3]:font-semibold [&_h3]:mb-2">
        {!! $page->getFooterContent() !!}
    </div>
</section>
@endif

{{-- Global footer --}}
<footer class="border-t border-gray-100 py-10 mt-0">
    <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-between gap-4 text-sm text-gray-400">
        <div>
            <p class="font-semibold text-gray-700">{{ $tenant->name }}</p>
            @if($tenant->address)<p class="mt-1">{{ $tenant->address }}</p>@endif
            @if($tenant->phone)<p>{{ $tenant->phone }}</p>@endif
        </div>
        <p class="text-xs self-end">Powered by <span class="font-medium text-gray-500">FaithStack</span></p>
    </div>
</footer>

</body>
</html>
