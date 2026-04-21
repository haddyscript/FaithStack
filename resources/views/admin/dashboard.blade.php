@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')

@php
    $isOnTrial    = $tenant->subscription_status === 'trial';
    $isActive     = $tenant->subscription_status === 'active';
    $hasTheme     = (bool) $tenant->theme;
    $hasPages     = $stats['pages'] > 0;
    $hasPublished = $stats['published'] > 0;

    $trialDaysLeft = null;
    if ($isOnTrial && $tenant->subscription_ends_at) {
        $trialDaysLeft = max(0, (int) now()->diffInDays($tenant->subscription_ends_at, false));
    }

    $checklist = [
        ['done' => $hasTheme,     'label' => 'Choose a theme',    'url' => route('admin.themes.index'),  'desc' => 'Pick a design for your site'],
        ['done' => $hasPages,     'label' => 'Create a page',     'url' => route('admin.pages.create'),  'desc' => 'Add your first piece of content'],
        ['done' => $hasPublished, 'label' => 'Publish your site', 'url' => route('admin.pages.index'),   'desc' => 'Make a page live for visitors'],
    ];
    $checklistDone  = collect($checklist)->where('done', true)->count();
    $checklistTotal = count($checklist);
    $nextStep       = collect($checklist)->firstWhere('done', false);
    $allDone        = $checklistDone === $checklistTotal;
    $progressPct    = (int) (($checklistDone / $checklistTotal) * 100);
@endphp

{{-- ══════════════════════════════════════════════════
     HERO / WELCOME BANNER
     ══════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden rounded-2xl mb-8"
     style="background:linear-gradient(135deg,#1e1b4b 0%,#312e81 45%,#4338ca 100%)">

    {{-- Dot-grid overlay --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background-image:radial-gradient(circle,rgba(255,255,255,.055) 1px,transparent 1px);background-size:24px 24px"></div>

    {{-- Glow blobs --}}
    <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full pointer-events-none"
         style="background:radial-gradient(circle,rgba(139,92,246,.22),transparent 70%)"></div>
    <div class="absolute bottom-0 left-1/3 w-48 h-48 rounded-full pointer-events-none"
         style="background:radial-gradient(circle,rgba(99,102,241,.15),transparent 70%)"></div>

    <div class="relative z-10 p-6 md:p-8">
        <div class="flex flex-col lg:flex-row lg:items-start gap-6 lg:gap-10">

            {{-- Left: Welcome --}}
            <div class="flex-1">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-extrabold flex-shrink-0"
                         style="background:rgba(255,255,255,.12)">
                        {{ strtoupper(substr($tenant->name, 0, 1)) }}
                    </div>
                    <span class="text-indigo-300 text-sm font-medium">{{ $tenant->name }}</span>
                </div>

                <h2 class="text-2xl md:text-3xl font-extrabold text-white leading-tight mb-1">
                    @if($allDone) Your site is live! 🎉
                    @else Let's build your site
                    @endif
                </h2>
                <p class="text-indigo-300/80 text-sm mb-5">{{ now()->format('l, F j, Y') }}</p>

                @if($nextStep)
                <div class="inline-flex items-center gap-3 rounded-xl border px-4 py-3"
                     style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.15);backdrop-filter:blur(8px)">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                         style="background:rgba(165,180,252,.15)">
                        <svg class="w-3.5 h-3.5 text-indigo-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-indigo-300/80 uppercase tracking-widest leading-none mb-0.5">Next Step</p>
                        <p class="text-white text-sm font-semibold">{{ $nextStep['label'] }}</p>
                    </div>
                    <a href="{{ $nextStep['url'] }}"
                       class="ml-2 inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white text-indigo-700 text-xs font-bold hover:bg-indigo-50 transition-colors flex-shrink-0 shadow-sm">
                        Go
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                @else
                <div class="inline-flex items-center gap-2.5 rounded-xl border px-4 py-3"
                     style="background:rgba(52,211,153,.1);border-color:rgba(52,211,153,.25)">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-emerald-200 text-sm font-semibold">Setup complete — your site is ready!</p>
                </div>
                @endif
            </div>

            {{-- Right: Onboarding checklist --}}
            <div class="lg:w-68 rounded-2xl p-5 flex-shrink-0"
                 style="width:clamp(220px,280px,100%);background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(10px)">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest">Setup Progress</p>
                    <span class="text-xs font-bold text-indigo-200">{{ $checklistDone }}/{{ $checklistTotal }}</span>
                </div>

                <div class="h-1.5 rounded-full overflow-hidden mb-4" style="background:rgba(255,255,255,.1)">
                    <div class="h-full rounded-full transition-all duration-700"
                         style="width:{{ $progressPct }}%;background:linear-gradient(90deg,#a5b4fc,#818cf8)"></div>
                </div>

                <div class="space-y-3">
                    @foreach($checklist as $item)
                    <a href="{{ $item['url'] }}"
                       class="flex items-center gap-3 group/check">
                        <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 transition-colors {{ $item['done'] ? 'bg-emerald-400/20' : 'bg-white/10 group-hover/check:bg-white/20' }}">
                            @if($item['done'])
                                <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <div class="w-1.5 h-1.5 rounded-full bg-white/30"></div>
                            @endif
                        </div>
                        <span class="text-sm font-medium transition-colors leading-none
                                     {{ $item['done'] ? 'text-white/40 line-through' : 'text-white/85 group-hover/check:text-white' }}">
                            {{ $item['label'] }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     STAT CARDS
     ══════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

    {{-- Total Pages --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 group hover:shadow-md hover:-translate-y-px transition-all duration-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <a href="{{ route('admin.pages.index') }}"
               class="text-[11px] font-semibold text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity hover:text-indigo-700">
                View →
            </a>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 leading-none mb-1 tabular-nums"
           x-data="{ n:0 }"
           x-init="$nextTick(()=>{ const t={{ $stats['pages'] }}; if(!t)return; let s=0,iv=setInterval(()=>{s+=t/35;if(s>=t){n=t;clearInterval(iv);}else{n=Math.round(s);}},18); });"
           x-text="n.toLocaleString()">0</p>
        <p class="text-xs font-medium text-gray-400">Total Pages</p>
        @if($stats['pages'] > 0)
            <div class="mt-3">
                <div class="h-1 rounded-full bg-gray-100 overflow-hidden">
                    <div class="h-full bg-indigo-400 rounded-full"
                         style="width:{{ min(100, ($stats['published'] / max(1,$stats['pages'])) * 100) }}%;transition:width 1s ease"></div>
                </div>
                <p class="text-[11px] text-gray-400 mt-1">{{ $stats['published'] }} of {{ $stats['pages'] }} published</p>
            </div>
        @endif
    </div>

    {{-- Published --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 group hover:shadow-md hover:-translate-y-px transition-all duration-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity
                         {{ $stats['published'] > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-400' }}">
                {{ $stats['published'] > 0 ? 'Live' : 'None live' }}
            </span>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 leading-none mb-1 tabular-nums"
           x-data="{ n:0 }"
           x-init="$nextTick(()=>{ const t={{ $stats['published'] }}; if(!t)return; let s=0,iv=setInterval(()=>{s+=t/35;if(s>=t){n=t;clearInterval(iv);}else{n=Math.round(s);}},18); });"
           x-text="n.toLocaleString()">0</p>
        <p class="text-xs font-medium text-gray-400">Published</p>
        @if($stats['published'] === 0 && $stats['pages'] > 0)
            <p class="text-[11px] text-amber-500 mt-2 font-medium">→ Publish a page</p>
        @endif
    </div>

    {{-- Donations --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 group hover:shadow-md hover:-translate-y-px transition-all duration-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <a href="{{ route('admin.donations.index') }}"
               class="text-[11px] font-semibold text-rose-500 opacity-0 group-hover:opacity-100 transition-opacity hover:text-rose-700">
                View →
            </a>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 leading-none mb-1 tabular-nums"
           x-data="{ n:0 }"
           x-init="$nextTick(()=>{ const t={{ $stats['donations'] }}; if(!t)return; let s=0,iv=setInterval(()=>{s+=t/35;if(s>=t){n=t;clearInterval(iv);}else{n=Math.round(s);}},18); });"
           x-text="n.toLocaleString()">0</p>
        <p class="text-xs font-medium text-gray-400">Total Donations</p>
    </div>

    {{-- Revenue --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 group hover:shadow-md hover:-translate-y-px transition-all duration-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <a href="{{ route('admin.donations.index') }}"
               class="text-[11px] font-semibold text-amber-500 opacity-0 group-hover:opacity-100 transition-opacity hover:text-amber-700">
                View →
            </a>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 leading-none mb-1 tabular-nums">
            ${{ number_format($stats['revenue'], 2) }}
        </p>
        <p class="text-xs font-medium text-gray-400">Total Revenue</p>
    </div>

</div>

{{-- ══════════════════════════════════════════════════
     LOWER GRID: Quick Actions + Sidebar
     ══════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── Quick Actions (2/3 col) ──────────────────── --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Primary actions: big gradient cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <a href="{{ route('admin.pages.create') }}"
               class="group relative overflow-hidden rounded-2xl p-6 flex flex-col justify-between min-h-[148px] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl"
               style="background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%)">
                <div class="absolute -right-6 -bottom-6 w-28 h-28 rounded-full pointer-events-none" style="background:rgba(255,255,255,.06)"></div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(255,255,255,.15)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-base">New Page</p>
                    <p class="text-indigo-200 text-xs mt-0.5">Create & publish content</p>
                </div>
                <svg class="absolute bottom-5 right-5 w-5 h-5 text-white/25 transition-all group-hover:text-white/55 group-hover:translate-x-0.5"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>

            <a href="{{ route('home') }}" target="_blank"
               class="group relative overflow-hidden rounded-2xl p-6 flex flex-col justify-between min-h-[148px] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl"
               style="background:linear-gradient(135deg,#0f766e 0%,#0d9488 100%)">
                <div class="absolute -right-6 -bottom-6 w-28 h-28 rounded-full pointer-events-none" style="background:rgba(255,255,255,.06)"></div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:rgba(255,255,255,.15)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-base">View Site</p>
                    <p class="text-teal-200 text-xs mt-0.5">Open your public website ↗</p>
                </div>
                <svg class="absolute bottom-5 right-5 w-5 h-5 text-white/25 transition-all group-hover:text-white/55 group-hover:translate-x-0.5"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        {{-- Secondary actions: compact grid --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">More Actions</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach([
                    ['href' => route('admin.navigation.index'), 'label' => 'Navigation', 'desc' => 'Manage menu', 'bg' => 'bg-blue-50',   'color' => 'text-blue-500',
                     'icon' => 'M4 6h16M4 12h10M4 18h7'],
                    ['href' => route('admin.themes.index'),     'label' => 'Themes',     'desc' => 'Change design','bg' => 'bg-violet-50', 'color' => 'text-violet-500',
                     'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                    ['href' => route('admin.donations.index'),  'label' => 'Donations',  'desc' => 'View giving',  'bg' => 'bg-rose-50',   'color' => 'text-rose-500',
                     'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                    ['href' => route('admin.settings'),         'label' => 'Settings',   'desc' => 'Site & brand', 'bg' => 'bg-gray-100',  'color' => 'text-gray-500',
                     'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ] as $a)
                <a href="{{ $a['href'] }}"
                   class="group flex flex-col items-center gap-2 py-4 px-2 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 rounded-xl {{ $a['bg'] }} flex items-center justify-center group-hover:scale-110 transition-transform duration-150">
                        <svg class="w-5 h-5 {{ $a['color'] }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $a['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-semibold text-gray-700 group-hover:text-gray-900 transition-colors">{{ $a['label'] }}</p>
                        <p class="text-[11px] text-gray-400">{{ $a['desc'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ── Right Sidebar ────────────────────────────── --}}
    <div class="flex flex-col gap-4">

        {{-- Plan card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            @if($isOnTrial)
                {{-- Trial header gradient --}}
                <div class="relative overflow-hidden px-5 pt-5 pb-4"
                     style="background:linear-gradient(135deg,#92400e,#b45309)">
                    <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full pointer-events-none"
                         style="background:rgba(255,255,255,.07)"></div>
                    <div class="flex items-center justify-between mb-3 relative">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider text-amber-100"
                              style="background:rgba(251,191,36,.15);border:1px solid rgba(251,191,36,.25)">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-300 inline-block animate-pulse"></span>
                            Free Trial
                        </span>
                        @if($trialDaysLeft !== null)
                            <span class="text-amber-200 text-xs font-bold">{{ $trialDaysLeft }}d left</span>
                        @endif
                    </div>
                    @if($trialDaysLeft !== null)
                        @php $trialPct = max(5, min(100, ($trialDaysLeft / 14) * 100)); @endphp
                        <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(180,83,9,.4)">
                            <div class="h-full rounded-full" style="width:{{ $trialPct }}%;background:linear-gradient(90deg,#fcd34d,#f59e0b)"></div>
                        </div>
                        @if($tenant->subscription_ends_at)
                            <p class="text-amber-300/70 text-[11px] mt-1.5">Expires {{ $tenant->subscription_ends_at->format('M d, Y') }}</p>
                        @endif
                    @endif
                </div>

                <div class="px-5 py-4">
                    <p class="text-sm text-gray-500 leading-relaxed mb-3">
                        Unlock custom branding, unlimited pages, and priority support.
                    </p>
                    <a href="{{ route('admin.billing') }}"
                       class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl font-bold text-sm text-white shadow-sm transition-all hover:-translate-y-px hover:shadow-md"
                       style="background:linear-gradient(135deg,#d97706,#b45309)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Upgrade to Pro
                    </a>
                </div>

            @elseif($isActive)
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 text-emerald-600" style="width:18px;height:18px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ optional($tenant->plan)->name ?? 'Pro' }}</p>
                            <p class="text-xs text-emerald-600 font-semibold">Active</p>
                        </div>
                    </div>
                    @if($tenant->subscription_ends_at)
                        <p class="text-xs text-gray-400">Renews {{ $tenant->subscription_ends_at->format('M d, Y') }}</p>
                    @endif
                    <a href="{{ route('admin.billing') }}" class="mt-3 inline-block text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                        Manage billing →
                    </a>
                </div>

            @else
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400 flex-shrink-0"></span>
                        <p class="text-sm font-bold text-gray-900">Subscription Expired</p>
                    </div>
                    <a href="{{ route('admin.billing') }}"
                       class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl font-bold text-sm text-white bg-red-500 hover:bg-red-600 transition-colors">
                        Renew Now
                    </a>
                </div>
            @endif
        </div>

        {{-- Site info card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex-1">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Site Info</p>

            <div class="space-y-2.5 mb-4">
                @if($tenant->email)
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm text-gray-600 truncate">{{ $tenant->email }}</span>
                </div>
                @endif
                @if($tenant->phone)
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span class="text-sm text-gray-600">{{ $tenant->phone }}</span>
                </div>
                @endif
            </div>

            {{-- Page counts --}}
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-extrabold text-gray-800 tabular-nums">{{ $stats['pages'] }}</p>
                    <p class="text-[11px] font-medium text-gray-400 mt-0.5">Pages</p>
                </div>
                <div class="rounded-xl p-3 text-center {{ $stats['published'] > 0 ? 'bg-emerald-50' : 'bg-gray-50' }}">
                    <p class="text-2xl font-extrabold tabular-nums {{ $stats['published'] > 0 ? 'text-emerald-700' : 'text-gray-400' }}">
                        {{ $stats['published'] }}
                    </p>
                    <p class="text-[11px] font-medium mt-0.5 {{ $stats['published'] > 0 ? 'text-emerald-500' : 'text-gray-400' }}">Published</p>
                </div>
            </div>

            {{-- Active theme chip --}}
            @if($tenant->theme)
            <div class="flex items-center gap-2.5 px-3 py-2.5 bg-indigo-50 rounded-xl">
                <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold text-indigo-400 uppercase tracking-wider leading-none mb-0.5">Active Theme</p>
                    <p class="text-xs font-bold text-indigo-700 truncate">{{ $tenant->theme->name }}</p>
                </div>
            </div>
            @else
            <a href="{{ route('admin.themes.index') }}"
               class="flex items-center gap-2.5 px-3 py-2.5 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors group">
                <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <p class="text-xs font-semibold text-amber-700 group-hover:text-amber-800 transition-colors">Choose a theme →</p>
            </a>
            @endif

        </div>
    </div>

</div>

@endsection
