<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $tenant->name }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @php
        $br            = $tenant->getBranding();
        $isOnTrial     = $tenant->subscription_status === 'trial';
        $isActive      = $tenant->subscription_status === 'active';
        $planName      = optional($tenant->plan)->name ?? ($isOnTrial ? 'Free Trial' : 'Starter');
        $trialDaysLeft = null;
        if ($isOnTrial && $tenant->subscription_ends_at) {
            $trialDaysLeft = max(0, (int) now()->diffInDays($tenant->subscription_ends_at, false));
        }
        $trialUrgent = $isOnTrial && $trialDaysLeft !== null && $trialDaysLeft <= 6;
    @endphp
    <style>
        :root {
            --sb-bg:    {{ $br['sidebar_bg'] }};
            --sb-text:  {{ $br['sidebar_text'] }};
            --adm-pri:  {{ $br['primary'] }};
            --adm-acc:  {{ $br['accent'] }};
        }
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--sb-bg); border-radius: 4px; }

        /* Sidebar theming */
        .adm-sidebar              { background-color: var(--sb-bg) !important; }
        .adm-sidebar .nav-text    { color: var(--sb-text) !important; }
        .adm-nav-item:hover       { background-color: rgba(255,255,255,0.07) !important; }
        .adm-nav-item.active      { background-color: var(--adm-pri) !important; color: #fff !important; }
        .adm-nav-item.active svg  { color: #fff !important; }
        .adm-nav-item.active span { color: #fff !important; }

        /* Primary action buttons */
        .adm-btn-primary {
            background-color: var(--adm-pri) !important;
            color: #fff !important;
        }
        .adm-btn-primary:hover { filter: brightness(1.1); }

        /* Accent badges / toggles */
        .adm-accent-bg { background-color: var(--adm-acc) !important; }
        .adm-accent-text { color: var(--adm-pri) !important; }

        /* Focus rings */
        *:focus-visible { outline-color: var(--adm-pri) !important; }
    </style>
</head>

<body class="h-full bg-slate-50"
      x-data="{
          sidebarOpen: JSON.parse(localStorage.getItem('tenant_sidebar') ?? 'true'),
          mobileOpen: false,
          profileOpen: false,
          notifOpen: false,
          quickOpen: false,
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              localStorage.setItem('tenant_sidebar', this.sidebarOpen);
          },
      }">

{{-- ── Mobile overlay ─────────────────────────────────────────────────── --}}
<div x-show="mobileOpen"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileOpen = false"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-20 lg:hidden"></div>

<div class="flex h-screen overflow-hidden">

    {{-- ── Sidebar ──────────────────────────────────────────────────────── --}}
    <aside
        :class="sidebarOpen ? 'w-64' : 'w-[68px]'"
        class="adm-sidebar flex-col transition-all duration-300 ease-in-out overflow-hidden flex-shrink-0 z-30
               hidden lg:flex"
    >
        {{-- Brand --}}
        <div class="border-b border-white/10 flex-shrink-0 h-16 flex items-center"
             :class="sidebarOpen ? 'px-4 gap-3' : 'justify-center'">
            <div class="w-8 h-8 bg-indigo-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm shadow-indigo-500/30">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <span class="font-bold text-white text-base transition-all duration-200 truncate"
                  :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                FaithStack
            </span>
        </div>

        {{-- Tenant identity --}}
        <div class="px-3 py-3 border-b border-white/10 flex-shrink-0">
            <div class="flex items-center gap-2.5 min-w-0 rounded-xl px-1 py-1">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                </div>
                <div class="min-w-0 transition-all duration-200"
                     :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                    <p class="text-xs font-semibold text-white truncate leading-tight">{{ $tenant->name }}</p>
                    <p class="text-xs text-slate-500 truncate leading-tight">{{ $tenant->subdomain }}.faithstack.test</p>
                </div>
            </div>
        </div>

        {{-- ── Plan badge ── --}}
        <div class="px-3 py-2.5 border-b border-white/10 flex-shrink-0">
            @if($isOnTrial)
            <a href="{{ route('admin.billing') }}"
               class="flex items-center gap-2 px-2.5 py-2 rounded-xl bg-amber-400/15 border border-amber-400/25 hover:bg-amber-400/25 transition-all duration-200 group"
               :class="sidebarOpen ? '' : 'justify-center'">
                <span class="text-[15px] flex-shrink-0" title="Free Trial">⚡</span>
                <div class="min-w-0 transition-all duration-200" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                    <p class="text-[11px] font-bold text-amber-300 leading-tight">Free Trial</p>
                    @if($trialDaysLeft !== null)
                    <p class="text-[10px] leading-tight {{ $trialUrgent ? 'text-red-300' : 'text-amber-400/70' }}">
                        {{ $trialDaysLeft }} day{{ $trialDaysLeft !== 1 ? 's' : '' }} left
                    </p>
                    @endif
                </div>
                <svg class="w-3 h-3 text-amber-400/50 ml-auto flex-shrink-0 group-hover:translate-x-0.5 transition-transform duration-150"
                     :class="sidebarOpen ? 'block' : 'hidden'"
                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @elseif($isActive)
            <a href="{{ route('admin.billing') }}"
               class="flex items-center gap-2 px-2.5 py-2 rounded-xl bg-indigo-500/15 border border-indigo-400/20 hover:bg-indigo-500/25 transition-all duration-200"
               :class="sidebarOpen ? '' : 'justify-center'">
                <span class="text-[15px] flex-shrink-0" title="{{ $planName }}">💎</span>
                <div class="min-w-0 transition-all duration-200" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                    <p class="text-[11px] font-bold text-indigo-300 leading-tight">{{ $planName }}</p>
                    <p class="text-[10px] text-indigo-400/60 leading-tight">Active</p>
                </div>
            </a>
            @else
            <a href="{{ route('admin.billing') }}"
               class="flex items-center gap-2 px-2.5 py-2 rounded-xl bg-red-500/15 border border-red-400/25 hover:bg-red-500/25 transition-all duration-200"
               :class="sidebarOpen ? '' : 'justify-center'">
                <span class="text-[15px] flex-shrink-0">⚠️</span>
                <div class="min-w-0 transition-all duration-200" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                    <p class="text-[11px] font-bold text-red-300 leading-tight">Subscription Expired</p>
                    <p class="text-[10px] text-red-400/60 leading-tight">Renew to restore access</p>
                </div>
            </a>
            @endif
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-2 py-3 overflow-y-auto space-y-0.5">

            {{-- Section: Overview --}}
            <div class="transition-all duration-200 mb-1"
                 :class="sidebarOpen ? 'opacity-100 h-auto' : 'opacity-0 h-0 overflow-hidden'">
                <p class="px-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Overview</p>
            </div>

            <x-nav-item
                href="{{ route('admin.dashboard') }}"
                label="Dashboard"
                :active="request()->routeIs('admin.dashboard')"
                icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
            />

            {{-- Section: Website --}}
            <div class="transition-all duration-200 pt-3 mb-1"
                 :class="sidebarOpen ? 'opacity-100 h-auto' : 'opacity-0 h-0 overflow-hidden'">
                <p class="px-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Website</p>
            </div>

            <x-nav-item
                href="{{ route('admin.pages.index') }}"
                label="Pages"
                :active="request()->routeIs('admin.pages.*')"
                icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            />

            <x-nav-item
                href="{{ route('admin.navigation.index') }}"
                label="Navigation"
                :active="request()->routeIs('admin.navigation.*')"
                icon="M4 6h16M4 12h10M4 18h7"
            />

            <x-nav-item
                href="{{ route('admin.themes.index') }}"
                label="Themes"
                :active="request()->routeIs('admin.themes.*')"
                icon="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"
            />

            {{-- Section: Management --}}
            <div class="transition-all duration-200 pt-3 mb-1"
                 :class="sidebarOpen ? 'opacity-100 h-auto' : 'opacity-0 h-0 overflow-hidden'">
                <p class="px-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Management</p>
            </div>

            <x-nav-item
                href="{{ route('admin.members.index') }}"
                label="Members"
                :active="request()->routeIs('admin.members.*') || request()->routeIs('admin.groups.*') || request()->routeIs('admin.tags.*') || request()->routeIs('admin.member-fields.*')"
                icon="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
            />

            <x-nav-item
                href="{{ route('admin.donations.index') }}"
                label="Donations"
                :active="request()->routeIs('admin.donations.*')"
                icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
            />

            {{-- Section: Account --}}
            <div class="transition-all duration-200 pt-3 mb-1"
                 :class="sidebarOpen ? 'opacity-100 h-auto' : 'opacity-0 h-0 overflow-hidden'">
                <p class="px-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Account</p>
            </div>

            <x-nav-item
                href="{{ route('admin.settings') }}"
                label="Settings"
                :active="request()->routeIs('admin.settings')"
                icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"
            />

            <x-nav-item
                href="{{ route('admin.billing') }}"
                label="Plan & Billing"
                :active="request()->routeIs('admin.billing')"
                icon="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
            />

        </nav>

        {{-- View site --}}
        <div class="px-2 py-3 border-t border-white/10 flex-shrink-0">
            <div class="relative group/navitem">
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:bg-white/10 hover:text-white transition-all duration-150"
                   :class="sidebarOpen ? '' : 'justify-center px-0'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span class="transition-all duration-200 text-xs truncate"
                          :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                        View Site
                    </span>
                </a>
                {{-- Tooltip --}}
                <div class="absolute left-full top-1/2 -translate-y-1/2 ml-3 z-50 pointer-events-none
                            opacity-0 group-hover/navitem:opacity-100 transition-opacity duration-150"
                     :class="sidebarOpen ? 'hidden' : ''">
                    <div class="whitespace-nowrap bg-gray-900 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-xl ring-1 ring-white/10 relative">
                        View Site
                        <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-gray-900"></div>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- ── Mobile sidebar drawer ──────────────────────────────────────────── --}}
    <aside x-show="mobileOpen" x-cloak
           x-transition:enter="transition ease-out duration-250"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 w-72 flex flex-col bg-slate-900 z-30 lg:hidden shadow-2xl overflow-y-auto">

        <div class="flex items-center h-16 px-4 border-b border-white/10 gap-3">
            <div class="w-8 h-8 bg-indigo-500 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <span class="font-bold text-white">FaithStack</span>
            <button @click="mobileOpen = false" class="ml-auto p-1.5 text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="px-3 py-3 border-b border-white/10">
            <div class="flex items-center gap-2.5 px-1">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs font-semibold text-white leading-tight">{{ $tenant->name }}</p>
                    <p class="text-xs text-slate-500 leading-tight">{{ $tenant->subdomain }}.faithstack.test</p>
                </div>
            </div>
        </div>

        {{-- Mobile plan badge --}}
        <div class="px-3 py-2.5 border-b border-white/10">
            @if($isOnTrial)
            <a href="{{ route('admin.billing') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-xl bg-amber-400/15 border border-amber-400/25">
                <span class="text-sm">⚡</span>
                <div>
                    <p class="text-[11px] font-bold text-amber-300 leading-tight">Free Trial</p>
                    @if($trialDaysLeft !== null)
                    <p class="text-[10px] {{ $trialUrgent ? 'text-red-300' : 'text-amber-400/70' }} leading-tight">{{ $trialDaysLeft }} day{{ $trialDaysLeft !== 1 ? 's' : '' }} left — Upgrade</p>
                    @endif
                </div>
            </a>
            @elseif($isActive)
            <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-indigo-500/15 border border-indigo-400/20">
                <span class="text-sm">💎</span>
                <p class="text-[11px] font-bold text-indigo-300">{{ $planName }} — Active</p>
            </div>
            @endif
        </div>

        <nav class="flex-1 px-2 py-3 space-y-0.5" x-data="{ sidebarOpen: true }">
            <p class="px-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Overview</p>
            <x-nav-item href="{{ route('admin.dashboard') }}" label="Dashboard" :active="request()->routeIs('admin.dashboard')" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            <p class="px-3 pt-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Website</p>
            <x-nav-item href="{{ route('admin.pages.index') }}" label="Pages" :active="request()->routeIs('admin.pages.*')" icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            <x-nav-item href="{{ route('admin.navigation.index') }}" label="Navigation" :active="request()->routeIs('admin.navigation.*')" icon="M4 6h16M4 12h10M4 18h7"/>
            <x-nav-item href="{{ route('admin.themes.index') }}" label="Themes" :active="request()->routeIs('admin.themes.*')" icon="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
            <p class="px-3 pt-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Management</p>
            <x-nav-item href="{{ route('admin.members.index') }}" label="Members" :active="request()->routeIs('admin.members.*') || request()->routeIs('admin.groups.*') || request()->routeIs('admin.member-fields.*')" icon="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            <x-nav-item href="{{ route('admin.donations.index') }}" label="Donations" :active="request()->routeIs('admin.donations.*')" icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            <p class="px-3 pt-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Account</p>
            <x-nav-item href="{{ route('admin.settings') }}" label="Settings" :active="request()->routeIs('admin.settings')" icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <x-nav-item href="{{ route('admin.billing') }}" label="Plan & Billing" :active="request()->routeIs('admin.billing')" icon="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </nav>

        <div class="px-2 py-3 border-t border-white/10" x-data="{ sidebarOpen: true }">
            <x-nav-item href="{{ route('home') }}" label="View Site ↗" :active="false" icon="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </div>
    </aside>

    {{-- ── Main area ──────────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="h-14 bg-white border-b border-slate-100 flex items-center px-4 lg:px-5 gap-3 flex-shrink-0 shadow-sm">

            {{-- Mobile hamburger --}}
            <button @click="mobileOpen = true" class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            {{-- Desktop sidebar toggle --}}
            <button @click="toggleSidebar()"
                    class="hidden lg:flex p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all flex-shrink-0"
                    :title="sidebarOpen ? 'Collapse sidebar' : 'Expand sidebar'">
                <svg class="w-4 h-4 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>

            {{-- Breadcrumb --}}
            @hasSection('breadcrumbs')
                @yield('breadcrumbs')
            @else
                <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => Str::of(request()->route()->getName() ?? '')->after('admin.')->replace(['.index','.create','.edit','.store','.update'], '')->replace('.', ' › ')->title()->toString()]]" />
            @endif

            <div class="ml-auto flex items-center gap-2">

                @yield('header-actions')

                {{-- Quick Actions --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open; $nextTick(() => $el.closest('[x-data]').profileOpen = false)"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Quick Actions
                        <svg class="w-3 h-3 opacity-70 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl ring-1 ring-slate-100 py-2 z-50">
                        <p class="px-4 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Create</p>
                        <a href="{{ route('admin.pages.create') }}" @click="open = false"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            <div>
                                <p class="font-semibold text-xs">New Page</p>
                                <p class="text-xs text-slate-400">Create & publish content</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.members.create') }}" @click="open = false"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            <div>
                                <p class="font-semibold text-xs">Add Member</p>
                                <p class="text-xs text-slate-400">Add to member directory</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.navigation.index') }}" @click="open = false"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h7"/></svg>
                            <div>
                                <p class="font-semibold text-xs">Add Nav Item</p>
                                <p class="text-xs text-slate-400">Update your menu</p>
                            </div>
                        </a>
                        <div class="border-t border-slate-50 my-1"></div>
                        <p class="px-4 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Customize</p>
                        <a href="{{ route('admin.themes.index') }}" @click="open = false"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            <div>
                                <p class="font-semibold text-xs">Change Theme</p>
                                <p class="text-xs text-slate-400">10+ professional designs</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Notifications --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="relative p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-indigo-500 rounded-full ring-2 ring-white"></span>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute right-0 top-full mt-2 w-72 bg-white rounded-2xl shadow-xl ring-1 ring-slate-100 py-2 z-50">
                        <p class="px-4 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">Notifications</p>
                        <div class="px-4 py-3 flex items-start gap-3 hover:bg-slate-50 transition cursor-pointer">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3l14 9-14 9V3z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Welcome to FaithStack!</p>
                                <p class="text-xs text-slate-400 mt-0.5">Your site is live and ready to customize.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profile --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="hidden sm:block text-sm font-semibold text-slate-700 max-w-[100px] truncate">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl ring-1 ring-slate-100 py-2 z-50">
                        <div class="px-4 py-2.5 border-b border-slate-50">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('admin.settings') }}" @click="open = false"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Settings
                        </a>
                        @if($isOnTrial)
                        <a href="{{ route('admin.billing') }}" @click="open = false"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-amber-600 hover:bg-amber-50 transition-colors font-semibold">
                            <span class="text-sm">⚡</span>
                            Upgrade Plan
                        </a>
                        @endif
                        <div class="border-t border-slate-50 my-1"></div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- ── Trial urgency banner ── --}}
        @if($isOnTrial)
        <div class="flex-shrink-0 {{ $trialUrgent
            ? 'bg-gradient-to-r from-orange-500 via-red-500 to-rose-500'
            : 'bg-gradient-to-r from-amber-500 via-amber-400 to-orange-400' }}">
            <div class="flex items-center gap-3 px-4 lg:px-5 py-2.5 max-w-7xl">
                <span class="text-base flex-shrink-0">{{ $trialUrgent ? '🔥' : '⚡' }}</span>
                <p class="text-white text-sm font-medium flex-1 min-w-0 leading-snug">
                    @if($trialUrgent)
                        <span class="font-bold">Your trial ends in {{ $trialDaysLeft }} day{{ $trialDaysLeft !== 1 ? 's' : '' }}.</span>
                        Upgrade now to keep your website active and features intact.
                    @else
                        You're on the Free Trial.
                        Upgrade to Pro to unlock custom branding, more pages, and priority support.
                    @endif
                </p>
                <a href="{{ route('admin.billing') }}"
                   class="flex-shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-white text-xs font-bold shadow-sm transition-all hover:scale-[1.02] whitespace-nowrap
                          {{ $trialUrgent ? 'text-red-600 hover:bg-red-50' : 'text-amber-700 hover:bg-amber-50' }}">
                    {{ $trialUrgent ? 'Upgrade Now' : 'View Plans' }}
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
        @endif

        {{-- ── Impersonation banner ── --}}
        @if(session('impersonating'))
        <div class="flex-shrink-0 bg-gradient-to-r from-violet-600 to-purple-600 border-b border-purple-700/50">
            <div class="flex items-center gap-3 px-4 lg:px-5 py-2.5">
                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <p class="text-white text-sm flex-1 min-w-0 truncate">
                    <span class="font-semibold">Super Admin</span>
                    <span class="opacity-75 mx-1">—</span>
                    Viewing as
                    <span class="font-bold">{{ session('tenant_name', $tenant->name) }}</span>
                </p>
                <form method="POST" action="{{ route('admin.impersonate.stop') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/20 hover:bg-white/30 border border-white/25 text-white text-xs font-bold transition-colors whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Leave Impersonation
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Flash --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                 x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 max-h-20"
                 x-transition:leave-end="opacity-0 max-h-0"
                 class="mx-4 lg:mx-5 mt-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm font-medium overflow-hidden">
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
                <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mx-4 lg:mx-5 mt-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm font-medium">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto">
            <div class="p-4 lg:p-6 max-w-7xl mx-auto">

                {{-- Page heading row --}}
                <div class="flex items-center justify-between gap-4 mb-6">
                    <h1 class="text-xl font-bold text-slate-800">@yield('heading', 'Dashboard')</h1>
                    @hasSection('header-actions')
                        <div class="sm:hidden flex items-center gap-2">@yield('header-actions')</div>
                    @endif
                </div>

                @yield('content')
            </div>
        </main>
    </div>

</div>
</body>
</html>
