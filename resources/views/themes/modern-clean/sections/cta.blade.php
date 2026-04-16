<section class="py-20 px-6 bg-accent text-white text-center">
    <div class="max-w-2xl mx-auto">
        @if(!empty($section['heading']))
            <h2 class="text-3xl font-bold mb-3">{{ $section['heading'] }}</h2>
        @endif
        @if(!empty($section['subtext']))
            <p class="text-white/80 mb-8 text-lg">{{ $section['subtext'] }}</p>
        @endif
        @if(!empty($section['button_text']))
            <a href="{{ $section['button_url'] ?? '#' }}"
               class="inline-block bg-white font-bold px-8 py-3 rounded-lg text-sm transition hover:bg-gray-100"
               style="color: var(--secondary)">
                {{ $section['button_text'] }}
            </a>
        @endif
    </div>
</section>
