<header class="sticky top-0 z-50 bg-white border-b-2" style="border-color: var(--primary);">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            @if($tenant->logo)
                <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="h-8">
            @else
                <span class="text-xl font-bold" style="color: var(--primary);">{{ $tenant->name }}</span>
            @endif
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
            @foreach($navItems as $item)
                <a href="{{ $item->url }}" class="nav-link hover:text-gray-900 transition-colors">{{ $item->name }}</a>
            @endforeach
            <a href="{{ route('donate') }}"
               class="btn-primary px-5 py-2 {{ $config['button_radius'] ?? 'rounded-lg' }} text-sm font-semibold shadow transition-all text-white">
                Get Started
            </a>
        </nav>
        <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-gray-500 hover:text-gray-800">
            <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg x-show="mobileOpen"  class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div x-show="mobileOpen" x-transition class="md:hidden border-t border-gray-100 bg-white px-6 py-4 space-y-3">
        @foreach($navItems as $item)
            <a href="{{ $item->url }}" class="block text-sm font-medium text-gray-600 hover:text-gray-900">{{ $item->name }}</a>
        @endforeach
        <a href="{{ route('donate') }}" class="block text-center btn-primary px-5 py-2.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-sm font-semibold text-white">Get Started</a>
    </div>
</header>
