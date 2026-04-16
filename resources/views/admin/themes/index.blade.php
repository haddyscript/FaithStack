@extends('admin.layouts.app')

@section('title', 'Themes')
@section('heading', 'Theme Gallery')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <p class="text-gray-500 text-sm mt-1">Choose a theme that reflects your organization's style.</p>
    </div>
    @if($tenant->theme)
        <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-green-50 border border-green-200">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-semibold text-green-700">Active: {{ $tenant->theme->name }}</span>
        </div>
    @endif
</div>

{{-- Category tabs (Alpine.js) --}}
<div x-data="{ activeCategory: 'all' }">

    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
        <button @click="activeCategory = 'all'"
            :class="activeCategory === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600'"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150 whitespace-nowrap">
            All Themes
        </button>
        @foreach($themes->keys() as $category)
            <button @click="activeCategory = '{{ $category }}'"
                :class="activeCategory === '{{ $category }}' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150 whitespace-nowrap capitalize">
                {{ ucfirst($category) }}
                <span class="ml-1 opacity-60 text-xs">({{ $themes[$category]->count() }})</span>
            </button>
        @endforeach
    </div>

    {{-- Theme Grid --}}
    @foreach($themes as $category => $categoryThemes)
        <div
            x-show="activeCategory === 'all' || activeCategory === '{{ $category }}'"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            {{-- Category heading --}}
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest capitalize">{{ $category }}</h2>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                @foreach($categoryThemes as $theme)
                    @php $isActive = $tenant->theme_id === $theme->id; @endphp
                    <div
                        class="group relative bg-white rounded-2xl border shadow-sm overflow-hidden transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5
                            {{ $isActive ? 'border-indigo-400 ring-2 ring-indigo-500/20' : 'border-gray-100 hover:border-indigo-200' }}"
                    >
                        {{-- Preview image / placeholder --}}
                        <div class="relative h-40 overflow-hidden bg-gradient-to-br
                            @if($category === 'church') from-slate-700 via-slate-800 to-slate-900
                            @elseif($category === 'business') from-zinc-700 via-zinc-800 to-zinc-900
                            @else from-teal-700 via-teal-800 to-teal-900 @endif
                        ">
                            @if($theme->preview_image)
                                <img src="{{ asset('storage/' . $theme->preview_image) }}"
                                     alt="{{ $theme->name }} preview"
                                     class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-300">
                            @else
                                {{-- Decorative placeholder --}}
                                <div class="absolute inset-0 flex flex-col justify-between p-4 opacity-30">
                                    <div class="flex gap-2">
                                        <div class="h-2 w-16 rounded bg-white/60"></div>
                                        <div class="h-2 w-10 rounded bg-white/40"></div>
                                        <div class="h-2 w-12 rounded bg-white/40"></div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="h-4 w-3/4 rounded bg-white/70"></div>
                                        <div class="h-2 w-full rounded bg-white/40"></div>
                                        <div class="h-2 w-5/6 rounded bg-white/40"></div>
                                        <div class="h-8 w-24 rounded bg-white/50 mt-2"></div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="h-10 rounded bg-white/20"></div>
                                        <div class="h-10 rounded bg-white/20"></div>
                                        <div class="h-10 rounded bg-white/20"></div>
                                    </div>
                                </div>
                                {{-- Theme name overlay --}}
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-white/50 text-lg font-bold tracking-wide">{{ $theme->name }}</span>
                                </div>
                            @endif

                            {{-- Active badge --}}
                            @if($isActive)
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-600 text-white shadow-lg">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Active
                                    </span>
                                </div>
                            @endif

                            {{-- Category pill --}}
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-black/30 backdrop-blur-sm text-white/90 capitalize">
                                    {{ $theme->category }}
                                </span>
                            </div>
                        </div>

                        {{-- Card body --}}
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $theme->name }}</h3>
                                    @if($theme->config['description'] ?? null)
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $theme->config['description'] }}</p>
                                    @else
                                        <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ $theme->category }} theme</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Color swatches (if configured) --}}
                            @if($theme->config['colors'] ?? null)
                                <div class="flex gap-1.5 mb-3">
                                    @foreach(array_slice($theme->config['colors'], 0, 5) as $swatch)
                                        <div class="w-5 h-5 rounded-full border-2 border-white shadow-sm ring-1 ring-gray-200" style="background-color: {{ $swatch }};"></div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Activate button --}}
                            @if($isActive)
                                <div class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-indigo-50 text-indigo-600 text-sm font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Currently Active
                                </div>
                            @else
                                <form method="POST" action="{{ route('admin.themes.activate') }}">
                                    @csrf
                                    <input type="hidden" name="theme_id" value="{{ $theme->id }}">
                                    <button type="submit"
                                        class="w-full py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-150 group/btn">
                                        <span class="group-hover/btn:hidden">Activate Theme</span>
                                        <span class="hidden group-hover/btn:inline-flex items-center gap-1">
                                            Apply This Theme →
                                        </span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

</div>

@endsection
