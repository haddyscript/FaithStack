@php
    $spacing = match($config['section_spacing'] ?? 'spacious') {
        'spacious' => 'py-24',
        'compact'  => 'py-12',
        default    => 'py-16',
    };
    $cardClass = match($config['card_style'] ?? 'glass') {
        'glass'   => 'bg-white/5 border border-white/10 backdrop-blur-sm',
        'flat'    => 'bg-gray-900',
        'shadow'  => 'bg-gray-900 shadow-xl shadow-black/40',
        'outline' => 'border border-gray-700',
        default   => 'bg-white/5 border border-white/10',
    };
    $items = $data['items'] ?? [];
@endphp

<section class="{{ $spacing }}" style="background-color: var(--primary);">
    <div class="max-w-7xl mx-auto px-6">
        @if(!empty($data['heading']))
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $data['heading'] }}</h2>
                @if(!empty($data['subheading']))
                    <p class="text-gray-400 max-w-2xl mx-auto text-lg">{{ $data['subheading'] }}</p>
                @endif
            </div>
        @endif

        @if(count($items))
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($items as $item)
                    <div class="{{ $cardClass }} rounded-xl p-6 transition-all duration-200 hover:-translate-y-1">
                        @if(!empty($item['icon']))
                            <div class="w-10 h-10 rounded-lg mb-4 flex items-center justify-center text-xl"
                                 style="background-color: var(--secondary); opacity: 0.85;">
                                {{ $item['icon'] }}
                            </div>
                        @endif
                        <h3 class="text-lg font-semibold text-white mb-2">{{ $item['title'] ?? '' }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $item['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
