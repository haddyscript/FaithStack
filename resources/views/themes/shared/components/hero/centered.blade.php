@php
    // $data = the hero section (from page content)
    $textColor = $sectionConfig['text_color'] ?? 'inverse';
    $alignment = $sectionConfig['alignment']  ?? 'center';
    $bgType    = $sectionConfig['background'] ?? 'gradient';
    $textVal   = $textColor === 'inverse' ? 'var(--text-inv)' : 'var(--text-pri)';
    $subVal    = $textColor === 'inverse' ? 'rgba(255,255,255,0.75)' : 'var(--text-sec)';
    $spacing   = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-32', 'compact' => 'py-16', default => 'py-24' };
    $alignCls  = $alignment === 'center' ? 'text-center mx-auto' : '';
@endphp
<section class="{{ $spacing }} relative overflow-hidden" style="background:var(--primary);">
    @if($bgType === 'gradient')
        <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full opacity-15 blur-3xl" style="background:var(--secondary);"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full opacity-10 blur-2xl"  style="background:var(--secondary);"></div>
    @endif
    <div class="relative max-w-4xl px-6 {{ $alignCls }}">
        @if(!empty($data['button_text']) && empty($data['title']))
            {{-- badge only --}}
        @endif
        @if(!empty($data['badge']))
            <span class="text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full border border-current opacity-60 mb-6 inline-block" style="color:{{ $textVal }};">{{ $data['badge'] }}</span>
        @endif
        <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6" style="color:{{ $textVal }};">
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
                   class="px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold border border-current opacity-80 hover:opacity-100 transition-all"
                   style="color:{{ $textVal }};">
                    {{ $data['secondary_button_text'] }}
                </a>
            @endif
        </div>
    </div>
</section>
