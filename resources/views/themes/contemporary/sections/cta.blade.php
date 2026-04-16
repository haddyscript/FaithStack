<section class="py-24 px-6 relative overflow-hidden" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
    {{-- Decorative blobs --}}
    <div class="absolute -left-16 -top-16 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
    <div class="absolute -right-16 -bottom-16 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

    <div class="relative z-10 max-w-2xl mx-auto text-center text-white">
        @if(!empty($section['heading']))
            <h2 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight">{{ $section['heading'] }}</h2>
        @endif
        @if(!empty($section['subtext']))
            <p class="text-white/80 mb-10 text-lg md:text-xl">{{ $section['subtext'] }}</p>
        @endif
        @if(!empty($section['button_text']))
            <a href="{{ $section['button_url'] ?? '#' }}"
               class="inline-block bg-white px-10 py-4 rounded-2xl font-extrabold text-sm shadow-xl hover:shadow-2xl hover:-translate-y-0.5 transition-all duration-150"
               style="color: var(--primary)">
                {{ $section['button_text'] }}
            </a>
        @endif
    </div>
</section>
