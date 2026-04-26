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
    <title>{{ $event->title }} — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        :root { --primary: {{ $primary }}; --accent: {{ $accent }}; }
        .btn-primary { background-color: var(--primary); color: #fff; }
        .btn-primary:hover { opacity: 0.88; }
        .text-primary { color: var(--primary); }
        .bg-primary   { background-color: var(--primary); }
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
        <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
            @foreach($navItems as $item)
                <a href="{{ $item->url }}" class="text-white/80 hover:text-white transition-colors">{{ $item->name }}</a>
            @endforeach
            <a href="{{ route('events.index') }}" class="text-white font-semibold border-b-2 border-white/80 pb-0.5">Events</a>
        </nav>
        <a href="{{ route('donate') }}"
           class="hidden md:inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold bg-white hover:bg-white/90 transition-colors"
           style="color: var(--primary);">
            Give
        </a>
        <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
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

{{-- ── Event Banner ──────────────────────────────────────────────────────────── --}}
@if($event->image_url)
    <div class="h-64 md:h-80 overflow-hidden">
        <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
             class="w-full h-full object-cover">
    </div>
@else
    <div class="h-32 md:h-48" style="background: linear-gradient(135deg, var(--primary), var(--accent));"></div>
@endif

{{-- ── Content ───────────────────────────────────────────────────────────────── --}}
<div class="max-w-5xl mx-auto px-5 py-10">

    {{-- Back link --}}
    <a href="{{ route('events.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 mb-6 transition-colors group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        All Events
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Main content --}}
        <div class="lg:col-span-2">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-6">
                {{ $event->title }}
            </h1>

            @if($event->description)
                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                    {!! nl2br(e($event->description)) !!}
                </div>
            @endif

            @if($event->cta_text && $event->cta_url)
                <div class="mt-8">
                    <a href="{{ $event->cta_url }}" target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-base font-bold text-white btn-primary shadow-md hover:shadow-lg transition-all">
                        {{ $event->cta_text }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        {{-- Sidebar details --}}
        <div class="space-y-4">

            {{-- Date & Time card --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Date & Time</h3>
                <div class="flex items-start gap-3">
                    <div class="w-11 h-11 rounded-xl flex flex-col items-center justify-center flex-shrink-0 text-white text-center"
                         style="background-color: var(--primary);">
                        <span class="text-[10px] font-bold uppercase leading-none">{{ $event->start_date->format('M') }}</span>
                        <span class="text-lg font-extrabold leading-none">{{ $event->start_date->format('j') }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $event->start_date->format('l, F j, Y') }}</p>
                        <p class="text-sm text-slate-500">
                            {{ $event->start_date->format('g:i A') }}
                            @if($event->end_date)
                                – {{ $event->end_date->format('g:i A') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Location card --}}
            @if($event->location)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Location</h3>
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background-color: var(--primary)20;">
                        @if($event->isOnline())
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                 style="color: var(--primary);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                 style="color: var(--primary);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500">{{ $event->isOnline() ? 'Online Event' : 'In Person' }}</p>
                        <p class="text-sm text-slate-700 break-words">{{ $event->location }}</p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- ── Footer ───────────────────────────────────────────────────────────────── --}}
<footer class="bg-slate-900 text-slate-400 text-sm py-10 mt-6">
    <div class="max-w-6xl mx-auto px-5 flex flex-col md:flex-row justify-between gap-4">
        <div>
            <p class="font-semibold text-white mb-1">{{ $tenant->name }}</p>
            @if($tenant->address)<p>{{ $tenant->address }}</p>@endif
            @if($tenant->phone)<p>{{ $tenant->phone }}</p>@endif
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
