<section class="bg-primary text-white py-24 px-6 text-center">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
            {{ $section['title'] ?? '' }}
        </h1>
        @if(!empty($section['subtitle']))
            <p class="text-lg md:text-xl opacity-80 mb-8">{{ $section['subtitle'] }}</p>
        @endif
        @if(!empty($section['button_text']))
            <a href="{{ $section['button_url'] ?? '#' }}"
               class="inline-block px-8 py-3 rounded-full font-semibold text-sm transition"
               style="background: var(--secondary); color: #1a1a1a;">
                {{ $section['button_text'] }}
            </a>
        @endif
    </div>
</section>
