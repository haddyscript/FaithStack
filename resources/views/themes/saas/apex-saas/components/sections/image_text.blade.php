{{-- Apex SaaS image+text — alternating layout with rounded imagery --}}
@php
    $bg       = match($sectionConfig['background'] ?? 'secondary') {
        'secondary' => 'var(--bg-sec)',
        'brand'     => 'var(--primary)',
        default     => 'var(--bg-pri)',
    };
    $textVal  = 'var(--text-pri)';
    $subVal   = 'var(--text-sec)';
    $side     = $sectionConfig['image_side'] ?? 'right';
    $textOrd  = $side === 'right' ? 'order-1' : 'order-2 md:order-2';
    $imgOrd   = $side === 'right' ? 'order-2' : 'order-1 md:order-1';
    $spacing  = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-20', 'compact' => 'py-10', default => 'py-14' };
    $radius   = $config['button_radius'] ?? 'rounded-lg';
@endphp
<section style="background:{{ $bg }}; border-top: 1px solid rgba(0,0,0,0.06);" class="{{ $spacing }}">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-14 items-center">
        <div class="{{ $textOrd }}">
            @if(!empty($data['heading']))
                <h2 class="text-3xl md:text-4xl font-bold mb-5 tracking-tight" style="color:{{ $textVal }};">
                    {{ $data['heading'] }}
                </h2>
            @endif
            @if(!empty($data['text']))
                <p class="text-lg leading-relaxed mb-8" style="color:{{ $subVal }};">{{ $data['text'] }}</p>
            @endif
            @if(!empty($data['button_text']))
                <a href="{{ $data['button_url'] ?? '#' }}"
                   class="inline-block px-7 py-3 {{ $radius }} font-semibold text-sm shadow-sm transition-all hover:opacity-90"
                   style="background:var(--primary); color:var(--text-inv);">
                    {{ $data['button_text'] }}
                </a>
            @endif
        </div>
        <div class="{{ $imgOrd }} relative h-72 md:h-96 rounded-2xl overflow-hidden shadow-lg bg-gray-100">
            @if(!empty($data['image']))
                <img src="{{ $data['image'] }}" alt="{{ $data['heading'] ?? '' }}"
                     class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 flex items-center justify-center"
                     style="background:linear-gradient(135deg,var(--primary),var(--secondary));">
                    <span class="text-6xl font-bold opacity-20" style="color:var(--text-inv);">{{ substr($tenant->name,0,1) }}</span>
                </div>
            @endif
        </div>
    </div>
</section>
