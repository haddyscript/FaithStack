@php
    $hero    = collect($content['sections'] ?? [])->firstWhere('type', 'hero')['data'] ?? [];
    $spacing = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-28', 'compact' => 'py-14', default => 'py-20' };
    $isLight = str_starts_with($config['primary_color'] ?? '', '#f') || $config['primary_color'] === '#ffffff';
    $textColor = $isLight ? 'text-gray-900' : 'text-white';
    $subColor  = $isLight ? 'text-gray-600' : 'text-gray-300';
@endphp
<section class="{{ $spacing }} relative overflow-hidden" style="background-color: var(--primary);">
    <div class="absolute inset-0 opacity-10" style="background: radial-gradient(ellipse at 80% 50%, var(--secondary), transparent 60%);"></div>
    <div class="relative max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
        <div>
            @if(!empty($hero['badge']))
                <span class="{{ $textColor }} text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full border border-current opacity-60 mb-6 inline-block">{{ $hero['badge'] }}</span>
            @endif
            <h1 class="text-4xl md:text-5xl font-bold {{ $textColor }} leading-tight mb-6">
                {!! nl2br(e($hero['title'] ?? $page->title)) !!}
            </h1>
            @if(!empty($hero['subtitle']))
                <p class="text-lg {{ $subColor }} mb-10 leading-relaxed">{{ $hero['subtitle'] }}</p>
            @endif
            <div class="flex flex-wrap gap-4">
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
        <div class="relative h-72 md:h-80 rounded-2xl border border-white/10 overflow-hidden"
             style="background: linear-gradient(135deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));">
            <div class="absolute bottom-6 left-6 right-6 h-1.5 rounded-full opacity-40" style="background: linear-gradient(90deg, var(--secondary), transparent);"></div>
        </div>
    </div>
</section>
