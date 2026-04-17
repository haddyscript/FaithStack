@php
    $bg = match($sectionConfig['background'] ?? 'primary') {
        'secondary' => 'var(--bg-sec)',
        'brand'     => 'var(--primary)',
        default     => 'var(--bg-pri)',
    };
    $textColor = match($sectionConfig['text_color'] ?? 'primary') {
        'inverse'   => 'var(--text-inv)',
        'secondary' => 'var(--text-sec)',
        default     => 'var(--text-pri)',
    };
    $subColor = match($sectionConfig['text_color'] ?? 'primary') {
        'inverse' => 'rgba(255,255,255,0.75)',
        default   => 'var(--text-sec)',
    };
    $align     = $sectionConfig['alignment'] ?? 'left';
    $spacing   = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-20', 'compact' => 'py-10', default => 'py-14' };
    $textAlign = $align === 'center' ? 'text-center mx-auto' : '';
@endphp
<section style="background:{{ $bg }};" class="{{ $spacing }}">
    <div class="max-w-4xl mx-auto px-6 {{ $textAlign }}">
        @if(!empty($data['heading']))
            <h2 class="text-3xl md:text-4xl font-bold mb-6" style="color:{{ $textColor }};">
                {{ $data['heading'] }}
            </h2>
        @endif
        @if(!empty($data['content']))
            <div class="prose prose-lg max-w-none leading-relaxed" style="color:{{ $subColor }};">
                {!! nl2br(e($data['content'])) !!}
            </div>
        @endif
    </div>
</section>
