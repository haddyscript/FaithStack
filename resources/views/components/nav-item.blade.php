{{--
  Nav item component — works in both tenant admin and superadmin sidebars.
  Props: $href, $label, $icon (SVG path d="..."), $active (bool), $badge (optional count)
  Expects parent x-data to expose: sidebarOpen (bool)
--}}
@props([
    'href'   => '#',
    'label'  => '',
    'icon'   => '',
    'active' => false,
    'badge'  => null,
])

<div class="relative group/navitem">
    <a href="{{ $href }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ $active
                  ? 'bg-white/15 text-white shadow-sm'
                  : 'text-slate-400 hover:bg-white/10 hover:text-white' }}"
       :class="sidebarOpen ? '' : 'justify-center px-0'"
    >
        {{-- Icon --}}
        <div class="relative flex-shrink-0">
            <svg class="w-5 h-5 {{ $active ? 'text-white' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
            </svg>
            {{-- Active indicator dot --}}
            @if($active)
                <span class="absolute -top-0.5 -right-0.5 w-1.5 h-1.5 rounded-full bg-indigo-400 ring-1 ring-slate-900"></span>
            @endif
        </div>

        {{-- Label --}}
        <span class="truncate transition-all duration-200 flex-1"
              :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden pointer-events-none'">
            {{ $label }}
        </span>

        {{-- Badge --}}
        @if($badge)
            <span class="flex-shrink-0 text-xs font-bold px-1.5 py-0.5 rounded-full bg-indigo-500/30 text-indigo-300"
                  :class="sidebarOpen ? '' : 'hidden'">
                {{ $badge }}
            </span>
        @endif
    </a>

    {{-- Tooltip (visible only when collapsed) --}}
    <div class="absolute left-full top-1/2 -translate-y-1/2 ml-3 z-50 pointer-events-none
                opacity-0 group-hover/navitem:opacity-100 transition-opacity duration-150"
         :class="sidebarOpen ? 'hidden' : ''">
        <div class="flex items-center gap-1.5 whitespace-nowrap bg-gray-900 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-xl ring-1 ring-white/10">
            {{ $label }}
            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-gray-900"></div>
        </div>
    </div>
</div>
