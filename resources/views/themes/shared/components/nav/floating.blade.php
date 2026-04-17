@php $br = $tenant->getBranding(); $ctaText = $br['nav_cta_text']; $ctaUrl = $br['nav_cta_url']; @endphp
<header class="fixed top-4 inset-x-4 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between rounded-2xl border border-white/10 shadow-2xl backdrop-blur-md"
         style="background: rgba(0,0,0,0.5);">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            @if($tenant->logo)
                <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="h-7">
            @else
                <span class="text-lg font-bold text-white tracking-tight">{{ $tenant->name }}</span>
            @endif
        </a>
        <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-white/70">
            @foreach($navItems as $item)
                <a href="{{ $item->url }}" class="nav-link hover:text-white transition-colors">{{ $item->name }}</a>
            @endforeach
            <a href="{{ $ctaUrl }}"
               class="btn-primary px-5 py-2 {{ $config['button_radius'] ?? 'rounded-lg' }} text-sm font-semibold transition-all">
                {{ $ctaText }}
            </a>
        </nav>
        <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-white/70 hover:text-white">
            <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg x-show="mobileOpen"  class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div x-show="mobileOpen" x-transition class="mt-2 mx-auto rounded-2xl border border-white/10 px-6 py-4 space-y-3 backdrop-blur-md" style="background: rgba(0,0,0,0.7);">
        @foreach($navItems as $item)
            <a href="{{ $item->url }}" class="block text-sm font-medium text-white/80 hover:text-white">{{ $item->name }}</a>
        @endforeach
        <a href="{{ $ctaUrl }}" class="block text-center btn-primary px-5 py-2.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-sm font-semibold">{{ $ctaText }}</a>
    </div>
</header>
<div class="h-20"></div>{{-- spacer for fixed nav --}}
