@php
    $textColor = $sectionConfig['text_color'] ?? 'inverse';
    $textVal   = $textColor === 'inverse' ? 'var(--text-inv)' : 'var(--text-pri)';
    $subVal    = 'rgba(255,255,255,0.80)';
    $spacing   = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-40', 'compact' => 'py-20', default => 'py-32' };
    $alignment = $sectionConfig['alignment'] ?? 'center';
    $alignCls  = $alignment === 'center' ? 'text-center mx-auto' : '';
@endphp
<section class="{{ $spacing }} relative overflow-hidden" style="background:var(--primary);">
    @if(!empty($data['video_url']))
        <video class="absolute inset-0 w-full h-full object-cover opacity-30" autoplay muted loop playsinline>
            <source src="{{ $data['video_url'] }}" type="video/mp4">
        </video>
    @else
        <div class="absolute inset-0 opacity-20" style="background:linear-gradient(135deg,var(--secondary) 0%,var(--primary) 50%,#000 100%);"></div>
    @endif
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative max-w-4xl px-6 {{ $alignCls }}">
        @if(!empty($data['badge']))
            <span class="text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full border border-white/30 mb-6 inline-block" style="color:var(--text-inv);">{{ $data['badge'] }}</span>
        @endif
        <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6" style="color:var(--text-inv);">
            {!! nl2br(e($data['title'] ?? $page->title)) !!}
        </h1>
        @if(!empty($data['subtitle']))
            <p class="text-lg md:text-xl mb-10 max-w-2xl leading-relaxed {{ $alignment === 'center' ? 'mx-auto' : '' }}" style="color:{{ $subVal }};">{{ $data['subtitle'] }}</p>
        @endif
        <div class="flex flex-col sm:flex-row gap-4 {{ $alignment === 'center' ? 'justify-center' : '' }}">
            @if(!empty($data['button_text']))
                <a href="{{ $data['button_url'] ?? '#' }}"
                   class="btn-primary px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold shadow-xl transition-all">
                    {{ $data['button_text'] }}
                </a>
            @endif
            @if(!empty($data['secondary_button_text']))
                <a href="{{ $data['secondary_button_url'] ?? '#' }}"
                   class="px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold border border-white/40 hover:bg-white/10 transition-all"
                   style="color:var(--text-inv);">
                    {{ $data['secondary_button_text'] }}
                </a>
            @endif
        </div>
    </div>
</section>
