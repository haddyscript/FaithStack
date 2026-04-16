{{--
  Modal component.
  Props: $id (unique id), $title, $size (sm|md|lg|xl)
  Usage:
    <x-admin.modal id="confirm-delete" title="Delete Page">
        ...content...
        <x-slot name="footer">
            ...buttons...
        </x-slot>
    </x-admin.modal>

  Trigger via: @click="$dispatch('open-modal', { id: 'confirm-delete' })"
--}}
@props([
    'id'    => 'modal',
    'title' => 'Modal',
    'size'  => 'md',
])

@php
$sizeMap = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
];
$maxW = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail.id === '{{ $id }}') open = true"
    x-on:close-modal.window="if ($event.detail.id === '{{ $id }}') open = false"
    x-show="open"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm"
    ></div>

    {{-- Panel --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            class="relative w-full {{ $maxW }} bg-white rounded-2xl shadow-xl ring-1 ring-gray-100 overflow-hidden"
            @click.stop
        >
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">{{ $title }}</h3>
                <button @click="open = false" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 text-sm text-gray-600">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @isset($footer)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
