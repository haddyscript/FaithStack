{{-- Apex SaaS gallery — grid with hover zoom, glass card overlay --}}
@php
    $bg       = match($sectionConfig['background'] ?? 'primary') {
        'secondary' => 'var(--bg-sec)',
        'brand'     => 'var(--primary)',
        default     => 'var(--bg-pri)',
    };
    $textVal  = 'var(--text-pri)';
    $columns  = $sectionConfig['columns'] ?? 3;
    $gridCols = match($columns) { 2 => 'md:grid-cols-2', 4 => 'md:grid-cols-2 lg:grid-cols-4', default => 'md:grid-cols-3' };
    $spacing  = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-20', 'compact' => 'py-10', default => 'py-14' };

    $raw = $data['images'] ?? '';
    $images = is_array($raw)
        ? array_filter($raw)
        : array_filter(array_map('trim', preg_split('/[\n,]+/', $raw)));
@endphp
<section style="background:{{ $bg }}; border-top: 1px solid rgba(0,0,0,0.06);" class="{{ $spacing }}">
    <div class="max-w-7xl mx-auto px-6">
        @if(!empty($data['heading']))
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold" data-animate="fade-up" style="color:{{ $textVal }};">{{ $data['heading'] }}</h2>
            </div>
        @endif

        @if(count($images))
            <div class="grid {{ $gridCols }} gap-4" data-stagger>
                @foreach($images as $img)
                    <div class="group relative aspect-square rounded-2xl overflow-hidden shadow-md">
                        <img src="{{ $img }}" alt=""
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300"></div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="grid {{ $gridCols }} gap-4">
                @for($i = 0; $i < $columns * 2; $i++)
                    <div class="aspect-square rounded-2xl flex items-center justify-center font-bold text-2xl opacity-20"
                         style="background:linear-gradient(135deg,var(--primary),var(--secondary)); color:var(--text-inv);">
                        {{ chr(65 + $i) }}
                    </div>
                @endfor
            </div>
        @endif
    </div>
</section>
