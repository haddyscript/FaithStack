@php
    $textColor = $sectionConfig['text_color'] ?? 'inverse';
    $textVal   = $textColor === 'inverse' ? 'var(--text-inv)' : 'var(--text-pri)';
    $subVal    = $textColor === 'inverse' ? 'rgba(255,255,255,0.75)' : 'var(--text-sec)';
    $spacing   = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-28', 'compact' => 'py-14', default => 'py-20' };
@endphp
<section class="{{ $spacing }} relative overflow-hidden" style="background:var(--primary);">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
        <div>
            @if(!empty($data['badge']))
                <span class="hero-enter hero-enter-1 text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full border border-current opacity-60 mb-6 inline-block" style="color:{{ $textVal }};">{{ $data['badge'] }}</span>
            @endif
            <h1 class="hero-enter hero-enter-2 text-4xl md:text-5xl font-bold leading-tight mb-6" style="color:{{ $textVal }};">
                {!! nl2br(e($data['title'] ?? $page->title)) !!}
            </h1>
            @if(!empty($data['subtitle']))
                <p class="hero-enter hero-enter-3 text-lg mb-10 leading-relaxed" style="color:{{ $subVal }};">{{ $data['subtitle'] }}</p>
            @endif
            <div class="hero-enter hero-enter-4 flex flex-wrap gap-4">
                @if(!empty($data['button_text']))
                    <a href="{{ $data['button_url'] ?? '#' }}"
                       class="btn-primary px-8 py-3.5 {{ $config['button_radius'] ?? 'rounded-lg' }} text-base font-semibold shadow-xl transition-all">
                        {{ $data['button_text'] }}
                    </a>
                @endif
            </div>
        </div>
        <div class="relative h-72 md:h-96 rounded-2xl overflow-hidden"
             style="background:linear-gradient(135deg,var(--secondary),var(--primary));">
            @if(!empty($data['image']))
                <img src="{{ $data['image'] }}" alt="" class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 flex items-center justify-center opacity-20">
                    <span class="text-6xl font-bold" style="color:var(--text-inv);">{{ substr($tenant->name, 0, 1) }}</span>
                </div>
            @endif
        </div>
    </div>
</section>
