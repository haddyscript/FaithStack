@php
    $spacing = match($config['section_spacing'] ?? 'spacious') { 'spacious' => 'py-24', 'compact' => 'py-12', default => 'py-16' };
    $isLight = str_starts_with($config['primary_color'] ?? '', '#f') || in_array($config['primary_color'] ?? '', ['#ffffff', '#fafaf9', '#f8f8f8', '#fdf6ec', '#fce4ec', '#d6cfc4', '#f8fafc', '#f0f4ff', '#e8d5d0', '#b5a99a']);
    $headingColor = $isLight ? 'text-gray-900' : 'text-white';
    $bodyColor    = $isLight ? 'text-gray-600' : 'text-gray-400';
    $labelColor   = $isLight ? 'text-gray-500' : 'text-gray-400';
@endphp
<section class="{{ $spacing }}" style="background-color: var(--primary);">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div>
                @if(!empty($data['label']))
                    <span class="text-xs font-semibold tracking-widest uppercase {{ $labelColor }} mb-3 block opacity-70">{{ $data['label'] }}</span>
                @endif
                <h2 class="text-3xl md:text-4xl font-bold {{ $headingColor }} mb-6 leading-tight">{{ $data['heading'] ?? 'About Us' }}</h2>
                @if(!empty($data['body']))
                    <div class="{{ $bodyColor }} leading-relaxed space-y-4">
                        @foreach(explode("\n", $data['body']) as $paragraph)
                            @if(trim($paragraph))<p>{{ trim($paragraph) }}</p>@endif
                        @endforeach
                    </div>
                @endif
                @if(!empty($data['button_text']))
                    <div class="mt-8">
                        <a href="{{ $data['button_url'] ?? '#' }}"
                           class="btn-primary px-7 py-3 {{ $config['button_radius'] ?? 'rounded-lg' }} text-sm font-semibold inline-block transition-all">
                            {{ $data['button_text'] }}
                        </a>
                    </div>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach($data['stats'] ?? [] as $stat)
                    <div class="rounded-xl p-6 text-center border border-white/10 bg-white/5">
                        <div class="text-3xl font-bold {{ $headingColor }} mb-1">{{ $stat['value'] ?? '' }}</div>
                        <div class="{{ $bodyColor }} text-sm">{{ $stat['label'] ?? '' }}</div>
                    </div>
                @endforeach
                @if(empty($data['stats']))
                    <div class="col-span-2 h-48 rounded-xl border border-white/10 bg-white/5 flex items-center justify-center">
                        <span class="{{ $bodyColor }} text-sm opacity-50">{{ $tenant->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
