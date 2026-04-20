<section id="build-preview" class="py-28 bg-[#09090b] overflow-hidden relative">

    {{-- Grid bg --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff04_1px,transparent_1px),linear-gradient(to_bottom,#ffffff04_1px,transparent_1px)] bg-[size:48px_48px] pointer-events-none"></div>

    {{-- Ambient glow --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div x-data="buildPreview" class="relative max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-16">
            <div class="reveal inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-white/10 bg-white/[0.04] text-xs text-white/55 mb-8 font-medium">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-400"></span>
                </span>
                Interactive Preview — Try it now
            </div>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-white tracking-tight mb-5" data-delay="1">
                See your site before<br>
                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">you sign up</span>
            </h2>
            <p class="reveal text-lg text-white/40 max-w-xl mx-auto" data-delay="2">
                Pick a style and organization type. Your website preview updates instantly.
            </p>
        </div>

        <div class="reveal grid lg:grid-cols-[300px,1fr] gap-10 items-start" data-delay="1">

            {{-- ── Controls panel ── --}}
            <div class="space-y-5 lg:sticky lg:top-24">

                {{-- Style selector --}}
                <div class="bg-white/[0.04] border border-white/[0.07] rounded-2xl p-5">
                    <p class="text-[10px] font-bold text-white/30 uppercase tracking-widest mb-3.5">Style</p>
                    <div class="space-y-2">
                        @foreach([
                            ['dark',    '#09090b', 'Dark Mode',     'Bold, modern look'],
                            ['light',   '#f8fafc', 'Light & Clean', 'Bright, professional'],
                            ['minimal', '#ffffff', 'Minimal',       'Clean, distraction-free'],
                        ] as [$id, $color, $label, $desc])
                        <button @click="setTheme('{{ $id }}')"
                                :class="theme === '{{ $id }}' ? 'border-indigo-500/70 bg-indigo-500/10' : 'border-white/[0.07] hover:border-white/15 hover:bg-white/[0.03]'"
                                class="w-full flex items-center gap-3 p-3 rounded-xl border transition-all duration-200 text-left cursor-pointer group">
                            <div class="w-7 h-7 rounded-lg flex-shrink-0 border border-white/10 shadow-inner" style="background: {{ $color }}"></div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-white leading-none mb-0.5">{{ $label }}</div>
                                <div class="text-[11px] text-white/30">{{ $desc }}</div>
                            </div>
                            <div :class="theme === '{{ $id }}' ? 'opacity-100 scale-100' : 'opacity-0 scale-75'"
                                 class="w-4 h-4 rounded-full bg-indigo-500 flex items-center justify-center transition-all duration-200 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Type selector --}}
                <div class="bg-white/[0.04] border border-white/[0.07] rounded-2xl p-5">
                    <p class="text-[10px] font-bold text-white/30 uppercase tracking-widest mb-3.5">Organization Type</p>
                    <div class="space-y-2">
                        @foreach([
                            ['church',    '⛪', 'Church',    'Sermons, giving, events'],
                            ['business',  '🏢', 'Business',  'Services, team, contact'],
                            ['portfolio', '✨', 'Portfolio', 'Showcase, hire me'],
                        ] as [$id, $icon, $label, $desc])
                        <button @click="setType('{{ $id }}')"
                                :class="type === '{{ $id }}' ? 'border-indigo-500/70 bg-indigo-500/10' : 'border-white/[0.07] hover:border-white/15 hover:bg-white/[0.03]'"
                                class="w-full flex items-center gap-3 p-3 rounded-xl border transition-all duration-200 text-left cursor-pointer">
                            <div class="w-7 h-7 rounded-lg bg-white/[0.05] border border-white/10 flex items-center justify-center text-base flex-shrink-0">{{ $icon }}</div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-white leading-none mb-0.5">{{ $label }}</div>
                                <div class="text-[11px] text-white/30">{{ $desc }}</div>
                            </div>
                            <div :class="type === '{{ $id }}' ? 'opacity-100 scale-100' : 'opacity-0 scale-75'"
                                 class="w-4 h-4 rounded-full bg-indigo-500 flex items-center justify-center transition-all duration-200 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- CTA --}}
                <a href="{{ url('/register') }}?plan=free-trial"
                   class="ripple-btn btn-spring flex items-center justify-center gap-2.5 w-full py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/25 transition-colors duration-200">
                    Build This Site Free
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            {{-- ── Live Browser Preview ── --}}
            <div class="relative">

                {{-- Glow behind --}}
                <div class="absolute -inset-8 pointer-events-none blur-3xl opacity-25 transition-all duration-700"
                     :style="'background: radial-gradient(ellipse at center, ' + currentStyle.accent + '50 0%, transparent 65%)'"></div>

                {{-- Browser frame --}}
                <div class="relative rounded-2xl overflow-hidden border border-white/[0.08] shadow-[0_40px_90px_rgba(0,0,0,0.65)]"
                     :class="animating ? 'opacity-50 scale-[0.985]' : 'opacity-100 scale-100'"
                     style="transition: opacity 0.22s ease, transform 0.22s cubic-bezier(0.16,1,0.3,1)">

                    {{-- Chrome bar --}}
                    <div class="flex items-center gap-2 px-4 py-3 bg-[#1c1c1e] border-b border-white/[0.05] flex-shrink-0">
                        <span class="w-3 h-3 rounded-full bg-red-500/65 hover:bg-red-500 transition-colors cursor-pointer"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-500/65 hover:bg-yellow-500 transition-colors cursor-pointer"></span>
                        <span class="w-3 h-3 rounded-full bg-green-500/65 hover:bg-green-500 transition-colors cursor-pointer"></span>
                        <div class="flex-1 mx-3 px-3 py-1.5 rounded-md bg-white/[0.05] text-[10px] text-white/20 font-mono flex items-center gap-1.5">
                            <svg class="w-3 h-3 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                            yourorg.faithstack.com
                        </div>
                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-500/12 border border-emerald-500/20 flex-shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span class="text-[10px] text-emerald-400 font-medium">Live</span>
                        </div>
                    </div>

                    {{-- Page content (Alpine driven) --}}
                    <div :style="'background: ' + currentStyle.bg"
                         style="transition: background 0.45s cubic-bezier(0.16,1,0.3,1)">

                        {{-- Page nav --}}
                        <div class="flex items-center justify-between px-5 py-3.5"
                             :style="'border-bottom: 1px solid ' + currentStyle.border">
                            <div class="flex items-center gap-2.5">
                                <div class="w-6 h-6 rounded-md flex-shrink-0 transition-colors duration-400"
                                     :style="'background: ' + currentStyle.accent"></div>
                                <span class="text-sm font-bold transition-colors duration-300"
                                      :style="'color: ' + currentStyle.text"
                                      x-text="currentPreset.nav[0]"></span>
                            </div>
                            <div class="hidden sm:flex items-center gap-5">
                                <template x-for="item in currentPreset.nav.slice(1)" :key="item">
                                    <span class="text-xs font-medium transition-colors duration-300"
                                          :style="'color: ' + currentStyle.muted"
                                          x-text="item"></span>
                                </template>
                            </div>
                            <div class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white transition-colors duration-400"
                                 :style="'background: ' + currentStyle.accent"
                                 x-text="currentPreset.cta"></div>
                        </div>

                        {{-- Hero --}}
                        <div class="relative px-6 py-10 text-center overflow-hidden">
                            <div class="absolute inset-0 pointer-events-none transition-all duration-500"
                                 :style="'background: radial-gradient(ellipse 85% 60% at 50% 0%, ' + currentStyle.accent + '1a 0%, transparent 70%)'"></div>
                            <div class="relative">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-5 transition-all duration-400"
                                     :style="'background: ' + currentStyle.accent + '18; border: 1px solid ' + currentStyle.accent + '35; color: ' + currentStyle.accent"
                                     x-text="currentPreset.badge"></div>

                                <h1 class="text-2xl sm:text-[1.7rem] font-bold leading-tight mb-3 transition-all duration-300"
                                    :style="'color: ' + currentStyle.text"
                                    x-text="currentPreset.headline"></h1>

                                <p class="text-sm mb-6 max-w-xs mx-auto transition-colors duration-300"
                                   :style="'color: ' + currentStyle.muted"
                                   x-text="currentPreset.sub"></p>

                                <div class="flex items-center justify-center gap-3">
                                    <div class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white transition-colors duration-400"
                                         :style="'background: ' + currentStyle.accent"
                                         x-text="currentPreset.cta"></div>
                                    <div class="px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-300"
                                         :style="'color: ' + currentStyle.muted + '; border: 1px solid ' + currentStyle.border">
                                        Learn More
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Feature cards --}}
                        <div class="grid grid-cols-3 gap-3 px-5 pb-7">
                            <template x-for="(item, i) in currentPreset.features" :key="i">
                                <div class="rounded-xl p-3.5 transition-all duration-400"
                                     :style="'background: ' + currentStyle.surface + '; border: 1px solid ' + currentStyle.border">
                                    <div class="w-7 h-7 rounded-lg mb-2.5 flex items-center justify-center text-sm transition-all duration-300"
                                         :style="'background: ' + currentStyle.accent + '1c'"
                                         x-text="item.icon"></div>
                                    <div class="text-xs font-semibold mb-1.5 transition-colors duration-300"
                                         :style="'color: ' + currentStyle.text"
                                         x-text="item.label"></div>
                                    <div class="h-1.5 rounded-full w-4/5 transition-all duration-300"
                                         :style="'background: ' + currentStyle.muted + '28'"></div>
                                </div>
                            </template>
                        </div>

                    </div>
                </div>

                {{-- "Preview updates live" chip --}}
                <div class="absolute -bottom-5 left-1/2 -translate-x-1/2 bg-white rounded-full px-4 py-2 shadow-xl text-xs font-semibold text-slate-700 flex items-center gap-2 border border-slate-100/80 whitespace-nowrap">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                    </span>
                    Preview updates live
                </div>
            </div>

        </div>
    </div>
</section>
