<section class="py-20 px-6 bg-gray-50">
    <div class="max-w-6xl mx-auto">
        @if(!empty($section['heading']))
            <h2 class="text-3xl md:text-4xl font-extrabold text-center mb-10 gradient-text">{{ $section['heading'] }}</h2>
        @endif
        @php
            $images = is_array($section['images'] ?? null)
                ? $section['images']
                : array_filter(array_map('trim', explode("\n", $section['images'] ?? '')));
        @endphp
        @if(!empty($images))
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($images as $img)
                    <div class="group relative rounded-2xl overflow-hidden aspect-square shadow-md hover:shadow-xl transition-all duration-300">
                        <img src="{{ $img }}" alt="Gallery image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-30 transition-opacity duration-300" style="background: linear-gradient(135deg, var(--primary), var(--secondary));"></div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
