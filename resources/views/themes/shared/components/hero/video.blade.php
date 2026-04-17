@php
    $hero    = collect($content['sections'] ?? [])->firstWhere('type', 'hero')['data'] ?? [];
    $spacing = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-40', 'compact' => 'py-20', default => 'py-32' };
@endphp
<section class="{{ $spacing }} relative overflow-hidden" style="background-color: var(--primary);">
    {{-- Video background placeholder --}}
    @if(!empty($hero['video_url']))
        <video class="absolute inset-0 w-full h-full object-cover opacity-30" autoplay muted loop playsinline>
            <source src="{{ $hero['video_url'] }}" type="video/mp4">
        </video>
    @else
        <div class="absolute inset-0 opacity-20"
             style="background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 50%, #000 100%);"></div>
    @endif
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
        @if(!empty($hero['badge']))
            <span class="text-white text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full border border-white/30 mb-6 inline-block">{{ $hero['badge'] }}</span>
        @endif
        <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight mb-6">
            {!! nl2br(e($hero['title'] ?? $page->title)) !!}
        </h1>
        @if(!empty($hero['subtitle']))
            <p class="text-lg md:text-xl text-white/80 mb-10 max-w-2xl mx-auto leading-relaxed">{{ $hero['subtitle'] }}</p>
        @endif
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @if(!empty($hero['button_text']))
                <a href="{{ $hero['button_url'] ?? '#' }}"
                   class="btn-primary px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold shadow-xl transition-all">
                    {{ $hero['button_text'] }}
                </a>
            @endif
            @if(!empty($hero['secondary_button_text']))
                <a href="{{ $hero['secondary_button_url'] ?? '#' }}"
                   class="px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold border border-white/40 text-white hover:bg-white/10 transition-all">
                    {{ $hero['secondary_button_text'] }}
                </a>
            @endif
        </div>
    </div>
</section>
