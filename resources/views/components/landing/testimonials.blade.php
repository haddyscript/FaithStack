@props(['testimonials'])

<section id="testimonials" class="py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <p class="reveal text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Testimonials</p>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5" data-delay="1">
                Trusted by organizations<br>just like yours
            </h2>
        </div>

        {{-- Animated stat counters --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-20">
            @php
            $stats = [
                ['counter' => '500',  'suffix' => '+',   'label' => 'Organizations'],
                ['counter' => '80',   'suffix' => '+',   'label' => 'Themes available'],
                ['counter' => '9800', 'suffix' => '+',   'prefix' => '$', 'label' => 'Avg donations/mo'],
                ['counter' => '99.9', 'suffix' => '%',   'decimals' => '1', 'label' => 'Uptime SLA'],
            ];
            @endphp
            @foreach($stats as $i => $s)
            <div class="reveal text-center" data-delay="{{ $i + 1 }}">
                <div class="text-4xl font-bold text-slate-900 mb-1 tabular-nums">
                    <span data-counter="{{ $s['counter'] }}"
                          data-suffix="{{ $s['suffix'] ?? '' }}"
                          data-prefix="{{ $s['prefix'] ?? '' }}"
                          data-decimals="{{ $s['decimals'] ?? '0' }}">
                        {{ ($s['prefix'] ?? '') . number_format((float)$s['counter'], $s['decimals'] ?? 0) . ($s['suffix'] ?? '') }}
                    </span>
                </div>
                <div class="text-sm text-slate-400">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Real Clients --}}
        <div class="mb-20">

            {{-- Label --}}
            <div class="reveal text-center mb-12">
                <p class="inline-flex items-center gap-3 text-xs font-semibold text-slate-400 uppercase tracking-[0.2em]">
                    <span class="block w-10 h-px bg-gradient-to-r from-transparent to-slate-300"></span>
                    Live websites powered by FaithStack
                    <span class="block w-10 h-px bg-gradient-to-l from-transparent to-slate-300"></span>
                </p>
            </div>

            {{-- Cards --}}
            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">

                {{-- Card 1 — Johnny Davis Global Missions --}}
                <a href="https://johnnydavisglobalmissions.org/"
                   target="_blank" rel="noopener noreferrer"
                   class="reveal client-showcase-card group relative rounded-3xl overflow-hidden bg-white border border-slate-200/80 shadow-sm
                          hover:shadow-[0_20px_60px_-10px_rgba(99,102,241,0.18)] hover:border-indigo-300/60
                          hover:-translate-y-1.5 transition-all duration-500 ease-out"
                   data-delay="1">

                    {{-- Ambient glow (shown on hover via group) --}}
                    <div class="pointer-events-none absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                         style="background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(99,102,241,0.07), transparent 70%);"></div>

                    {{-- Browser mockup --}}
                    <div class="relative h-52 overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-violet-950 rounded-t-3xl">

                        {{-- Browser chrome bar --}}
                        <div class="absolute top-0 inset-x-0 z-10 h-8 bg-slate-800/95 backdrop-blur-sm flex items-center gap-1.5 px-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400/90"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400/90"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400/90"></span>
                            <div class="ml-2 flex-1 h-5 rounded-full bg-slate-700/70 flex items-center px-3 gap-1.5">
                                <svg class="w-2.5 h-2.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                                <span class="text-[9px] text-slate-400 font-mono truncate">johnnydavisglobalmissions.org</span>
                            </div>
                        </div>

                        {{-- Screenshot thumbnail --}}
                        <div class="absolute inset-x-0 bottom-0 top-8 overflow-hidden">
                            <img src="{{ asset('images/jdgm.png') }}"
                                 alt="Johnny Davis Global Missions website screenshot"
                                 class="w-full h-full object-cover object-top
                                        group-hover:scale-[1.06] transition-transform duration-700 ease-out">
                        </div>

                        {{-- Hover overlay with Visit CTA --}}
                        <div class="absolute inset-0 top-8 flex items-center justify-center
                                    bg-indigo-950/70 backdrop-blur-[2px]
                                    opacity-0 group-hover:opacity-100 transition-all duration-400">
                            <span class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-white text-indigo-700 text-xs font-bold shadow-2xl shadow-indigo-900/40
                                         translate-y-3 group-hover:translate-y-0 transition-transform duration-400 ease-out">
                                Visit Live Site
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </span>
                        </div>

                        {{-- Glowing bottom border that intensifies on hover --}}
                        <div class="absolute bottom-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent
                                    opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>

                    {{-- Card footer --}}
                    <div class="flex items-center justify-between px-6 py-5">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-md">JD</div>
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-900 group-hover:text-indigo-700 transition-colors duration-300 truncate">Johnny Davis Global Missions</div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse flex-shrink-0"></span>
                                    <span class="text-xs text-slate-400 truncate">johnnydavisglobalmissions.org</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-9 h-9 rounded-full border border-slate-200 group-hover:border-indigo-500 group-hover:bg-indigo-600
                                    flex items-center justify-center flex-shrink-0 ml-3
                                    transition-all duration-300 ease-out shadow-sm group-hover:shadow-indigo-500/30 group-hover:shadow-md">
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-white transition-colors duration-300"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Card 2 — Johnny Davis Ministries --}}
                <a href="http://johnnydavisministries.org/"
                   target="_blank" rel="noopener noreferrer"
                   class="reveal client-showcase-card group relative rounded-3xl overflow-hidden bg-white border border-slate-200/80 shadow-sm
                          hover:shadow-[0_20px_60px_-10px_rgba(16,185,129,0.18)] hover:border-emerald-300/60
                          hover:-translate-y-1.5 transition-all duration-500 ease-out"
                   data-delay="2">

                    {{-- Ambient glow --}}
                    <div class="pointer-events-none absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                         style="background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(16,185,129,0.07), transparent 70%);"></div>

                    {{-- Browser mockup --}}
                    <div class="relative h-52 overflow-hidden bg-gradient-to-br from-slate-900 via-emerald-950 to-teal-950 rounded-t-3xl">

                        {{-- Browser chrome --}}
                        <div class="absolute top-0 inset-x-0 z-10 h-8 bg-slate-800/95 backdrop-blur-sm flex items-center gap-1.5 px-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400/90"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400/90"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400/90"></span>
                            <div class="ml-2 flex-1 h-5 rounded-full bg-slate-700/70 flex items-center px-3 gap-1.5">
                                <svg class="w-2.5 h-2.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                                <span class="text-[9px] text-slate-400 font-mono truncate">johnnydavisministries.org</span>
                            </div>
                        </div>

                        {{-- Screenshot thumbnail --}}
                        <div class="absolute inset-x-0 bottom-0 top-8 overflow-hidden">
                            <img src="{{ asset('images/jdministries.png') }}"
                                 alt="Johnny Davis Ministries website screenshot"
                                 class="w-full h-full object-cover object-top
                                        group-hover:scale-[1.06] transition-transform duration-700 ease-out">
                        </div>

                        {{-- Hover overlay with Visit CTA --}}
                        <div class="absolute inset-0 top-8 flex items-center justify-center
                                    bg-emerald-950/70 backdrop-blur-[2px]
                                    opacity-0 group-hover:opacity-100 transition-all duration-400">
                            <span class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-white text-emerald-700 text-xs font-bold shadow-2xl shadow-emerald-900/40
                                         translate-y-3 group-hover:translate-y-0 transition-transform duration-400 ease-out">
                                Visit Live Site
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </span>
                        </div>

                        {{-- Glowing bottom border --}}
                        <div class="absolute bottom-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent
                                    opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>

                    {{-- Card footer --}}
                    <div class="flex items-center justify-between px-6 py-5">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-md">JD</div>
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-900 group-hover:text-emerald-700 transition-colors duration-300 truncate">Johnny Davis Ministries</div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse flex-shrink-0"></span>
                                    <span class="text-xs text-slate-400 truncate">johnnydavisministries.org</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-9 h-9 rounded-full border border-slate-200 group-hover:border-emerald-500 group-hover:bg-emerald-600
                                    flex items-center justify-center flex-shrink-0 ml-3
                                    transition-all duration-300 ease-out shadow-sm group-hover:shadow-emerald-500/30 group-hover:shadow-md">
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-white transition-colors duration-300"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        {{-- Cards --}}
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($testimonials as $i => $t)
            <div class="reveal testimonial-card group bg-slate-50 rounded-2xl p-8 border border-slate-100 cursor-default"
                 data-delay="{{ $i + 1 }}"
                 data-tilt="4">

                {{-- Quote mark --}}
                <div class="text-5xl font-serif text-indigo-200 leading-none mb-4 select-none group-hover:text-indigo-300 transition-colors duration-300">&ldquo;</div>

                {{-- Stars --}}
                <div class="flex gap-1 mb-5">
                    @for($s = 0; $s < 5; $s++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>

                <blockquote class="text-slate-600 leading-relaxed mb-7 text-[0.9375rem]">
                    "{{ $t['quote'] }}"
                </blockquote>

                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-full {{ $t['avatar_color'] }} flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-md">
                        {{ $t['initials'] }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-900">{{ $t['author'] }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">{{ $t['role'] }}</div>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>
