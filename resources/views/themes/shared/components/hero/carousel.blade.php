@php
    $textColor = $sectionConfig['text_color'] ?? 'inverse';
    $textVal   = $textColor === 'inverse' ? 'var(--text-inv)' : 'var(--text-pri)';
    $subVal    = $textColor === 'inverse' ? 'rgba(255,255,255,0.75)' : 'var(--text-sec)';
    $spacing   = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-32', 'compact' => 'py-16', default => 'py-24' };
    $slides    = !empty($data['slides']) ? $data['slides'] : [['title' => $data['title'] ?? $page->title, 'subtitle' => $data['subtitle'] ?? '']];
@endphp
<section class="{{ $spacing }} relative overflow-hidden" style="background:var(--primary);"
         x-data="{ slide: 0, slides: {{ count($slides) }} }"
         x-init="setInterval(() => slide = (slide + 1) % slides, 5000)">
    <div class="absolute inset-0 opacity-10" style="background:radial-gradient(circle at 60% 40%,var(--secondary),transparent 70%);"></div>
    <div class="relative max-w-4xl mx-auto px-6 text-center">
        @foreach($slides as $i => $slide)
            <div x-show="slide === {{ $i }}"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6" style="color:{{ $textVal }};">
                    {!! nl2br(e($slide['title'] ?? '')) !!}
                </h1>
                @if(!empty($slide['subtitle']))
                    <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto leading-relaxed" style="color:{{ $subVal }};">{{ $slide['subtitle'] }}</p>
                @endif
            </div>
        @endforeach

        @if(!empty($data['button_text']))
            <a href="{{ $data['button_url'] ?? '#' }}"
               class="btn-primary inline-block px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold shadow-xl transition-all mt-4">
                {{ $data['button_text'] }}
            </a>
        @endif

        <div class="flex justify-center gap-2 mt-8">
            @foreach($slides as $i => $slide)
                <button @click="slide = {{ $i }}"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="slide === {{ $i }} ? 'w-6' : 'w-2'"
                        style="background:var(--text-inv); opacity: slide === {{ $i }} ? 1 : 0.4;"></button>
            @endforeach
        </div>
    </div>
</section>
