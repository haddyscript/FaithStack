<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — FaithStack Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
    </style>
</head>
<body class="h-full bg-gray-50"
      x-data="{
          sidebarOpen: JSON.parse(localStorage.getItem('superadmin_sidebar') ?? 'true'),
          mobileOpen: false,
          profileOpen: false,
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              localStorage.setItem('superadmin_sidebar', this.sidebarOpen);
          },
      }">

{{-- Mobile overlay --}}
<div x-show="mobileOpen" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click="mobileOpen = false"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-20 lg:hidden"></div>

<div class="flex h-screen overflow-hidden">

    {{-- ── Desktop Sidebar ─────────────────────────────────────────────── --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-[68px]'"
           class="hidden lg:flex flex-col bg-gray-950 transition-all duration-300 ease-in-out overflow-hidden flex-shrink-0 z-30">

        {{-- Brand --}}
        <div class="border-b border-white/10 flex-shrink-0 h-16 flex items-center"
             :class="sidebarOpen ? 'px-4 gap-3' : 'justify-center'">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 flex items-center justify-center flex-shrink-0 text-white font-black text-sm shadow-sm shadow-emerald-500/30">
                FS
            </div>
            <div class="transition-all duration-200 min-w-0"
                 :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                <p class="text-white font-bold text-sm leading-none truncate">FaithStack</p>
                <p class="text-emerald-400 text-xs mt-0.5 font-medium">Platform Admin</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-2 py-4 overflow-y-auto space-y-0.5">

            {{-- Section label: Platform --}}
            <div class="transition-all duration-200 mb-1"
                 :class="sidebarOpen ? 'opacity-100 h-auto' : 'opacity-0 h-0 overflow-hidden'">
                <p class="px-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Platform</p>
            </div>

            <x-nav-item
                href="{{ route('superadmin.dashboard') }}"
                label="Dashboard"
                :active="request()->routeIs('superadmin.dashboard')"
                icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
            />

            <x-nav-item
                href="{{ route('superadmin.tenants.index') }}"
                label="Tenants"
                :active="request()->routeIs('superadmin.tenants.*')"
                icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
            />

            {{-- Section label: System --}}
            <div class="transition-all duration-200 pt-3 mb-1"
                 :class="sidebarOpen ? 'opacity-100 h-auto' : 'opacity-0 h-0 overflow-hidden'">
                <p class="px-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">System</p>
            </div>

            {{-- phpMyAdmin link (port 8080) --}}
            <div class="relative group/navitem">
                <a href="http://{{ config('app.base_domain') }}:8080" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:bg-white/10 hover:text-white transition-all duration-150"
                   :class="sidebarOpen ? '' : 'justify-center px-0'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                    <span class="transition-all duration-200 truncate"
                          :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                        Database ↗
                    </span>
                </a>
                <div class="absolute left-full top-1/2 -translate-y-1/2 ml-3 z-50 pointer-events-none
                            opacity-0 group-hover/navitem:opacity-100 transition-opacity duration-150"
                     :class="sidebarOpen ? 'hidden' : ''">
                    <div class="whitespace-nowrap bg-gray-900 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-xl ring-1 ring-white/10 relative">
                        Database ↗
                        <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-gray-900"></div>
                    </div>
                </div>
            </div>

        </nav>

    </aside>

    {{-- ── Mobile sidebar drawer ──────────────────────────────────────────── --}}
    <aside x-show="mobileOpen" x-cloak
           x-transition:enter="transition ease-out duration-250"
           x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 w-72 flex flex-col bg-gray-950 z-30 lg:hidden shadow-2xl">

        <div class="flex items-center h-16 px-4 border-b border-white/10 gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 flex items-center justify-center text-white font-black text-sm">FS</div>
            <div>
                <p class="text-white font-bold text-sm">FaithStack</p>
                <p class="text-emerald-400 text-xs">Platform Admin</p>
            </div>
            <button @click="mobileOpen = false" class="ml-auto p-1.5 text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 px-2 py-4 space-y-0.5" x-data="{ sidebarOpen: true }">
            <p class="px-3 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Platform</p>
            <x-nav-item href="{{ route('superadmin.dashboard') }}" label="Dashboard" :active="request()->routeIs('superadmin.dashboard')" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            <x-nav-item href="{{ route('superadmin.tenants.index') }}" label="Tenants" :active="request()->routeIs('superadmin.tenants.*')" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </nav>
    </aside>

    {{-- ── Main area ──────────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-h-screen min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="sticky top-0 z-20 bg-white border-b border-gray-100 shadow-sm h-14 flex items-center px-4 lg:px-5 gap-3 flex-shrink-0">

            {{-- Mobile hamburger --}}
            <button @click="mobileOpen = true" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            {{-- Desktop sidebar toggle --}}
            <button @click="toggleSidebar()"
                    class="hidden lg:flex p-2 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all flex-shrink-0"
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
                <x-breadcrumb :items="[
                    ['label' => 'Platform', 'url' => route('superadmin.dashboard')],
                    ['label' => Str::of(request()->route()->getName() ?? '')->after('superadmin.')->replace(['.index','.create','.edit','.store','.update'], '')->replace('.', ' › ')->title()->toString()],
                ]" />
            @endif

            <div class="ml-auto flex items-center gap-2">
                @yield('header-actions')

                {{-- Profile --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                        </div>
                        <span class="hidden sm:block text-sm font-semibold text-gray-700 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl ring-1 ring-gray-100 py-2 z-50">
                        <div class="px-4 py-2.5 border-b border-gray-50">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                Platform Admin
                            </span>
                        </div>
                        <div class="border-t border-gray-50 mt-1">
                            <form method="POST" action="{{ route('superadmin.logout') }}">
                                @csrf
                                <button class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors font-medium">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash --}}
        @if(session('success') || session('error'))
            <div class="px-4 lg:px-5 pt-4"
                 x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)"
                 x-show="show" x-cloak
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-1">
                @if(session('success'))
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
                        <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto">
            <div class="p-4 lg:p-6 max-w-7xl mx-auto">

                {{-- Page heading --}}
                <div class="flex items-center justify-between gap-4 mb-6">
                    <h1 class="text-xl font-bold text-gray-900">@yield('heading', 'Dashboard')</h1>
                    @hasSection('header-actions')
                        <div class="sm:hidden">@yield('header-actions')</div>
                    @endif
                </div>

                @yield('content')
            </div>
        </main>

    </div>
</div>

</body>
</html>
