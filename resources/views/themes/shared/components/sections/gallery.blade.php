@php
    $bg = match($sectionConfig['background'] ?? 'primary') {
        'secondary' => 'var(--bg-sec)',
        'brand'     => 'var(--primary)',
        default     => 'var(--bg-pri)',
    };
    $textColor = match($sectionConfig['text_color'] ?? 'primary') {
        'inverse' => 'var(--text-inv)',
        default   => 'var(--text-pri)',
    };
    $subColor  = match($sectionConfig['text_color'] ?? 'primary') {
        'inverse' => 'rgba(255,255,255,0.75)',
        default   => 'var(--text-sec)',
    };
    $columns = $sectionConfig['columns'] ?? 3;
    $gridCols = match($columns) { 2 => 'md:grid-cols-2', 4 => 'md:grid-cols-2 lg:grid-cols-4', default => 'md:grid-cols-3' };
    $spacing  = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-20', 'compact' => 'py-10', default => 'py-14' };

    // images field may be comma-separated URLs or an array
    $raw = $data['images'] ?? '';
    $images = is_array($raw)
        ? array_filter($raw)
        : array_filter(array_map('trim', preg_split('/[\n,]+/', $raw)));
@endphp
<section style="background:{{ $bg }};" class="{{ $spacing }}">
    <div class="max-w-7xl mx-auto px-6">
        @if(!empty($data['heading']))
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold" data-animate="fade-up" style="color:{{ $textColor }};">{{ $data['heading'] }}</h2>
            </div>
        @endif

        @if(count($images))
            <div class="grid {{ $gridCols }} gap-4" data-stagger>
                @foreach($images as $img)
                    <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100">
                        <img src="{{ $img }}" alt="" class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                @endforeach
            </div>
        @else
            {{-- Placeholder grid when no images are provided --}}
            <div class="grid {{ $gridCols }} gap-4">
                @for($i = 0; $i < $columns * 2; $i++)
                    <div class="aspect-square rounded-xl flex items-center justify-center text-2xl font-bold opacity-20"
                         style="background:var(--primary); color:var(--text-inv);">
                        {{ chr(65 + $i) }}
                    </div>
                @endfor
            </div>
        @endif
    </div>
</section>
