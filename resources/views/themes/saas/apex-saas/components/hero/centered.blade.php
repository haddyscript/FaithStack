@php
    $hero = collect($content['sections'] ?? [])->firstWhere('type', 'hero')['data'] ?? [];
    $spacing = match($config['section_spacing'] ?? 'spacious') {
        'spacious' => 'py-32',
        'compact'  => 'py-16',
        default    => 'py-24',
    };
@endphp

<section class="{{ $spacing }} relative overflow-hidden" style="background-color: var(--primary);">
    {{-- Decorative glow orbs --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full opacity-20 blur-3xl"
         style="background-color: var(--secondary);"></div>
    <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full opacity-10 blur-2xl"
         style="background-color: var(--secondary);"></div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
        @if(!empty($hero['badge']))
            <span class="inline-block text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full border border-white/20 text-white/70 mb-6">
                {{ $hero['badge'] }}
            </span>
        @endif

        <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight mb-6">
            {!! nl2br(e($hero['title'] ?? $page->title)) !!}
        </h1>

        @if(!empty($hero['subtitle']))
            <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ $hero['subtitle'] }}
            </p>
        @endif

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @if(!empty($hero['button_text']))
                <a href="{{ $hero['button_url'] ?? '#' }}"
                   class="btn-primary px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold shadow-xl transition-all duration-150">
                    {{ $hero['button_text'] }}
                </a>
            @endif
            @if(!empty($hero['secondary_button_text']))
                <a href="{{ $hero['secondary_button_url'] ?? '#' }}"
                   class="px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold border border-white/30 text-white hover:bg-white/10 transition-all duration-150">
                    {{ $hero['secondary_button_text'] }}
                </a>
            @endif
        </div>
    </div>
</section>
