{{--
  Stat card component for the admin dashboard.
  Props: $label, $value, $icon, $color (indigo|green|blue|amber|rose), $prefix, $suffix, $animated
--}}
@props([
    'label'    => 'Stat',
    'value'    => 0,
    'icon'     => 'chart',
    'color'    => 'indigo',
    'prefix'   => '',
    'suffix'   => '',
    'animated' => true,
])

@php
$colorMap = [
    'indigo' => ['bg' => 'bg-indigo-50', 'icon_bg' => 'bg-indigo-100', 'icon_text' => 'text-indigo-600', 'value_text' => 'text-indigo-700'],
    'green'  => ['bg' => 'bg-green-50',  'icon_bg' => 'bg-green-100',  'icon_text' => 'text-green-600',  'value_text' => 'text-green-700'],
    'blue'   => ['bg' => 'bg-blue-50',   'icon_bg' => 'bg-blue-100',   'icon_text' => 'text-blue-600',   'value_text' => 'text-blue-700'],
    'amber'  => ['bg' => 'bg-amber-50',  'icon_bg' => 'bg-amber-100',  'icon_text' => 'text-amber-600',  'value_text' => 'text-amber-700'],
    'rose'   => ['bg' => 'bg-rose-50',   'icon_bg' => 'bg-rose-100',   'icon_text' => 'text-rose-600',   'value_text' => 'text-rose-700'],
];
$c = $colorMap[$color] ?? $colorMap['indigo'];
$numericValue = (float) preg_replace('/[^0-9.]/', '', $value);
@endphp

<div
    x-data="{{ $animated ? "{ displayed: 0, target: {$numericValue}, prefix: '{$prefix}', suffix: '{$suffix}', run() { if(this.target === 0){ this.displayed = 0; return; } let start = 0; const step = this.target / 40; const interval = setInterval(() => { start += step; if(start >= this.target){ this.displayed = this.target; clearInterval(interval); } else { this.displayed = start; } }, 20); } }" : '{}' }}"
    {{ $animated ? 'x-init="run()"' : '' }}
    class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow duration-200 p-6 flex items-center gap-4"
>
    {{-- Icon --}}
    <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $c['icon_bg'] }} flex items-center justify-center">
        @if($icon === 'pages')
            <svg class="w-6 h-6 {{ $c['icon_text'] }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        @elseif($icon === 'check')
            <svg class="w-6 h-6 {{ $c['icon_text'] }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @elseif($icon === 'heart')
            <svg class="w-6 h-6 {{ $c['icon_text'] }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        @elseif($icon === 'currency')
            <svg class="w-6 h-6 {{ $c['icon_text'] }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @else
            <svg class="w-6 h-6 {{ $c['icon_text'] }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        @endif
    </div>

    {{-- Content --}}
    <div class="min-w-0 flex-1">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">{{ $label }}</p>
        @if($animated)
            <p class="text-2xl font-bold {{ $c['value_text'] }}" x-text="prefix + (Number.isInteger(target) ? Math.round(displayed).toLocaleString() : displayed.toFixed(2)) + suffix"></p>
        @else
            <p class="text-2xl font-bold {{ $c['value_text'] }}">{{ $prefix }}{{ $value }}{{ $suffix }}</p>
        @endif
    </div>

    {{-- Decorative gradient blob --}}
    <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full {{ $c['icon_bg'] }} opacity-50 pointer-events-none"></div>
</div>
