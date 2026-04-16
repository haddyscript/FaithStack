<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        :root {
            --primary:   {{ $themeConfig['primary_color']   ?? '#7c3aed' }};
            --secondary: {{ $themeConfig['secondary_color'] ?? '#06b6d4' }};
            --dark:      {{ $themeConfig['dark_color']      ?? '#0f172a' }};
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        .btn-outline:hover { background-color: var(--primary); color: #fff; }
        .text-primary { color: var(--primary); }
        .bg-primary   { background-color: var(--primary); }
        .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased" x-data="{ mobileOpen: false }">

{{-- Header --}}
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            @if($tenant->logo)
                <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="h-9">
            @else
                <span class="text-xl font-extrabold gradient-text">{{ $tenant->name }}</span>
            @endif
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-gray-600">
            @foreach($navItems as $item)
                <a href="{{ $item->url }}" class="hover:text-gray-900 transition-colors relative group">
                    {{ $item->name }}
                    <span class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-cyan-400 group-hover:w-full transition-all duration-200 rounded-full"></span>
                </a>
            @endforeach
            <a href="{{ route('donate') }}"
               class="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all duration-150">
                Give Now
            </a>
        </nav>

        {{-- Mobile hamburger --}}
        <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
            <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg x-show="mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden border-t border-gray-100 bg-white px-6 py-4 space-y-3">
        @foreach($navItems as $item)
            <a href="{{ $item->url }}" class="block text-sm font-semibold text-gray-600 hover:text-gray-900">{{ $item->name }}</a>
        @endforeach
        <a href="{{ route('donate') }}" class="block btn-primary text-center px-5 py-2.5 rounded-xl text-sm font-bold">Give Now</a>
    </div>
</header>

{{-- Sections --}}
<main>
    @foreach($page->getSections() as $section)
        @include("themes.contemporary.sections.{$section['type']}", ['section' => $section])
    @endforeach
</main>

{{-- Page-level footer (only if enabled for this page) --}}
@if($page->getFooterEnabled() && $page->getFooterContent())
<section class="border-t border-white/10 py-8 mt-0" style="background-color: var(--dark);">
    <div class="max-w-6xl mx-auto px-6
                prose prose-sm prose-invert max-w-none
                [&_a]:text-cyan-400 [&_a:hover]:text-cyan-300
                [&_hr]:border-white/10
                [&_p]:text-gray-400 [&_p]:leading-relaxed
                [&_ul]:text-gray-400 [&_li]:marker:text-gray-600
                [&_h2]:text-white [&_h3]:text-white [&_h4]:text-white">
        {!! $page->getFooterContent() !!}
    </div>
</section>
@endif

{{-- Global footer --}}
<footer style="background-color: var(--dark);" class="text-gray-400 py-12 mt-0">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between gap-8 mb-8">
            <div>
                <p class="text-xl font-extrabold text-white mb-2">{{ $tenant->name }}</p>
                @if($tenant->address)<p class="text-sm">{{ $tenant->address }}</p>@endif
                @if($tenant->phone)<p class="text-sm">{{ $tenant->phone }}</p>@endif
                @if($tenant->email)<p class="text-sm">{{ $tenant->email }}</p>@endif
            </div>
            <div>
                <a href="{{ route('donate') }}"
                   class="inline-flex items-center gap-2 btn-primary px-6 py-3 rounded-xl font-bold text-sm shadow-lg">
                    ❤ Give Online
                </a>
            </div>
        </div>
        <div class="pt-6 border-t border-white/10 flex items-center justify-between text-xs text-gray-600">
            <span>© {{ date('Y') }} {{ $tenant->name }}</span>
            <span>Powered by <span class="text-gray-400 font-medium">FaithStack</span></span>
        </div>
    </div>
</footer>

</body>
</html>
