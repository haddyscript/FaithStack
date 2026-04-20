@php
$items = [
    ['org' => 'Grace Community Church',  'action' => 'just launched their new website',    'time' => '2m ago',  'icon' => '⛪'],
    ['org' => 'Hope Foundation',          'action' => 'applied the Ocean theme',            'time' => '5m ago',  'icon' => '🌟'],
    ['org' => 'New Life Ministry',        'action' => 'received their first donation',      'time' => '8m ago',  'icon' => '💝'],
    ['org' => 'Riverside Church',         'action' => 'published a new sermon series',      'time' => '11m ago', 'icon' => '📖'],
    ['org' => 'Urban Reach Nonprofit',    'action' => 'went live with fundraising',         'time' => '14m ago', 'icon' => '🚀'],
    ['org' => 'Trinity Fellowship',       'action' => 'customized their homepage',          'time' => '17m ago', 'icon' => '✨'],
    ['org' => 'Community First Church',   'action' => 'added an events calendar',           'time' => '20m ago', 'icon' => '📅'],
    ['org' => 'Faith & Family Church',    'action' => 'reached 100 subscribers',            'time' => '23m ago', 'icon' => '🎉'],
];
@endphp

<div class="py-10 bg-slate-50 border-y border-slate-100 overflow-hidden">

    {{-- Label row --}}
    <div class="flex items-center gap-5 max-w-7xl mx-auto px-6 mb-5">
        <div class="flex items-center gap-2.5 flex-shrink-0">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
            </span>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Live Activity</span>
        </div>
        <div class="h-px flex-1 bg-gradient-to-r from-slate-200 to-transparent"></div>
        <span class="text-xs text-slate-300 flex-shrink-0">Updated in real-time</span>
    </div>

    {{-- Scrolling ticker --}}
    <div class="ticker-wrap overflow-hidden">
        <div class="ticker-track flex gap-4 w-max px-4">
            {{-- Duplicated for seamless loop --}}
            @foreach(array_merge($items, $items) as $item)
            <div class="flex items-center gap-3 bg-white rounded-2xl px-4 py-3 shadow-sm border border-slate-100/80 flex-shrink-0 select-none">
                <span class="text-xl leading-none">{{ $item['icon'] }}</span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-sm font-bold text-slate-800">{{ $item['org'] }}</span>
                    <span class="text-sm text-slate-500">{{ $item['action'] }}</span>
                </div>
                <span class="text-xs text-slate-300 ml-1 flex-shrink-0 font-medium">{{ $item['time'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>
