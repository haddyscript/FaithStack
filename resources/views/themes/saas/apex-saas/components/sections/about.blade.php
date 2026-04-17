@php
    $spacing = match($config['section_spacing'] ?? 'spacious') {
        'spacious' => 'py-24',
        'compact'  => 'py-12',
        default    => 'py-16',
    };
@endphp

<section class="{{ $spacing }}" style="background-color: var(--primary);">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            {{-- Text content --}}
            <div>
                @if(!empty($data['label']))
                    <span class="text-xs font-semibold tracking-widest uppercase text-purple-400 mb-3 block">
                        {{ $data['label'] }}
                    </span>
                @endif
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">
                    {{ $data['heading'] ?? 'About Us' }}
                </h2>
                @if(!empty($data['body']))
                    <div class="text-gray-400 leading-relaxed space-y-4">
                        @foreach(explode("\n", $data['body']) as $paragraph)
                            @if(trim($paragraph))
                                <p>{{ trim($paragraph) }}</p>
                            @endif
                        @endforeach
                    </div>
                @endif
                @if(!empty($data['button_text']))
                    <div class="mt-8">
                        <a href="{{ $data['button_url'] ?? '#' }}"
                           class="btn-primary px-7 py-3 {{ $config['button_radius'] ?? 'rounded-lg' }} text-sm font-semibold inline-block transition-all duration-150">
                            {{ $data['button_text'] }}
                        </a>
                    </div>
                @endif
            </div>

            {{-- Stats or decorative block --}}
            <div class="grid grid-cols-2 gap-4">
                @foreach($data['stats'] ?? [] as $stat)
                    <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-center">
                        <div class="text-3xl font-bold text-white mb-1">{{ $stat['value'] ?? '' }}</div>
                        <div class="text-gray-400 text-sm">{{ $stat['label'] ?? '' }}</div>
                    </div>
                @endforeach

                @if(empty($data['stats']))
                    {{-- Placeholder visual if no stats provided --}}
                    <div class="col-span-2 h-48 rounded-xl border border-white/10 bg-white/5 flex items-center justify-center">
                        <span class="text-gray-600 text-sm">{{ $tenant->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
