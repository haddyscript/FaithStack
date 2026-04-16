<section class="py-16 px-6 bg-gray-50">
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            @if(!empty($section['heading']))
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $section['heading'] }}</h2>
            @endif
            @if(!empty($section['text']))
                <p class="text-gray-500 leading-relaxed text-lg">{{ $section['text'] }}</p>
            @endif
        </div>
        @if(!empty($section['image']))
            <img src="{{ $section['image'] }}" alt="{{ $section['heading'] ?? '' }}"
                 class="rounded-2xl shadow-lg w-full object-cover">
        @endif
    </div>
</section>
