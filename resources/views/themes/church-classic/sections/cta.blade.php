<section class="py-16 px-6 text-center" style="background: var(--secondary);">
    <div class="max-w-2xl mx-auto">
        @if(!empty($section['heading']))
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ $section['heading'] }}</h2>
        @endif
        @if(!empty($section['subtext']))
            <p class="text-gray-700 mb-6">{{ $section['subtext'] }}</p>
        @endif
        @if(!empty($section['button_text']))
            <a href="{{ $section['button_url'] ?? '#' }}"
               class="inline-block bg-primary text-white px-8 py-3 rounded-full font-semibold text-sm hover:opacity-90 transition">
                {{ $section['button_text'] }}
            </a>
        @endif
    </div>
</section>
