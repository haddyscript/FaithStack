@php
    $hero    = collect($content['sections'] ?? [])->firstWhere('type', 'hero')['data'] ?? [];
    $spacing = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-32', 'compact' => 'py-16', default => 'py-24' };
    $isLight = in_array($config['nav_style'] ?? '', ['bordered']) || str_starts_with($config['primary_color'] ?? '', '#f');
    $textColor = $isLight ? 'text-gray-900' : 'text-white';
    $subColor  = $isLight ? 'text-gray-600' : 'text-white/70';
@endphp
<section class="{{ $spacing }} relative overflow-hidden" style="background-color: var(--primary);">
    <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full opacity-15 blur-3xl" style="background-color: var(--secondary);"></div>
    <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full opacity-10 blur-2xl"  style="background-color: var(--secondary);"></div>
    <div class="relative max-w-4xl mx-auto px-6 text-center">
        @if(!empty($hero['badge']))
            <span class="{{ $textColor }} text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full border border-current opacity-60 mb-6 inline-block">{{ $hero['badge'] }}</span>
        @endif
        <h1 class="text-4xl md:text-6xl font-bold {{ $textColor }} leading-tight mb-6">
            {!! nl2br(e($hero['title'] ?? $page->title)) !!}
        </h1>
        @if(!empty($hero['subtitle']))
            <p class="text-lg md:text-xl {{ $subColor }} mb-10 max-w-2xl mx-auto leading-relaxed">{{ $hero['subtitle'] }}</p>
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
                   class="px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold border border-current {{ $textColor }} opacity-80 hover:opacity-100 transition-all">
                    {{ $hero['secondary_button_text'] }}
                </a>
            @endif
        </div>
    </div>
</section>
