<nav x-data="{
        open: false,
        scrolled: false,
        compact: false,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled  = window.scrollY > 20;
                this.compact   = window.scrollY > 80;
            });
        }
     }"
     :class="scrolled
         ? 'bg-[#09090b]/90 backdrop-blur-xl border-white/10 shadow-[0_1px_0_0_rgba(255,255,255,0.05)]'
         : 'bg-transparent border-transparent'"
     class="fixed top-0 inset-x-0 z-50 border-b transition-all duration-500">

    <div :class="compact ? 'py-2.5' : 'py-4'"
         class="max-w-7xl mx-auto px-6 flex items-center justify-between transition-all duration-500">

        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2 group">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-shadow duration-300">
                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd"/></svg>
            </div>
            <span :class="compact ? 'text-sm' : 'text-base'" class="text-white font-bold tracking-tight transition-all duration-300">FaithStack</span>
        </a>

        {{-- Desktop links --}}
        <div class="hidden md:flex items-center gap-7 text-sm text-white/50">
            @foreach(['features' => 'Features', 'themes' => 'Themes', 'how-it-works' => 'How it works', 'pricing' => 'Pricing'] as $anchor => $label)
            <a href="#{{ $anchor }}" class="link-slide hover:text-white transition-colors duration-200">{{ $label }}</a>
            @endforeach
        </div>

        {{-- Desktop CTAs --}}
        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('superadmin.login') }}" class="text-sm text-white/50 hover:text-white transition-colors duration-200 px-3 py-1.5">
                Log in
            </a>
            <a href="{{ url('/register') }}?plan=free-trial"
               :class="compact ? 'px-4 py-2 text-xs' : 'px-5 py-2.5 text-sm'"
               class="ripple-btn font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white transition-all duration-300 shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/45 hover:-translate-y-0.5">
                Get started
            </a>
        </div>

        {{-- Mobile toggle --}}
        <button @click="open = !open" class="md:hidden p-2 text-white/60 hover:text-white transition-colors">
            <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg x-show="open"  class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="md:hidden bg-[#09090b]/98 backdrop-blur-xl border-t border-white/5 px-6 py-5 space-y-1">
        @foreach(['features' => 'Features', 'themes' => 'Themes', 'how-it-works' => 'How it works', 'pricing' => 'Pricing'] as $anchor => $label)
        <a href="#{{ $anchor }}" @click="open=false" class="block py-2.5 text-sm text-white/50 hover:text-white transition-colors">{{ $label }}</a>
        @endforeach
        <div class="pt-4 flex flex-col gap-3">
            <a href="{{ route('superadmin.login') }}" class="block text-center py-2.5 text-sm text-white/60 border border-white/10 rounded-lg hover:border-white/25 hover:text-white transition-all">Log in</a>
            <a href="{{ url('/register') }}?plan=free-trial" class="ripple-btn block text-center py-2.5 text-sm font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-all">Get started free</a>
        </div>
    </div>
</nav>
