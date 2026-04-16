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

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        .sidebar-item { @apply flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer; }
        .sidebar-item:hover { @apply bg-white/10 text-white; }
        .sidebar-item.active { @apply bg-white/15 text-white shadow-sm; }
        .sidebar-item:not(.active) { @apply text-slate-400; }

        /* Smooth sidebar collapse */
        .sidebar-label { transition: opacity 200ms, width 200ms; }

        /* Card hover lift */
        .card-lift { transition: transform 200ms, box-shadow 200ms; }
        .card-lift:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,.1); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>

<body class="h-full bg-slate-50"
      x-data="{
          sidebarOpen: true,
          profileOpen: false,
          notifOpen: false,
          mobileOpen: false,
          currentPage: '{{ request()->routeIs('admin.dashboard') ? 'dashboard' : (request()->routeIs('admin.pages.*') ? 'pages' : (request()->routeIs('admin.themes.*') ? 'themes' : (request()->routeIs('admin.navigation.*') ? 'navigation' : (request()->routeIs('admin.donations.*') ? 'donations' : 'settings')))) }}'
      }"
      @click.away="profileOpen = false; notifOpen = false">

<div class="flex h-screen overflow-hidden">

    {{-- ── Mobile overlay ─────────────────────────────────────────────────── --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 bg-black/50 z-20 lg:hidden"
         x-cloak></div>

    {{-- ── Sidebar ──────────────────────────────────────────────────────────── --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
           class="hidden lg:flex flex-col bg-slate-900 transition-all duration-300 ease-in-out overflow-hidden flex-shrink-0 z-30">

        {{-- Logo --}}
        <div class="flex items-center h-16 px-4 border-b border-white/10 flex-shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span class="sidebar-label font-semibold text-white truncate"
                      :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0 overflow-hidden'">
                    FaithStack
                </span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen"
                    class="ml-auto p-1 rounded text-slate-500 hover:text-white transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          :d="sidebarOpen ? 'M11 19l-7-7 7-7m8 14l-7-7 7-7' : 'M13 5l7 7-7 7M5 5l7 7-7 7'"/>
                </svg>
            </button>
        </div>

        {{-- Tenant name --}}
        <div class="px-4 py-3 border-b border-white/10"
             :class="sidebarOpen ? '' : 'px-2'">
            <div x-show="sidebarOpen" x-transition class="flex items-center gap-2 min-w-0">
                <div class="w-6 h-6 rounded-full bg-indigo-400 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ $tenant->name }}</p>
                    <p class="text-xs text-slate-500">{{ $tenant->subdomain }}.faithstack.test</p>
                </div>
            </div>
            <div x-show="!sidebarOpen" class="flex justify-center">
                <div class="w-7 h-7 rounded-full bg-indigo-400 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                </div>
            </div>
        </div>

        {{-- Nav items --}}
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">

            @php
                $nav = [
                    ['route' => 'admin.dashboard',        'key' => 'dashboard',   'label' => 'Dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'admin.pages.index',      'key' => 'pages',       'label' => 'Pages',       'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['route' => 'admin.navigation.index', 'key' => 'navigation',  'label' => 'Navigation',  'icon' => 'M4 6h16M4 12h16M4 18h16'],
                    ['route' => 'admin.themes.index',     'key' => 'themes',      'label' => 'Themes',      'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                    ['route' => 'admin.donations.index',  'key' => 'donations',   'label' => 'Donations',   'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                    ['route' => 'admin.settings',         'key' => 'settings',    'label' => 'Settings',    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ];
            @endphp

            @foreach($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="sidebar-item {{ request()->routeIs($item['route']) || request()->routeIs(str_replace('.index', '.*', $item['route'])) ? 'active' : '' }}"
                   :title="!sidebarOpen ? '{{ $item['label'] }}' : ''"
                   :class="sidebarOpen ? '' : 'justify-center'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                    </svg>
                    <span class="sidebar-label truncate"
                          :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach
        </nav>

        {{-- View site link --}}
        <div class="px-2 pb-4">
            <a href="{{ route('home') }}" target="_blank"
               class="sidebar-item text-slate-500 hover:text-white"
               :class="sidebarOpen ? '' : 'justify-center'"
               :title="!sidebarOpen ? 'View Site' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span class="sidebar-label truncate text-xs"
                      :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                    View Site
                </span>
            </a>
        </div>
    </aside>

    {{-- ── Main area ─────────────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top Navbar --}}
        <header class="h-16 bg-white border-b border-slate-100 flex items-center px-4 lg:px-6 gap-4 flex-shrink-0 shadow-sm">

            {{-- Mobile menu button --}}
            <button @click="mobileOpen = true"
                    class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-1.5 text-sm text-slate-500 min-w-0">
                <span class="text-slate-300">/</span>
                <span class="font-medium text-slate-700 truncate">@yield('heading', 'Dashboard')</span>
            </div>

            <div class="ml-auto flex items-center gap-2">

                {{-- Quick action --}}
                @yield('header-actions')

                {{-- Notifications --}}
                <div class="relative">
                    <button @click.stop="notifOpen = !notifOpen; profileOpen = false"
                            class="relative p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-indigo-500 rounded-full"></span>
                    </button>
                    <div x-show="notifOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click.away="notifOpen = false"
                         class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50">
                        <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wide">Notifications</p>
                        <div class="px-4 py-3 flex items-start gap-3 hover:bg-slate-50 transition cursor-pointer">
                            <div class="w-2 h-2 mt-1.5 rounded-full bg-indigo-500 flex-shrink-0"></div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Welcome to FaithStack!</p>
                                <p class="text-xs text-slate-400 mt-0.5">Your site is live and ready to customize.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profile dropdown --}}
                <div class="relative">
                    <button @click.stop="profileOpen = !profileOpen; notifOpen = false"
                            class="flex items-center gap-2 p-1.5 pl-2 pr-3 rounded-lg hover:bg-slate-100 transition">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="hidden sm:block text-sm font-medium text-slate-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="profileOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="profileOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.away="profileOpen = false"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50">
                        <div class="px-4 py-2 border-b border-slate-50">
                            <p class="text-sm font-semibold text-slate-700">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('admin.settings') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Settings
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-cloak
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mx-4 lg:mx-6 mt-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm shadow-sm">
            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
            <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div class="mx-4 lg:mx-6 mt-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm shadow-sm">
            <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto">
            <div class="p-4 lg:p-6 max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</div>

</body>
</html>
