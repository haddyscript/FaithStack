<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Merriweather', serif; }
        :root {
            --primary:   {{ $themeConfig['primary_color']   ?? '#1e3a8a' }};
            --secondary: {{ $themeConfig['secondary_color'] ?? '#f59e0b' }};
        }
        .btn-primary { background-color: var(--primary); color: #fff; }
        .btn-primary:hover { opacity: 0.9; }
        .text-primary { color: var(--primary); }
        .bg-primary   { background-color: var(--primary); }
        .border-primary { border-color: var(--primary); }
    </style>
</head>
<body class="bg-white text-gray-800">

{{-- Header / Nav --}}
<header class="bg-primary text-white shadow">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-xl font-bold tracking-wide">
            @if($tenant->logo)
                <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="h-10">
            @else
                {{ $tenant->name }}
            @endif
        </a>
        <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
            @foreach($navItems as $item)
                <a href="{{ $item->url }}" class="hover:opacity-75 transition">{{ $item->name }}</a>
            @endforeach
            <a href="{{ route('donate') }}" class="btn-primary px-4 py-1.5 rounded-full text-sm font-semibold"
               style="background: var(--secondary); color: #1a1a1a;">Give</a>
        </nav>
    </div>
</header>

{{-- Page Sections --}}
<main>
    @foreach($page->getSections() as $section)
        @include("themes.church-classic.sections.{$section['type']}", ['section' => $section])
    @endforeach
</main>

{{-- Page-level footer (only if enabled for this page) --}}
@if($page->getFooterEnabled() && $page->getFooterContent())
<section class="bg-gray-800 border-t border-gray-700 py-8 mt-0">
    <div class="max-w-6xl mx-auto px-6 text-gray-300 text-sm leading-relaxed
                [&_a]:text-yellow-400 [&_a:hover]:text-yellow-300 [&_a]:underline
                [&_strong]:text-white
                [&_hr]:border-gray-600 [&_hr]:my-4
                [&_p]:mb-2 [&_p:last-child]:mb-0
                [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1
                [&_h2]:text-white [&_h2]:font-bold [&_h2]:text-base [&_h2]:mb-3
                [&_h3]:text-white [&_h3]:font-semibold [&_h3]:mb-2">
        {!! $page->getFooterContent() !!}
    </div>
</section>
@endif

{{-- Global footer --}}
<footer class="bg-gray-900 text-gray-400 text-sm py-8 mt-0">
    <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-between gap-4">
        <div>
            <p class="font-semibold text-white">{{ $tenant->name }}</p>
            @if($tenant->address)<p>{{ $tenant->address }}</p>@endif
            @if($tenant->phone)<p>{{ $tenant->phone }}</p>@endif
            @if($tenant->email)<p>{{ $tenant->email }}</p>@endif
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-600 mt-4">Powered by FaithStack</p>
        </div>
    </div>
</footer>

</body>
</html>
