<section class="relative min-h-screen flex items-center overflow-hidden bg-[#09090b]">

    {{-- Animated grid background --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff07_1px,transparent_1px),linear-gradient(to_bottom,#ffffff07_1px,transparent_1px)] bg-[size:64px_64px]"></div>

    {{-- Bottom gradient fade --}}
    <div class="absolute bottom-0 inset-x-0 h-40 bg-gradient-to-t from-[#09090b] to-transparent pointer-events-none z-10"></div>

    {{-- Animated blobs --}}
    <div class="blob absolute top-1/4 -left-32 w-[600px] h-[600px] rounded-full bg-indigo-600/15 blur-3xl pointer-events-none" style="--duration:20s"></div>
    <div class="blob absolute bottom-1/4 -right-32 w-[500px] h-[500px] rounded-full bg-purple-600/15 blur-3xl pointer-events-none" style="--duration:25s;animation-delay:-8s"></div>
    <div class="blob absolute top-2/3 left-1/3 w-64 h-64 rounded-full bg-pink-600/10 blur-3xl pointer-events-none" style="--duration:15s;animation-delay:-4s"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 lg:py-32 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            {{-- Left: Copy --}}
            <div>
                {{-- Badge --}}
                <div class="reveal inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/10 bg-white/5 text-xs text-white/60 mb-8 font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Now in open beta — join 500+ organizations
                </div>

                {{-- Animated headline --}}
                <h1 class="text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.08] tracking-tight mb-6 overflow-hidden">
                    <span class="word-reveal">Build</span>
                    <span class="word-reveal"> beautiful</span><br>
                    <span class="word-reveal">websites</span>
                    <span class="word-reveal"> for</span>
                    <span class="word-reveal"> your</span><br>
                    <span class="word-reveal bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                        organization
                    </span>
                </h1>

                {{-- Subheadline --}}
                <p class="reveal text-lg text-white/50 leading-relaxed mb-10 max-w-lg" data-delay="4">
                    FaithStack is a multi-tenant CMS designed for churches, nonprofits, and community organizations. Pick a theme, customize your brand, and go live in minutes.
                </p>

                {{-- CTAs --}}
                <div class="reveal flex flex-wrap gap-4" data-delay="5">
                    <a href="/superadmin/login"
                       class="ripple-btn group inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition-all duration-300 shadow-xl shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:-translate-y-1">
                        Start Free Trial
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="#themes"
                       class="ripple-btn group inline-flex items-center gap-2 px-7 py-3.5 rounded-xl border border-white/10 hover:border-white/25 text-white/70 hover:text-white font-semibold text-sm transition-all duration-300 hover:bg-white/5 hover:-translate-y-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/></svg>
                        View Themes
                    </a>
                </div>

                {{-- Trust badges --}}
                <div class="reveal flex flex-wrap gap-x-6 gap-y-2 mt-10 text-sm text-white/25" data-delay="6">
                    @foreach(['No credit card', '5-minute setup', 'Cancel anytime'] as $badge)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        {{ $badge }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Right: Browser mockup --}}
            <div class="hidden lg:flex justify-center items-center">
                <div class="mockup-float relative w-full max-w-lg" style="transform-origin: center; transform: rotate(-1deg);">

                    {{-- Glow --}}
                    <div class="absolute -inset-6 bg-gradient-to-r from-indigo-600/20 via-purple-600/15 to-pink-600/20 rounded-3xl blur-2xl pointer-events-none"></div>

                    {{-- Browser frame --}}
                    <div class="relative rounded-2xl overflow-hidden border border-white/10 shadow-[0_40px_80px_rgba(0,0,0,0.6)] bg-[#111113]">

                        {{-- Chrome bar --}}
                        <div class="flex items-center gap-2 px-4 py-3 bg-[#1a1a1e] border-b border-white/5">
                            <span class="w-3 h-3 rounded-full bg-red-500/70 hover:bg-red-500 transition-colors cursor-pointer"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-500/70 hover:bg-yellow-500 transition-colors cursor-pointer"></span>
                            <span class="w-3 h-3 rounded-full bg-green-500/70 hover:bg-green-500 transition-colors cursor-pointer"></span>
                            <div class="flex-1 mx-3 px-3 py-1 rounded-md bg-white/5 text-xs text-white/20 font-mono select-none">
                                yourchurch.faithstack.com
                            </div>
                        </div>

                        {{-- Mock site content --}}
                        <div class="bg-slate-900 aspect-[4/3] flex flex-col overflow-hidden">

                            {{-- Site nav --}}
                            <div class="flex items-center justify-between px-4 py-3 border-b border-white/5">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded bg-indigo-600"></div>
                                    <div class="w-20 h-2 rounded-full bg-white/20"></div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-8 h-1.5 rounded-full bg-white/10"></div>
                                    <div class="w-8 h-1.5 rounded-full bg-white/10"></div>
                                    <div class="w-8 h-1.5 rounded-full bg-white/10"></div>
                                </div>
                                <div class="w-16 h-6 rounded-lg bg-indigo-600/80"></div>
                            </div>

                            {{-- Hero area --}}
                            <div class="flex-1 flex flex-col items-center justify-center gap-3 p-6 bg-gradient-to-br from-slate-900 via-indigo-950/50 to-slate-900">
                                <div class="w-52 h-3 rounded-full bg-white/75"></div>
                                <div class="w-40 h-3 rounded-full bg-white/45"></div>
                                <div class="w-32 h-2 rounded-full bg-white/20 mt-1"></div>
                                <div class="flex gap-2 mt-3">
                                    <div class="w-20 h-6 rounded-lg bg-indigo-500"></div>
                                    <div class="w-20 h-6 rounded-lg border border-white/20"></div>
                                </div>
                            </div>

                            {{-- Feature strips --}}
                            <div class="grid grid-cols-3 gap-2 px-3 pb-3">
                                @foreach(['bg-indigo-600/25', 'bg-purple-600/25', 'bg-blue-600/25'] as $c)
                                <div class="rounded-xl {{ $c }} p-2.5">
                                    <div class="w-5 h-5 rounded-lg bg-white/20 mb-2"></div>
                                    <div class="w-full h-1.5 rounded-full bg-white/25 mb-1.5"></div>
                                    <div class="w-3/4 h-1.5 rounded-full bg-white/15"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Floating badges --}}
                    <div class="absolute -left-10 top-1/3 bg-white rounded-2xl px-4 py-2.5 shadow-2xl text-xs font-semibold text-slate-800 flex items-center gap-2 border border-slate-100">
                        <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white text-[10px]">✓</div>
                        Theme applied!
                    </div>
                    <div class="absolute -right-8 bottom-1/4 bg-white rounded-2xl px-4 py-2.5 shadow-2xl text-xs font-semibold text-slate-800 flex items-center gap-2 border border-slate-100">
                        <span class="text-base">💰</span> $1,240 raised
                    </div>
                    <div class="absolute -right-6 top-1/4 bg-[#1a1a2e] border border-white/10 rounded-2xl px-3 py-2 shadow-2xl text-xs text-white/70 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Live
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
