@php
    $style = $sectionConfig['style'] ?? 'full';

    $bg = match($sectionConfig['background'] ?? 'brand') {
        'primary'   => 'var(--bg-pri)',
        'secondary' => 'var(--bg-sec)',
        default     => 'var(--primary)',  // 'brand'
    };
    $textColor = match($sectionConfig['text_color'] ?? 'inverse') {
        'primary'  => 'var(--text-pri)',
        'secondary'=> 'var(--text-sec)',
        default    => 'var(--text-inv)',
    };
    $subColor = ($sectionConfig['text_color'] ?? 'inverse') === 'inverse'
        ? 'rgba(255,255,255,0.75)'
        : 'var(--text-sec)';

    $spacing  = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-24', 'compact' => 'py-12', default => 'py-16' };
    $radius   = $config['button_radius'] ?? 'rounded-lg';
@endphp

@if($style === 'boxed')
<section style="background:var(--bg-sec);" class="{{ $spacing }}">
    <div class="max-w-4xl mx-auto px-6">
        <div class="rounded-2xl p-10 md:p-16 text-center" style="background:{{ $bg }};">
            @if(!empty($data['heading']))
                <h2 class="text-3xl md:text-4xl font-bold mb-4" data-animate="fade-up" style="color:{{ $textColor }};">{{ $data['heading'] }}</h2>
            @endif
            @if(!empty($data['subtext']))
                <p class="text-lg mb-10 max-w-xl mx-auto" data-animate="fade-up" data-delay="100" style="color:{{ $subColor }};">{{ $data['subtext'] }}</p>
            @endif
            @if(!empty($data['button_text']))
                <a href="{{ $data['button_url'] ?? '#' }}"
                   class="inline-block px-10 py-4 {{ $radius }} font-semibold text-base shadow-xl transition-all hover:opacity-90"
                   data-animate="fade-up" data-delay="200"
                   style="background:var(--secondary); color:var(--text-inv);">
                    {{ $data['button_text'] }}
                </a>
            @endif
        </div>
    </div>
</section>
@else
{{-- full-width style --}}
<section style="background:{{ $bg }};" class="{{ $spacing }} text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background:radial-gradient(circle at 60% 50%, var(--secondary), transparent 70%);"></div>
    <div class="relative max-w-4xl mx-auto px-6">
        @if(!empty($data['heading']))
            <h2 class="text-3xl md:text-5xl font-bold mb-5" data-animate="fade-up" style="color:{{ $textColor }};">{{ $data['heading'] }}</h2>
        @endif
        @if(!empty($data['subtext']))
            <p class="text-lg md:text-xl mb-10 max-w-2xl mx-auto" data-animate="fade-up" data-delay="120" style="color:{{ $subColor }};">{{ $data['subtext'] }}</p>
        @endif
        @if(!empty($data['button_text']))
            <a href="{{ $data['button_url'] ?? '#' }}"
               class="inline-block btn-primary px-10 py-4 {{ $radius }} font-semibold text-base shadow-xl transition-all"
               data-animate="scale-in" data-delay="200">
                {{ $data['button_text'] }}
            </a>
        @endif
    </div>
</section>
@endif
