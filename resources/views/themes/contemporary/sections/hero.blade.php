<section class="relative overflow-hidden py-28 px-6 text-center" style="background: linear-gradient(135deg, var(--dark) 0%, #1e1b4b 50%, #0f172a 100%);">
    {{-- Decorative orbs --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full opacity-10 blur-3xl pointer-events-none" style="background: var(--primary);"></div>
    <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full opacity-10 blur-3xl pointer-events-none" style="background: var(--secondary);"></div>

    <div class="relative z-10 max-w-4xl mx-auto">
        <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-6 gradient-text">
            {{ $section['title'] ?? '' }}
        </h1>
        @if(!empty($section['subtitle']))
            <p class="text-xl md:text-2xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ $section['subtitle'] }}
            </p>
        @endif
        @if(!empty($section['button_text']))
            <a href="{{ $section['button_url'] ?? '#' }}"
               class="btn-primary inline-block px-10 py-4 rounded-2xl font-bold text-base shadow-lg shadow-purple-500/25 transition-all duration-150">
                {{ $section['button_text'] }}
            </a>
        @endif
    </div>
</section>
