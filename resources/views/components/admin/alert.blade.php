{{--
  Alert component with optional dismissal.
  Props: $type (success|error|warning|info), $dismissible (bool), $title
--}}
@props([
    'type'        => 'info',
    'dismissible' => true,
    'title'       => null,
])

@php
$map = [
    'success' => ['bg' => 'bg-green-50 border-green-200',  'icon_bg' => 'bg-green-100', 'icon' => 'text-green-500', 'text' => 'text-green-800', 'sub' => 'text-green-700'],
    'error'   => ['bg' => 'bg-red-50 border-red-200',      'icon_bg' => 'bg-red-100',   'icon' => 'text-red-500',   'text' => 'text-red-800',   'sub' => 'text-red-700'],
    'warning' => ['bg' => 'bg-yellow-50 border-yellow-200','icon_bg' => 'bg-yellow-100','icon' => 'text-yellow-500','text' => 'text-yellow-800','sub' => 'text-yellow-700'],
    'info'    => ['bg' => 'bg-blue-50 border-blue-200',    'icon_bg' => 'bg-blue-100',  'icon' => 'text-blue-500',  'text' => 'text-blue-800',  'sub' => 'text-blue-700'],
];
$c = $map[$type] ?? $map['info'];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="flex items-start gap-3 p-4 rounded-xl border {{ $c['bg'] }}"
    role="alert"
>
    {{-- Icon --}}
    <div class="flex-shrink-0 w-8 h-8 rounded-lg {{ $c['icon_bg'] }} flex items-center justify-center mt-0.5">
        @if($type === 'success')
            <svg class="w-4 h-4 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        @elseif($type === 'error')
            <svg class="w-4 h-4 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        @elseif($type === 'warning')
            <svg class="w-4 h-4 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        @else
            <svg class="w-4 h-4 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @endif
    </div>

    {{-- Text --}}
    <div class="flex-1 min-w-0">
        @if($title)
            <p class="text-sm font-semibold {{ $c['text'] }}">{{ $title }}</p>
        @endif
        <div class="text-sm {{ $title ? $c['sub'] . ' mt-0.5' : $c['text'] }}">{{ $slot }}</div>
    </div>

    {{-- Dismiss --}}
    @if($dismissible)
        <button @click="show = false" class="flex-shrink-0 p-1 rounded-md opacity-60 hover:opacity-100 {{ $c['icon'] }} transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    @endif
</div>
