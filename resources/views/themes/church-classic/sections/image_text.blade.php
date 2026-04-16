<section class="py-16 px-6">
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        @if(!empty($section['image']))
            <img src="{{ $section['image'] }}" alt="{{ $section['heading'] ?? '' }}"
                 class="rounded-xl shadow-md w-full object-cover">
        @endif
        <div>
            @if(!empty($section['heading']))
                <h2 class="text-2xl md:text-3xl font-bold text-primary mb-4">{{ $section['heading'] }}</h2>
            @endif
            @if(!empty($section['text']))
                <p class="text-gray-600 leading-relaxed">{{ $section['text'] }}</p>
            @endif
        </div>
    </div>
</section>
