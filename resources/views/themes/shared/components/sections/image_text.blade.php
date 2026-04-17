@php
    $bg = match($sectionConfig['background'] ?? 'secondary') {
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
    $imageSide = $sectionConfig['image_side'] ?? 'right';
    $spacing   = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-20', 'compact' => 'py-10', default => 'py-14' };
    $textOrder = $imageSide === 'right' ? 'order-1' : 'order-2';
    $imgOrder  = $imageSide === 'right' ? 'order-2' : 'order-1';
@endphp
<section style="background:{{ $bg }};" class="{{ $spacing }}">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
        <div class="{{ $textOrder }}" data-animate="{{ $imageSide === 'right' ? 'slide-right' : 'slide-left' }}">
            @if(!empty($data['heading']))
                <h2 class="text-3xl md:text-4xl font-bold mb-5" style="color:{{ $textColor }};">
                    {{ $data['heading'] }}
                </h2>
            @endif
            @if(!empty($data['text']))
                <p class="text-lg leading-relaxed" style="color:{{ $subColor }};">{{ $data['text'] }}</p>
            @endif
        </div>
        <div class="{{ $imgOrder }} relative h-64 md:h-80 rounded-2xl overflow-hidden bg-gray-100 img-zoom" data-animate="{{ $imageSide === 'right' ? 'slide-left' : 'slide-right' }}">
            @if(!empty($data['image']))
                <img src="{{ $data['image'] }}" alt="{{ $data['heading'] ?? '' }}"
                     class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 flex items-center justify-center opacity-20"
                     style="background:var(--primary);">
                    <span class="text-5xl font-bold" style="color:var(--text-inv);">{{ substr($tenant->name, 0, 1) }}</span>
                </div>
            @endif
        </div>
    </div>
</section>
