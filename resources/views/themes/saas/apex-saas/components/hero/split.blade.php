@php
    $hero = collect($content['sections'] ?? [])->firstWhere('type', 'hero')['data'] ?? [];
    $spacing = match($config['section_spacing'] ?? 'spacious') {
        'spacious' => 'py-28',
        'compact'  => 'py-14',
        default    => 'py-20',
    };
@endphp

<section class="{{ $spacing }} relative overflow-hidden" style="background-color: var(--primary);">
    <div class="absolute inset-0 opacity-10"
         style="background: radial-gradient(ellipse at 80% 50%, var(--secondary), transparent 60%);"></div>

    <div class="relative max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
        {{-- Text --}}
        <div>
            @if(!empty($hero['badge']))
                <span class="inline-block text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full border border-white/20 text-white/70 mb-6">
                    {{ $hero['badge'] }}
                </span>
            @endif

            <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight mb-6">
                {!! nl2br(e($hero['title'] ?? $page->title)) !!}
            </h1>

            @if(!empty($hero['subtitle']))
                <p class="text-lg text-gray-300 mb-10 leading-relaxed">{{ $hero['subtitle'] }}</p>
            @endif

            <div class="flex flex-wrap gap-4">
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

        {{-- Visual --}}
        <div class="relative">
            <div class="w-full h-80 rounded-2xl border border-white/10 backdrop-blur-sm"
                 style="background: linear-gradient(135deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));">
                <div class="absolute inset-4 rounded-xl border border-white/5"
                     style="background: rgba(255,255,255,0.02);"></div>
                <div class="absolute bottom-8 left-8 right-8 h-2 rounded-full opacity-30"
                     style="background: linear-gradient(90deg, var(--secondary), transparent);"></div>
            </div>
        </div>
    </div>
</section>
