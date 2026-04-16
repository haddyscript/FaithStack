<section class="py-16 px-6">
    <div class="max-w-3xl mx-auto">
        @if(!empty($section['heading']))
            <h2 class="text-3xl font-bold text-gray-900 mb-5">{{ $section['heading'] }}</h2>
        @endif
        @if(!empty($section['content']))
            <div class="text-gray-600 leading-relaxed text-lg">
                {!! nl2br(e($section['content'])) !!}
            </div>
        @endif
    </div>
</section>
