{{-- Apex SaaS CTA — full-width gradient band or boxed card --}}
@php
    $style    = $sectionConfig['style'] ?? 'full';
    $spacing  = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-24', 'compact' => 'py-12', default => 'py-16' };
    $radius   = $config['button_radius'] ?? 'rounded-lg';
@endphp

@if($style === 'boxed')
<section style="background:var(--bg-sec); border-top: 1px solid rgba(0,0,0,0.06);" class="{{ $spacing }}">
    <div class="max-w-4xl mx-auto px-6">
        <div class="rounded-2xl p-10 md:p-14 text-center relative overflow-hidden"
             style="background:var(--primary);">
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl opacity-20"
                 style="background:var(--secondary); transform: translate(30%, -30%);"></div>
            <div class="relative">
                @if(!empty($data['heading']))
                    <h2 class="text-3xl md:text-4xl font-bold mb-4" style="color:var(--text-inv);">{{ $data['heading'] }}</h2>
                @endif
                @if(!empty($data['subtext']))
                    <p class="text-lg mb-10 max-w-xl mx-auto" style="color:rgba(255,255,255,0.75);">{{ $data['subtext'] }}</p>
                @endif
                @if(!empty($data['button_text']))
                    <a href="{{ $data['button_url'] ?? '#' }}"
                       class="inline-block px-10 py-4 {{ $radius }} font-semibold text-base shadow-xl transition-all hover:opacity-90"
                       style="background:var(--secondary); color:var(--text-inv);">
                        {{ $data['button_text'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@else
<section class="{{ $spacing }} relative overflow-hidden text-center"
         style="background:linear-gradient(135deg,var(--primary),var(--secondary));">
    <div class="absolute inset-0 opacity-10"
         style="background:radial-gradient(circle at 70% 50%,#fff,transparent 60%);"></div>
    <div class="relative max-w-4xl mx-auto px-6">
        @if(!empty($data['heading']))
            <h2 class="text-3xl md:text-5xl font-bold mb-5" style="color:var(--text-inv);">{{ $data['heading'] }}</h2>
        @endif
        @if(!empty($data['subtext']))
            <p class="text-lg md:text-xl mb-10 max-w-2xl mx-auto" style="color:rgba(255,255,255,0.80);">{{ $data['subtext'] }}</p>
        @endif
        @if(!empty($data['button_text']))
            <a href="{{ $data['button_url'] ?? '#' }}"
               class="inline-block px-10 py-4 {{ $radius }} font-semibold text-base shadow-2xl transition-all hover:opacity-90"
               style="background:var(--text-inv); color:var(--primary);">
                {{ $data['button_text'] }}
            </a>
        @endif
    </div>
</section>
@endif
