@props(['themes'])

<section id="themes" class="py-28 bg-slate-50" x-data="themeGallery">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <p class="reveal text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Theme Library</p>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5" data-delay="1">
                Beautiful themes for<br>every organization
            </h2>
            <p class="reveal text-lg text-slate-500 max-w-xl mx-auto" data-delay="2">
                80+ professionally designed themes across 13 categories. Find yours, apply it instantly, and customize every detail.
            </p>
        </div>

        {{-- Grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($themes as $i => $theme)
            @php $themeJson = json_encode($theme); @endphp
            <div class="reveal theme-card group relative rounded-2xl overflow-hidden bg-white border border-slate-100 cursor-pointer select-none"
                 data-delay="{{ $i + 1 }}"
                 data-tilt="6"
                 @click="open({{ $themeJson }})">

                {{-- Preview --}}
                <div class="relative h-56 overflow-hidden">
                    <div class="theme-preview-img absolute inset-0 bg-gradient-to-br {{ $theme['gradient'] }}">
                        <div class="absolute inset-0 opacity-[0.09]"
                             style="background-image:linear-gradient(rgba(255,255,255,.15) 1px,transparent 1px),linear-gradient(to right,rgba(255,255,255,.15) 1px,transparent 1px);background-size:24px 24px;"></div>

                        {{-- Mock browser --}}
                        <div class="absolute inset-4 rounded-xl overflow-hidden border border-white/20 bg-black/20 backdrop-blur-sm flex flex-col">
                            <div class="flex items-center gap-1.5 px-3 py-2 bg-black/20 border-b border-white/10 flex-shrink-0">
                                <span class="w-2 h-2 rounded-full bg-white/30"></span>
                                <span class="w-2 h-2 rounded-full bg-white/30"></span>
                                <span class="w-2 h-2 rounded-full bg-white/30"></span>
                                <div class="flex-1 mx-2 h-2.5 rounded bg-white/10"></div>
                            </div>
                            <div class="flex-1 flex flex-col items-center justify-center gap-2 p-4">
                                <div class="text-2xl">{{ $theme['icon'] }}</div>
                                <div class="w-28 h-2.5 rounded-full bg-white/55"></div>
                                <div class="w-20 h-2 rounded-full bg-white/30"></div>
                                <div class="flex gap-2 mt-2">
                                    <div class="w-14 h-5 rounded-lg bg-white/35"></div>
                                    <div class="w-14 h-5 rounded-lg border border-white/30"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Overlay --}}
                    <div class="theme-overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-black/20 flex flex-col items-center justify-center gap-3">
                        <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-slate-900 text-sm font-semibold shadow-2xl transform group-hover:scale-105 group-hover:-translate-y-0.5 transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Preview Theme
                        </span>
                        <span class="text-white/60 text-xs">{{ $theme['count'] }} themes in this category</span>
                    </div>

                    {{-- Count badge --}}
                    <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-md text-white text-xs font-semibold px-2.5 py-1 rounded-full border border-white/10">
                        {{ $theme['count'] }} themes
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-5">
                    <h3 class="font-semibold text-slate-900 mb-2.5 group-hover:text-indigo-600 transition-colors duration-200">
                        {{ $theme['name'] }}
                    </h3>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($theme['tags'] as $tag)
                        <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 group-hover:bg-indigo-50 text-slate-500 group-hover:text-indigo-600 text-xs font-medium transition-colors duration-200">
                            {{ $tag }}
                        </span>
                        @endforeach
                    </div>
                </div>

                {{-- Mouse-tracking glare overlay (JS-driven via [data-tilt] handler) --}}
                <div class="tilt-glare absolute inset-0 pointer-events-none rounded-2xl" style="z-index:5;"></div>

            </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div class="reveal text-center mt-14" data-delay="2">
            <p class="text-slate-400 mb-5 text-sm">Plus Education, Health, Real Estate, Events, Travel, Beauty, Automotive & more</p>
            <a href="{{ url('/register') }}?plan=free-trial"
               class="ripple-btn inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition-all duration-300 shadow-lg shadow-slate-900/15 hover:-translate-y-0.5">
                Browse all themes
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

    </div>

    {{-- ── Preview Modal ── --}}
    <div x-cloak
         x-show="active"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-180"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="close()"
         @keydown.escape.window="close()"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md">

        <div x-show="active"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-3xl bg-[#0f0f11] rounded-3xl border border-white/10 shadow-[0_60px_120px_rgba(0,0,0,0.8)] overflow-hidden">

            {{-- Modal header --}}
            <div class="flex items-center justify-between px-7 py-5 border-b border-white/[0.06]">
                <div>
                    <h3 class="text-white font-bold text-lg" x-text="active?.name"></h3>
                    <p class="text-white/40 text-xs mt-0.5" x-text="(active?.count || 0) + ' themes in this category'"></p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ url('/register') }}?plan=free-trial"
                       class="ripple-btn inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold transition-colors">
                        Use this theme
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <button @click="close()" class="w-8 h-8 rounded-lg border border-white/10 flex items-center justify-center text-white/40 hover:text-white hover:border-white/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Live preview (scrollable mock page) --}}
            <div class="relative overflow-hidden" style="height: 440px;">

                {{-- Gradient preview bg --}}
                <div class="absolute inset-0" :class="active ? 'bg-gradient-to-br ' + active.gradient : ''"></div>
                <div class="absolute inset-0 opacity-[0.07]"
                     style="background-image:linear-gradient(rgba(255,255,255,.1) 1px,transparent 1px),linear-gradient(to right,rgba(255,255,255,.1) 1px,transparent 1px);background-size:32px 32px;"></div>

                {{-- Scrollable mock page content --}}
                <div class="absolute inset-x-8 top-6 bottom-0 overflow-y-auto rounded-t-xl border border-white/15 bg-black/25 backdrop-blur-sm">

                    {{-- Mock nav --}}
                    <div class="sticky top-0 flex items-center justify-between px-4 py-2.5 bg-black/40 backdrop-blur-md border-b border-white/10">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded bg-white/30"></div>
                            <div class="w-16 h-2 rounded-full bg-white/30"></div>
                        </div>
                        <div class="flex gap-2">
                            <div class="w-7 h-1.5 rounded-full bg-white/15"></div>
                            <div class="w-7 h-1.5 rounded-full bg-white/15"></div>
                            <div class="w-7 h-1.5 rounded-full bg-white/15"></div>
                        </div>
                        <div class="w-14 h-5 rounded-md bg-white/25"></div>
                    </div>

                    {{-- Mock hero --}}
                    <div class="flex flex-col items-center justify-center gap-2.5 py-10 px-8">
                        <div class="text-3xl" x-text="active?.icon"></div>
                        <div class="w-56 h-3.5 rounded-full bg-white/60"></div>
                        <div class="w-44 h-3.5 rounded-full bg-white/40"></div>
                        <div class="w-36 h-2.5 rounded-full bg-white/25 mt-1"></div>
                        <div class="flex gap-2 mt-3">
                            <div class="w-20 h-7 rounded-lg bg-white/35"></div>
                            <div class="w-20 h-7 rounded-lg border border-white/25"></div>
                        </div>
                    </div>

                    {{-- Mock feature section --}}
                    <div class="px-6 pb-6">
                        <div class="w-32 h-2 rounded-full bg-white/40 mx-auto mb-4"></div>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach(range(1,6) as $n)
                            <div class="rounded-xl bg-white/10 p-3">
                                <div class="w-6 h-6 rounded-lg bg-white/20 mb-2"></div>
                                <div class="w-full h-1.5 rounded-full bg-white/25 mb-1.5"></div>
                                <div class="w-3/4 h-1.5 rounded-full bg-white/15"></div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Mock testimonial --}}
                    <div class="mx-6 mb-6 p-4 rounded-xl bg-white/10 border border-white/10">
                        <div class="flex gap-1 mb-2">
                            @for($s=0;$s<5;$s++)<div class="w-3 h-3 rounded-sm bg-amber-400/70"></div>@endfor
                        </div>
                        <div class="space-y-1.5 mb-3">
                            <div class="w-full h-1.5 rounded-full bg-white/25"></div>
                            <div class="w-5/6 h-1.5 rounded-full bg-white/20"></div>
                            <div class="w-4/6 h-1.5 rounded-full bg-white/15"></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-white/30"></div>
                            <div class="w-20 h-1.5 rounded-full bg-white/25"></div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Tags row --}}
            <div class="flex items-center gap-2 px-7 py-4 border-t border-white/[0.06]">
                <span class="text-xs text-white/30 mr-1">Includes:</span>
                <template x-for="tag in active?.tags" :key="tag">
                    <span class="inline-block px-2.5 py-0.5 rounded-md bg-white/8 text-white/50 text-xs font-medium border border-white/8" x-text="tag"></span>
                </template>
            </div>

        </div>
    </div>

</section>
