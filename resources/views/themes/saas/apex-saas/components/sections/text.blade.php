{{-- Apex SaaS text section — clean white on dark or glass on dark depending on bg --}}
@php
    $bg       = match($sectionConfig['background'] ?? 'primary') {
        'secondary' => 'var(--bg-sec)',
        'brand'     => 'var(--primary)',
        default     => 'var(--bg-pri)',
    };
    $textVal  = 'var(--text-pri)';
    $subVal   = 'var(--text-sec)';
    $spacing  = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-20', 'compact' => 'py-10', default => 'py-14' };
    $align    = $sectionConfig['alignment'] ?? 'left';
    $maxW     = $align === 'center' ? 'max-w-3xl mx-auto text-center' : 'max-w-3xl';
@endphp
<section style="background:{{ $bg }}; border-top: 1px solid rgba(0,0,0,0.06);" class="{{ $spacing }}">
    <div class="max-w-6xl mx-auto px-6">
        <div class="{{ $maxW }}">
            @if(!empty($data['heading']))
                <h2 class="text-3xl md:text-4xl font-bold mb-5 tracking-tight" style="color:{{ $textVal }};">
                    {{ $data['heading'] }}
                </h2>
            @endif
            @if(!empty($data['content']))
                <div class="text-lg leading-relaxed space-y-4" style="color:{{ $subVal }};">
                    @foreach(explode("\n", $data['content']) as $para)
                        @if(trim($para))<p>{{ trim($para) }}</p>@endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
