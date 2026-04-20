<section data-cursor-glow class="relative min-h-screen flex items-center overflow-hidden bg-[#09090b]">

    {{-- Grid --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff07_1px,transparent_1px),linear-gradient(to_bottom,#ffffff07_1px,transparent_1px)] bg-[size:64px_64px] pointer-events-none"></div>

    {{-- Bottom fade --}}
    <div class="absolute bottom-0 inset-x-0 h-48 bg-gradient-to-t from-[#09090b] to-transparent pointer-events-none z-10"></div>

    {{-- Parallax blobs (CSS animation only, no JS parallax to avoid transform conflicts) --}}
    <div class="blob absolute top-1/4 -left-40 w-[680px] h-[680px] rounded-full bg-indigo-600/14 blur-3xl pointer-events-none" style="--dur:22s"></div>
    <div class="blob absolute bottom-1/4 -right-40 w-[560px] h-[560px] rounded-full bg-purple-600/14 blur-3xl pointer-events-none" style="--dur:28s;animation-delay:-9s"></div>
    <div class="blob absolute top-2/3 left-1/3 w-72 h-72 rounded-full bg-pink-500/8 blur-3xl pointer-events-none"  style="--dur:16s;animation-delay:-5s"></div>

    {{-- Static parallax depth dots (no CSS animation, pure JS parallax) --}}
    <div class="absolute top-[18%] right-[12%] w-2 h-2 rounded-full bg-indigo-400/40 pointer-events-none" data-parallax="-0.06"></div>
    <div class="absolute top-[60%] left-[6%] w-3 h-3 rounded-full bg-purple-400/30 pointer-events-none" data-parallax="-0.10"></div>
    <div class="absolute bottom-[30%] right-[25%] w-1.5 h-1.5 rounded-full bg-pink-400/40 pointer-events-none" data-parallax="-0.05"></div>
    <div class="absolute top-[35%] left-[25%] w-2.5 h-2.5 rounded-full bg-indigo-300/25 pointer-events-none" data-parallax="0.18"></div>

    {{-- Radial vignette --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_-10%,rgba(99,102,241,0.1),transparent)] pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 lg:py-32 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            {{-- Copy --}}
            <div>
                {{-- Badge --}}
                <div class="reveal inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-white/10 bg-white/[0.04] text-xs text-white/55 mb-8 font-medium backdrop-blur-sm">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-400"></span>
                    </span>
                    Now in open beta — join 500+ organizations
                </div>

                {{-- Headline --}}
                <h1 class="text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.07] tracking-tight mb-6">
                    <span class="word-reveal">Build</span>
                    <span class="word-reveal"> beautiful</span><br>
                    <span class="word-reveal">sites for</span><br>
                    <span x-data="typewriter" class="inline-block">
                        <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent"
                              x-text="displayed">churches</span><span class="tw-cursor bg-gradient-to-b from-indigo-400 to-purple-400"></span>
                    </span>
                </h1>

                {{-- Sub --}}
                <p class="reveal text-[1.0625rem] text-white/45 leading-[1.75] mb-10 max-w-md" data-delay="4">
                    A complete CMS platform — drag-and-drop editor, 80+ themes, donation management, and custom branding. Live in minutes.
                </p>

                {{-- CTAs --}}
                <div class="reveal flex flex-wrap gap-4" data-delay="5">
                    <a href="{{ url('/register') }}?plan=free-trial" data-magnetic
                       class="magnetic ripple-btn group inline-flex items-center gap-2.5 px-7 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition-colors duration-200 shadow-[0_0_0_1px_rgba(99,102,241,0.3),0_12px_32px_rgba(99,102,241,0.35)] hover:shadow-[0_0_0_1px_rgba(99,102,241,0.4),0_16px_40px_rgba(99,102,241,0.5)]">
                        Start Free Trial
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="#themes" data-magnetic
                       class="magnetic ripple-btn group inline-flex items-center gap-2.5 px-7 py-3.5 rounded-xl border border-white/10 hover:border-white/20 text-white/65 hover:text-white font-semibold text-sm transition-all duration-200 hover:bg-white/[0.04]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/></svg>
                        View Themes
                    </a>
                </div>

                {{-- Trust --}}
                <div class="reveal flex flex-wrap gap-x-6 gap-y-2 mt-10 text-xs text-white/22" data-delay="6">
                    @foreach(['No credit card', '5-minute setup', 'Cancel anytime'] as $b)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        {{ $b }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Browser mockup --}}
            <div class="hidden lg:flex justify-center items-center">
                <div class="mockup-float relative w-full max-w-lg">

                    {{-- Halo glow --}}
                    <div class="absolute -inset-8 bg-gradient-to-r from-indigo-600/18 via-purple-600/14 to-pink-600/18 rounded-3xl blur-3xl pointer-events-none"></div>

                    {{-- Frame --}}
                    <div class="relative rounded-2xl overflow-hidden border border-white/[0.08] shadow-[0_48px_100px_rgba(0,0,0,0.7)] bg-[#0f0f11]">

                        {{-- Chrome --}}
                        <div class="flex items-center gap-2 px-4 py-3 bg-[#18181b] border-b border-white/[0.05]">
                            <span class="w-3 h-3 rounded-full bg-red-500/65 hover:bg-red-500 transition-colors cursor-pointer"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-500/65 hover:bg-yellow-500 transition-colors cursor-pointer"></span>
                            <span class="w-3 h-3 rounded-full bg-green-500/65 hover:bg-green-500 transition-colors cursor-pointer"></span>
                            <div class="flex-1 mx-3 px-3 py-1 rounded-md bg-white/[0.04] text-[10px] text-white/18 font-mono select-none">
                                yourchurch.faithstack.com
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="bg-[#0d0d14] aspect-[4/3] flex flex-col overflow-hidden">

                            {{-- Nav --}}
                            <div class="flex items-center justify-between px-4 py-3 border-b border-white/[0.04]">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-md bg-indigo-600"></div>
                                    <div class="w-20 h-2 rounded-full bg-white/18"></div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-9 h-1.5 rounded-full bg-white/8"></div>
                                    <div class="w-9 h-1.5 rounded-full bg-white/8"></div>
                                    <div class="w-9 h-1.5 rounded-full bg-white/8"></div>
                                </div>
                                <div class="w-16 h-6 rounded-lg bg-indigo-600/75"></div>
                            </div>

                            {{-- Hero --}}
                            <div class="flex-1 flex flex-col items-center justify-center gap-3 p-6 bg-gradient-to-br from-[#0d0d14] via-indigo-950/40 to-[#0d0d14]">
                                <div class="w-52 h-3 rounded-full bg-white/70"></div>
                                <div class="w-40 h-3 rounded-full bg-white/40"></div>
                                <div class="w-32 h-2 rounded-full bg-white/18 mt-1"></div>
                                <div class="flex gap-2 mt-3">
                                    <div class="w-20 h-6 rounded-lg bg-indigo-500"></div>
                                    <div class="w-20 h-6 rounded-lg border border-white/18"></div>
                                </div>
                            </div>

                            {{-- Cards --}}
                            <div class="grid grid-cols-3 gap-2 px-3 pb-3">
                                @foreach(['bg-indigo-600/22', 'bg-purple-600/22', 'bg-blue-600/22'] as $c)
                                <div class="rounded-xl {{ $c }} p-2.5">
                                    <div class="w-5 h-5 rounded-lg bg-white/18 mb-2"></div>
                                    <div class="w-full h-1.5 rounded-full bg-white/22 mb-1.5"></div>
                                    <div class="w-3/4 h-1.5 rounded-full bg-white/12"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Floating chips (float-chip CSS animation, no JS parallax) --}}
                    <div class="float-chip absolute -left-10 top-[38%] bg-white rounded-2xl px-3.5 py-2.5 shadow-[0_20px_60px_rgba(0,0,0,0.3)] text-xs font-semibold text-slate-800 flex items-center gap-2 border border-slate-100/80" style="--dur:6.5s;--rot:-2deg">
                        <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white text-[9px] font-bold">✓</div>
                        Theme applied!
                    </div>
                    <div class="float-chip absolute -right-9 bottom-[28%] bg-white rounded-2xl px-3.5 py-2.5 shadow-[0_20px_60px_rgba(0,0,0,0.3)] text-xs font-semibold text-slate-800 flex items-center gap-2 border border-slate-100/80" style="--dur:5.2s;--rot:2deg;animation-delay:-2.5s">
                        <span>💰</span> $1,240 raised
                    </div>
                    <div class="float-chip absolute -right-7 top-[22%] bg-[#16161e] border border-white/[0.08] rounded-2xl px-3 py-2 shadow-xl text-[11px] text-white/60 flex items-center gap-2" style="--dur:7s;--rot:1deg;animation-delay:-1s">
                        <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span></span>
                        Live
                    </div>
                    <div class="float-chip absolute -left-6 bottom-[14%] bg-[#16161e] border border-white/[0.07] rounded-2xl px-3 py-2 shadow-xl text-[11px] text-white/50 flex items-center gap-2" style="--dur:8s;--rot:-1deg;animation-delay:-4s">
                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                        +12 joined today
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
