@php
    $hero    = collect($content['sections'] ?? [])->firstWhere('type', 'hero')['data'] ?? [];
    $spacing = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-32', 'compact' => 'py-16', default => 'py-24' };
    $slides  = $hero['slides'] ?? [['title' => $hero['title'] ?? $page->title, 'subtitle' => $hero['subtitle'] ?? '']];
    $isLight = str_starts_with($config['primary_color'] ?? '', '#f') || $config['primary_color'] === '#ffffff';
    $textColor = $isLight ? 'text-gray-900' : 'text-white';
    $subColor  = $isLight ? 'text-gray-600' : 'text-white/70';
@endphp
<section class="{{ $spacing }} relative overflow-hidden" style="background-color: var(--primary);"
         x-data="{ slide: 0, slides: {{ count($slides) }} }"
         x-init="setInterval(() => slide = (slide + 1) % slides, 5000)">
    <div class="absolute inset-0 opacity-10" style="background: radial-gradient(circle at 60% 40%, var(--secondary), transparent 70%);"></div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
        @foreach($slides as $i => $slide)
            <div x-show="slide === {{ $i }}" x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <h1 class="text-4xl md:text-6xl font-bold {{ $textColor }} leading-tight mb-6">
                    {!! nl2br(e($slide['title'] ?? '')) !!}
                </h1>
                @if(!empty($slide['subtitle']))
                    <p class="text-lg md:text-xl {{ $subColor }} mb-10 max-w-2xl mx-auto leading-relaxed">{{ $slide['subtitle'] }}</p>
                @endif
            </div>
        @endforeach

        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-4">
            @if(!empty($hero['button_text']))
                <a href="{{ $hero['button_url'] ?? '#' }}"
                   class="btn-primary px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold shadow-xl transition-all">
                    {{ $hero['button_text'] }}
                </a>
            @endif
        </div>

        {{-- Dots --}}
        <div class="flex justify-center gap-2 mt-8">
            @foreach($slides as $i => $slide)
                <button @click="slide = {{ $i }}"
                        class="w-2 h-2 rounded-full transition-all"
                        :class="slide === {{ $i }} ? 'bg-white w-6' : 'bg-white/40'"></button>
            @endforeach
        </div>
    </div>
</section>
