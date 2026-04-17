@props(['themes'])

<section id="themes" class="py-28 bg-slate-50">
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

        {{-- Theme grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($themes as $i => $theme)
            <div class="reveal theme-card group relative rounded-2xl overflow-hidden bg-white border border-slate-100 cursor-pointer"
                 data-delay="{{ $i + 1 }}">

                {{-- Preview area --}}
                <div class="relative h-52 overflow-hidden">
                    <div class="theme-preview-img absolute inset-0 bg-gradient-to-br {{ $theme['gradient'] }}">
                        {{-- Grid overlay --}}
                        <div class="absolute inset-0 opacity-10"
                             style="background-image:linear-gradient(rgba(255,255,255,.12) 1px,transparent 1px),linear-gradient(to right,rgba(255,255,255,.12) 1px,transparent 1px);background-size:28px 28px;">
                        </div>

                        {{-- Mock browser UI --}}
                        <div class="absolute inset-4 rounded-xl overflow-hidden border border-white/20 bg-black/20 backdrop-blur-sm flex flex-col">
                            <div class="flex items-center gap-1.5 px-3 py-2 bg-black/20 border-b border-white/10">
                                <span class="w-2 h-2 rounded-full bg-white/30"></span>
                                <span class="w-2 h-2 rounded-full bg-white/30"></span>
                                <span class="w-2 h-2 rounded-full bg-white/30"></span>
                                <div class="flex-1 mx-2 h-3 rounded bg-white/10"></div>
                            </div>
                            <div class="flex-1 flex flex-col items-center justify-center gap-2 p-4">
                                <div class="text-2xl">{{ $theme['icon'] }}</div>
                                <div class="w-28 h-2.5 rounded-full bg-white/50"></div>
                                <div class="w-20 h-2 rounded-full bg-white/30"></div>
                                <div class="flex gap-2 mt-2">
                                    <div class="w-14 h-5 rounded-lg bg-white/35"></div>
                                    <div class="w-14 h-5 rounded-lg border border-white/30"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hover overlay --}}
                    <div class="theme-overlay absolute inset-0 bg-black/60 flex items-center justify-center">
                        <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-slate-900 text-sm font-semibold shadow-xl transform group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Preview Theme
                        </span>
                    </div>

                    {{-- Count badge --}}
                    <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm text-white text-xs font-semibold px-2.5 py-1 rounded-full border border-white/10">
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
                        <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-xs font-medium">
                            {{ $tag }}
                        </span>
                        @endforeach
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div class="reveal text-center mt-14" data-delay="2">
            <p class="text-slate-400 mb-5 text-sm">Plus Education, Health, Real Estate, Events, Travel, Beauty, Automotive & more</p>
            <a href="/superadmin/login"
               class="ripple-btn inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition-all duration-300 shadow-lg shadow-slate-900/20 hover:-translate-y-0.5">
                Browse all themes
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

    </div>
</section>
