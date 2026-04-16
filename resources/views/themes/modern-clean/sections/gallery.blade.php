@php
    $images = $section['images'] ?? [];
    if (is_string($images)) {
        $images = array_filter(array_map('trim', explode("\n", $images)));
    }
@endphp
<section class="py-16 px-6">
    <div class="max-w-5xl mx-auto">
        @if(!empty($section['heading']))
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-10">{{ $section['heading'] }}</h2>
        @endif
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($images as $image)
                <img src="{{ $image }}" alt=""
                     class="w-full h-52 object-cover rounded-xl hover:scale-105 transition-transform duration-300">
            @endforeach
        </div>
    </div>
</section>
