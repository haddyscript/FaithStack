<section class="py-20 px-6">
    <div class="max-w-3xl mx-auto">
        @if(!empty($section['heading']))
            <h2 class="text-3xl md:text-4xl font-bold mb-6 gradient-text">{{ $section['heading'] }}</h2>
        @endif
        @if(!empty($section['content']))
            <div class="prose prose-lg text-gray-600 leading-relaxed">
                {!! nl2br(e($section['content'])) !!}
            </div>
        @endif
    </div>
</section>
