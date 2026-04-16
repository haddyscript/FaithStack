<section class="py-20 px-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        @if(!empty($section['image']))
            <div class="order-last md:order-first">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                    <img src="{{ $section['image'] }}" alt="{{ $section['heading'] ?? '' }}"
                         class="w-full h-72 md:h-96 object-cover">
                    {{-- Gradient overlay --}}
                    <div class="absolute inset-0 opacity-20" style="background: linear-gradient(135deg, var(--primary), var(--secondary));"></div>
                </div>
            </div>
        @endif
        <div>
            @if(!empty($section['heading']))
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4 gradient-text">{{ $section['heading'] }}</h2>
            @endif
            @if(!empty($section['text']))
                <p class="text-gray-500 text-lg leading-relaxed">{!! nl2br(e($section['text'])) !!}</p>
            @endif
        </div>
    </div>
</section>
