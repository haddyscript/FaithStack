{{--
  Badge component.
  Props: $color (green|yellow|red|blue|gray|indigo), $dot (bool)
--}}
@props([
    'color' => 'gray',
    'dot'   => false,
])

@php
$map = [
    'green'  => 'bg-green-100 text-green-700 ring-green-200',
    'yellow' => 'bg-yellow-100 text-yellow-700 ring-yellow-200',
    'red'    => 'bg-red-100 text-red-700 ring-red-200',
    'blue'   => 'bg-blue-100 text-blue-700 ring-blue-200',
    'indigo' => 'bg-indigo-100 text-indigo-700 ring-indigo-200',
    'gray'   => 'bg-gray-100 text-gray-600 ring-gray-200',
];
$dotMap = [
    'green'  => 'bg-green-500',
    'yellow' => 'bg-yellow-500',
    'red'    => 'bg-red-500',
    'blue'   => 'bg-blue-500',
    'indigo' => 'bg-indigo-500',
    'gray'   => 'bg-gray-400',
];
$cls = $map[$color] ?? $map['gray'];
$dotCls = $dotMap[$color] ?? $dotMap['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold ring-1 ring-inset {$cls}"]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotCls }}"></span>
    @endif
    {{ $slot }}
</span>
