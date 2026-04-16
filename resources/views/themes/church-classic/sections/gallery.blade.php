@php
    $images = $section['images'] ?? [];
    if (is_string($images)) {
        $images = array_filter(array_map('trim', explode("\n", $images)));
    }
@endphp
<section class="py-16 px-6 bg-gray-50">
    <div class="max-w-5xl mx-auto">
        @if(!empty($section['heading']))
            <h2 class="text-2xl font-bold text-primary text-center mb-8">{{ $section['heading'] }}</h2>
        @endif
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($images as $image)
                <img src="{{ $image }}" alt="" class="w-full h-48 object-cover rounded-lg shadow-sm">
            @endforeach
        </div>
    </div>
</section>
