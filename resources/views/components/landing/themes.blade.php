@props(['themes'])

<section id="themes" class="py-28 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Theme Library</p>
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5">
                Beautiful themes for<br>every organization
            </h2>
            <p class="text-lg text-slate-500 max-w-xl mx-auto">
                80+ professionally designed themes across 13 categories. Find yours, apply it instantly, and customize every detail.
            </p>
        </div>

        {{-- Theme category grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($themes as $theme)
            <div class="group relative rounded-2xl overflow-hidden cursor-pointer bg-white border border-slate-100 hover:border-slate-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">

                {{-- Preview area --}}
                <div class="relative h-52 bg-gradient-to-br {{ $theme['gradient'] }} overflow-hidden">

                    {{-- Decorative pattern overlay --}}
                    <div class="absolute inset-0 opacity-10"
                         style="background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px), linear-gradient(to right, rgba(255,255,255,.1) 1px, transparent 1px); background-size: 32px 32px;">
                    </div>

                    {{-- Mock browser UI --}}
                    <div class="absolute inset-4 rounded-xl overflow-hidden border border-white/20 bg-black/20 backdrop-blur-sm flex flex-col">
                        {{-- Browser bar --}}
                        <div class="flex items-center gap-1.5 px-3 py-2 bg-black/20 border-b border-white/10">
                            <span class="w-2 h-2 rounded-full bg-white/30"></span>
                            <span class="w-2 h-2 rounded-full bg-white/30"></span>
                            <span class="w-2 h-2 rounded-full bg-white/30"></span>
                            <div class="flex-1 mx-2 h-3 rounded bg-white/10"></div>
                        </div>

                        {{-- Simulated content --}}
                        <div class="flex-1 flex flex-col items-center justify-center gap-2 p-4">
                            <div class="text-2xl">{{ $theme['icon'] }}</div>
                            <div class="w-28 h-2 rounded-full bg-white/40"></div>
                            <div class="w-20 h-1.5 rounded-full bg-white/25"></div>
                            <div class="flex gap-2 mt-2">
                                <div class="w-12 h-4 rounded bg-white/30"></div>
                                <div class="w-12 h-4 rounded border border-white/30"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Count badge --}}
                    <div class="absolute top-3 right-3 bg-black/40 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-full border border-white/10">
                        {{ $theme['count'] }} themes
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-5">
                    <h3 class="font-semibold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">
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
        <div class="text-center mt-14">
            <p class="text-slate-500 mb-5">Plus Education, Health, Real Estate, Events, Travel, Beauty, Automotive & more</p>
            <a href="/superadmin/login"
               class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition-all shadow-lg shadow-slate-900/20 hover:-translate-y-0.5">
                Browse all themes
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

    </div>
</section>
