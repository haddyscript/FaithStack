<section data-cursor-glow class="relative min-h-screen flex items-center overflow-hidden bg-white">

    {{-- Dot grid --}}
    <div class="absolute inset-0 bg-[radial-gradient(circle,#cbd5e1_1px,transparent_1px)] bg-[size:32px_32px] opacity-50 pointer-events-none"></div>

    {{-- Bottom fade --}}
    <div class="absolute bottom-0 inset-x-0 h-48 bg-gradient-to-t from-white to-transparent pointer-events-none z-10"></div>

    {{-- Parallax blobs --}}
    <div class="blob absolute top-1/4 -left-40 w-[680px] h-[680px] rounded-full bg-indigo-400/10 blur-3xl pointer-events-none" style="--dur:22s"></div>
    <div class="blob absolute bottom-1/4 -right-40 w-[560px] h-[560px] rounded-full bg-violet-400/10 blur-3xl pointer-events-none" style="--dur:28s;animation-delay:-9s"></div>
    <div class="blob absolute top-2/3 left-1/3 w-72 h-72 rounded-full bg-sky-400/8 blur-3xl pointer-events-none"  style="--dur:16s;animation-delay:-5s"></div>

    {{-- Depth dots --}}
    <div class="absolute top-[18%] right-[12%] w-2 h-2 rounded-full bg-indigo-400/30 pointer-events-none" data-parallax="-0.06"></div>
    <div class="absolute top-[60%] left-[6%] w-3 h-3 rounded-full bg-violet-400/25 pointer-events-none" data-parallax="-0.10"></div>
    <div class="absolute bottom-[30%] right-[25%] w-1.5 h-1.5 rounded-full bg-indigo-300/35 pointer-events-none" data-parallax="-0.05"></div>
    <div class="absolute top-[35%] left-[25%] w-2.5 h-2.5 rounded-full bg-indigo-200/40 pointer-events-none" data-parallax="0.18"></div>

    {{-- Radial highlight (top-center) --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-5%,rgba(99,102,241,0.07),transparent)] pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 lg:py-32 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            {{-- Copy --}}
            <div>
                {{-- Badge --}}
                <div class="reveal inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-indigo-200 bg-indigo-50 text-xs text-indigo-600 mb-8 font-medium">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-400"></span>
                    </span>
                    Now in open beta — join 500+ organizations
                </div>

                {{-- Headline --}}
                <h1 class="text-5xl lg:text-6xl xl:text-7xl font-bold text-slate-900 leading-[1.07] tracking-tight mb-6">
                    <span class="word-reveal">Build</span>
                    <span class="word-reveal"> beautiful</span><br>
                    <span class="word-reveal">sites for</span><br>
                    <span x-data="typewriter" class="inline-block">
                        <span class="bg-gradient-to-r from-indigo-600 via-violet-500 to-purple-600 bg-clip-text text-transparent"
                              x-text="displayed">churches</span><span class="tw-cursor bg-gradient-to-b from-indigo-500 to-violet-600"></span>
                    </span>
                </h1>

                {{-- Sub --}}
                <p class="reveal text-[1.0625rem] text-slate-500 leading-[1.75] mb-10 max-w-md" data-delay="4">
                    A complete CMS platform — drag-and-drop editor, 80+ themes, donation management, and custom branding. Live in minutes.
                </p>

                {{-- CTAs --}}
                <div class="reveal flex flex-wrap gap-4" data-delay="5">
                    <a href="{{ url('/register') }}?plan=free-trial" data-magnetic
                       class="magnetic ripple-btn group inline-flex items-center gap-2.5 px-7 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition-colors duration-200 shadow-[0_0_0_1px_rgba(99,102,241,0.3),0_12px_32px_rgba(99,102,241,0.30)] hover:shadow-[0_0_0_1px_rgba(99,102,241,0.4),0_16px_40px_rgba(99,102,241,0.45)]">
                        Start Free Trial
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="#themes" data-magnetic
                       class="magnetic ripple-btn group inline-flex items-center gap-2.5 px-7 py-3.5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-900 font-semibold text-sm transition-all duration-200 hover:bg-slate-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/></svg>
                        View Themes
                    </a>
                </div>

                {{-- Trust chips --}}
                <div class="reveal flex flex-wrap items-center gap-3 mt-10" data-delay="6">
                    @foreach([
                        ['label' => 'No charge today', 'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z'],
                        ['label' => '5-minute setup',  'icon' => 'M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z'],
                        ['label' => 'Cancel anytime',  'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ] as $chip)
                    <span class="inline-flex items-center gap-2 pl-1.5 pr-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200">
                        {{-- Icon pill --}}
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 flex-shrink-0">
                            <svg style="width:11px;height:11px;color:#10b981"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $chip['icon'] }}"/>
                            </svg>
                        </span>
                        {{ $chip['label'] }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Browser mockup --}}
            <div class="hidden lg:flex justify-center items-center">
                <div class="mockup-float relative w-full max-w-lg">

                    {{-- Halo glow --}}
                    <div class="absolute -inset-8 bg-gradient-to-r from-indigo-200/40 via-violet-200/30 to-purple-200/40 rounded-3xl blur-3xl pointer-events-none"></div>

                    {{-- Frame --}}
                    <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-[0_48px_100px_rgba(0,0,0,0.10),0_8px_24px_rgba(0,0,0,0.06)] bg-white">

                        {{-- Chrome --}}
                        <div class="flex items-center gap-2 px-4 py-3 bg-slate-100 border-b border-slate-200">
                            <span class="w-3 h-3 rounded-full bg-red-400/80 hover:bg-red-500 transition-colors cursor-pointer"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-400/80 hover:bg-yellow-500 transition-colors cursor-pointer"></span>
                            <span class="w-3 h-3 rounded-full bg-green-400/80 hover:bg-green-500 transition-colors cursor-pointer"></span>
                            <div class="flex-1 mx-3 px-3 py-1 rounded-md bg-white border border-slate-200 text-[10px] text-slate-400 font-mono select-none">
                                yourchurch.faithstack.com
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="bg-slate-50 aspect-[4/3] flex flex-col overflow-hidden">

                            {{-- Nav --}}
                            <div class="mockup-nav-drop flex items-center justify-between px-4 py-3 bg-white border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-md bg-indigo-600"></div>
                                    <div class="w-20 h-2 rounded-full bg-slate-200"></div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-9 h-1.5 rounded-full bg-slate-200"></div>
                                    <div class="w-9 h-1.5 rounded-full bg-slate-200"></div>
                                    <div class="w-9 h-1.5 rounded-full bg-slate-200"></div>
                                </div>
                                <div class="w-16 h-6 rounded-lg bg-indigo-600"></div>
                            </div>

                            {{-- Hero --}}
                            <div class="mockup-content-drop flex-1 flex flex-col items-center justify-center gap-3 p-6 bg-gradient-to-br from-white via-indigo-50/60 to-slate-50">
                                <div class="w-52 h-3 rounded-full bg-slate-700/70"></div>
                                <div class="w-40 h-3 rounded-full bg-slate-400/60"></div>
                                <div class="w-32 h-2 rounded-full bg-slate-300/70 mt-1"></div>
                                <div class="flex gap-2 mt-3">
                                    <div class="w-20 h-6 rounded-lg bg-indigo-500"></div>
                                    <div class="w-20 h-6 rounded-lg border border-slate-200 bg-white"></div>
                                </div>
                            </div>

                            {{-- Cards --}}
                            <div class="grid grid-cols-3 gap-2 px-3 pb-3">
                                @foreach(['bg-indigo-50 border border-indigo-100', 'bg-violet-50 border border-violet-100', 'bg-sky-50 border border-sky-100'] as $c)
                                <div class="rounded-xl {{ $c }} p-2.5">
                                    <div class="w-5 h-5 rounded-lg bg-slate-200 mb-2"></div>
                                    <div class="w-full h-1.5 rounded-full bg-slate-300/70 mb-1.5"></div>
                                    <div class="w-3/4 h-1.5 rounded-full bg-slate-200/80"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Floating chips --}}
                    <div class="theme-applied-chip absolute -left-10 top-[38%] bg-white rounded-2xl px-3.5 py-2.5 shadow-[0_8px_32px_rgba(0,0,0,0.10),0_1px_4px_rgba(0,0,0,0.06)] text-xs font-semibold text-slate-800 flex items-center gap-2 border border-slate-100" style="--dur:6.5s;--rot:-2deg">
                        <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white text-[9px] font-bold">✓</div>
                        Theme applied!
                    </div>
                    <div class="float-chip absolute -right-9 bottom-[28%] bg-white rounded-2xl px-3.5 py-2.5 shadow-[0_8px_32px_rgba(0,0,0,0.10),0_1px_4px_rgba(0,0,0,0.06)] text-xs font-semibold text-slate-800 flex items-center gap-2 border border-slate-100" style="--dur:5.2s;--rot:2deg;animation-delay:-2.5s">
                        <span>💰</span> $1,240 raised
                    </div>
                    <div class="float-chip absolute -right-7 top-[22%] bg-white border border-slate-200 rounded-2xl px-3 py-2 shadow-[0_4px_16px_rgba(0,0,0,0.08)] text-[11px] text-slate-600 flex items-center gap-2" style="--dur:7s;--rot:1deg;animation-delay:-1s">
                        <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span></span>
                        Live
                    </div>
                    <div class="float-chip absolute -left-6 bottom-[14%] bg-white border border-slate-100 rounded-2xl px-3 py-2 shadow-[0_4px_16px_rgba(0,0,0,0.08)] text-[11px] text-slate-500 flex items-center gap-2" style="--dur:8s;--rot:-1deg;animation-delay:-4s">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                        +12 joined today
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
