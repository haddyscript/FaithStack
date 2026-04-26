@php
    $br      = $tenant->getBranding();
    $primary = $br['primary'] ?? '#6366f1';
    $accent  = $br['accent']  ?? '#a78bfa';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        :root {
            --primary: {{ $primary }};
            --accent:  {{ $accent }};
        }
        .btn-primary { background-color: var(--primary); color: #fff; }
        .btn-primary:hover { opacity: 0.88; }
        .text-primary { color: var(--primary); }
        .bg-primary   { background-color: var(--primary); }
        .border-primary { border-color: var(--primary); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

{{-- ── Navigation ───────────────────────────────────────────────────────────── --}}
<header class="bg-primary shadow-sm sticky top-0 z-30">
    <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
            @if($tenant->logo)
                <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="h-9">
            @else
                <span class="text-white font-bold text-lg tracking-tight">{{ $tenant->name }}</span>
            @endif
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
            @foreach($navItems as $item)
                <a href="{{ $item->url }}" class="text-white/80 hover:text-white transition-colors">{{ $item->name }}</a>
            @endforeach
            <a href="{{ route('events.index') }}"
               class="text-white font-semibold border-b-2 border-white/80 pb-0.5">Events</a>
        </nav>

        {{-- CTA button --}}
        <a href="{{ route('donate') }}"
           class="hidden md:inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold bg-white transition-colors hover:bg-white/90"
           style="color: var(--primary);">
            Give
        </a>

        {{-- Mobile toggle --}}
        <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    {{-- Mobile drawer --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-white/10 px-5 py-3 space-y-1">
        @foreach($navItems as $item)
            <a href="{{ $item->url }}" class="block py-2 text-sm text-white/80 hover:text-white transition-colors">{{ $item->name }}</a>
        @endforeach
        <a href="{{ route('events.index') }}" class="block py-2 text-sm text-white font-semibold">Events</a>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function () {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>

{{-- ── Page Hero ─────────────────────────────────────────────────────────────── --}}
<section class="bg-primary py-14">
    <div class="max-w-6xl mx-auto px-5 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-3">Events</h1>
        <p class="text-white/70 text-base max-w-xl mx-auto">
            Join us for upcoming services, programs, and community gatherings.
        </p>
    </div>
</section>

{{-- ── Upcoming Events ───────────────────────────────────────────────────────── --}}
<section class="py-14">
    <div class="max-w-6xl mx-auto px-5">

        @if($upcoming->isEmpty() && $past->isEmpty())
            <div class="text-center py-20">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-slate-500 font-medium">No upcoming events at this time.</p>
                <p class="text-slate-400 text-sm mt-1">Check back soon for new events.</p>
            </div>
        @endif

        @if($upcoming->isNotEmpty())
            <h2 class="text-2xl font-bold text-slate-800 mb-8">Upcoming Events</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                @foreach($upcoming as $event)
                <a href="{{ route('events.show', $event) }}"
                   class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    {{-- Banner --}}
                    @if($event->image_url)
                        <div class="h-44 overflow-hidden">
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-44 flex items-center justify-center" style="background: linear-gradient(135deg, var(--primary), var(--accent));">
                            <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    <div class="p-5">
                        {{-- Date badge --}}
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full text-white"
                                 style="background-color: var(--primary);">
                                {{ $event->start_date->format('M j') }}
                            </div>
                            <span class="text-xs text-slate-500">{{ $event->start_date->format('g:i A') }}</span>
                            @if($event->isOnline())
                                <span class="text-xs text-blue-600 font-medium">Online</span>
                            @endif
                        </div>

                        <h3 class="font-bold text-slate-800 text-base mb-2 line-clamp-2 group-hover:text-primary transition-colors"
                            style="--tw-text-opacity:1;">
                            {{ $event->title }}
                        </h3>

                        @if($event->description)
                            <p class="text-sm text-slate-500 line-clamp-2 mb-3">{{ $event->description }}</p>
                        @endif

                        @if($event->location)
                            <div class="flex items-center gap-1.5 text-xs text-slate-400">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="truncate">{{ $event->location }}</span>
                            </div>
                        @endif

                        @if($event->cta_text)
                            <div class="mt-4">
                                <span class="inline-flex items-center gap-1 text-sm font-semibold text-primary"
                                      style="color: var(--primary);">
                                    {{ $event->cta_text }}
                                    <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        @endif

        {{-- Past Events --}}
        @if($past->isNotEmpty())
            <div class="border-t border-slate-100 pt-12">
                <h2 class="text-xl font-bold text-slate-700 mb-6">Past Events</h2>
                <div class="space-y-3">
                    @foreach($past as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="group flex items-center gap-4 bg-white rounded-xl border border-slate-100 shadow-sm px-5 py-4 hover:shadow-md transition-shadow">
                        {{-- Date block --}}
                        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center flex-shrink-0 text-center">
                            <span class="text-xs font-bold text-slate-500 uppercase leading-none">{{ $event->start_date->format('M') }}</span>
                            <span class="text-lg font-extrabold text-slate-700 leading-none">{{ $event->start_date->format('j') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-700 truncate group-hover:text-primary transition-colors"
                               style="--tw-text-opacity:1;">{{ $event->title }}</p>
                            @if($event->location)
                                <p class="text-xs text-slate-400 truncate">{{ $event->location }}</p>
                            @endif
                        </div>
                        <svg class="w-4 h-4 text-slate-300 flex-shrink-0 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>

{{-- ── Footer ───────────────────────────────────────────────────────────────── --}}
<footer class="bg-slate-900 text-slate-400 text-sm py-10 mt-6">
    <div class="max-w-6xl mx-auto px-5 flex flex-col md:flex-row justify-between gap-4">
        <div>
            <p class="font-semibold text-white mb-1">{{ $tenant->name }}</p>
            @if($tenant->address)<p>{{ $tenant->address }}</p>@endif
            @if($tenant->phone)<p>{{ $tenant->phone }}</p>@endif
            @if($tenant->email)<p>{{ $tenant->email }}</p>@endif
        </div>
        <div class="text-right">
            <nav class="flex flex-wrap gap-4 justify-end mb-3">
                @foreach($navItems as $item)
                    <a href="{{ $item->url }}" class="hover:text-white transition-colors text-xs">{{ $item->name }}</a>
                @endforeach
                <a href="{{ route('events.index') }}" class="hover:text-white transition-colors text-xs font-medium text-slate-300">Events</a>
            </nav>
            <p class="text-xs text-slate-600">Powered by FaithStack</p>
        </div>
    </div>
</footer>

</body>
</html>
