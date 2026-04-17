<header class="absolute top-0 inset-x-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            @if($tenant->logo)
                <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="h-8">
            @else
                <span class="text-xl font-bold text-white tracking-tight">{{ $tenant->name }}</span>
            @endif
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-white/80">
            @foreach($navItems as $item)
                <a href="{{ $item->url }}" class="hover:text-white transition-colors">{{ $item->name }}</a>
            @endforeach
            <a href="{{ route('donate') }}"
               class="border border-white/40 hover:border-white text-white px-5 py-2 {{ $config['button_radius'] ?? 'rounded-lg' }} text-sm font-semibold transition-all backdrop-blur-sm">
                Get Started
            </a>
        </nav>
        <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-white">
            <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg x-show="mobileOpen"  class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div x-show="mobileOpen" x-transition class="md:hidden px-6 py-4 space-y-3 bg-black/60 backdrop-blur-md">
        @foreach($navItems as $item)
            <a href="{{ $item->url }}" class="block text-sm font-medium text-white/80 hover:text-white">{{ $item->name }}</a>
        @endforeach
        <a href="{{ route('donate') }}" class="block text-center border border-white/40 text-white px-5 py-2.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-sm font-semibold">Get Started</a>
    </div>
</header>
