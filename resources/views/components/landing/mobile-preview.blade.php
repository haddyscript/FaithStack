<section class="py-24 lg:py-32 bg-[#09090b] relative overflow-hidden">

    {{-- Grid texture --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff04_1px,transparent_1px),linear-gradient(to_bottom,#ffffff04_1px,transparent_1px)] bg-[size:48px_48px] pointer-events-none"></div>

    {{-- Ambient indigo glow left --}}
    <div class="absolute top-1/2 -left-40 -translate-y-1/2 w-[500px] h-[500px] rounded-full
                bg-indigo-600/12 blur-[120px] pointer-events-none"></div>
    {{-- Ambient purple glow right --}}
    <div class="absolute top-1/2 -right-32 -translate-y-1/2 w-[400px] h-[400px] rounded-full
                bg-violet-600/10 blur-[100px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">

            {{-- ── LEFT: Phone mockup (6 cols) ── --}}
            <div class="lg:col-span-6 flex justify-center items-center order-2 lg:order-1">
                <div class="relative">

                    {{-- Halo glow behind phone --}}
                    <div class="absolute -inset-10 bg-gradient-to-b from-indigo-500/20 via-violet-500/15 to-purple-500/10
                                rounded-[60px] blur-3xl pointer-events-none"></div>

                    {{-- Phone shell --}}
                    <div class="relative w-[320px] sm:w-[360px] lg:w-[400px] rounded-[52px] p-3
                                bg-gradient-to-b from-zinc-700 to-zinc-800
                                shadow-[0_50px_100px_rgba(0,0,0,0.60),0_0_0_1px_rgba(255,255,255,0.08),inset_0_0_0_1px_rgba(255,255,255,0.04)]">

                        {{-- Screen bezel --}}
                        <div class="rounded-[44px] overflow-hidden bg-black relative">

                            {{-- Status bar --}}
                            <div class="flex items-center justify-between px-5 pt-3 pb-2 bg-slate-950">
                                <span class="text-[10px] font-semibold text-white/70">9:41</span>
                                <div class="w-20 h-4 rounded-full bg-black absolute top-0 left-1/2 -translate-x-1/2"></div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-white/70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.293 4.293a1 1 0 011.414 1.414L8.414 16l-5.707-5.707a1 1 0 011.414-1.414L8.414 13.172l9.879-9.879z" clip-rule="evenodd"/></svg>
                                    <svg class="w-3 h-3 text-white/70" fill="currentColor" viewBox="0 0 24 24"><path d="M1.5 8.5a13 13 0 0121 0M5.5 12.5a8 8 0 0113 0M9.5 16.5a4 4 0 015 0M12 20.5h.01"/></svg>
                                    <div class="flex items-center gap-0.5">
                                        <div class="w-1 h-2.5 rounded-sm bg-white/80"></div>
                                        <div class="w-1 h-2 rounded-sm bg-white/80"></div>
                                        <div class="w-1 h-1.5 rounded-sm bg-white/50"></div>
                                        <div class="w-1 h-1 rounded-sm bg-white/30"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Video --}}
                            <video
                                autoplay muted loop playsinline
                                preload="auto"
                                class="w-full block">
                                <source src="{{ asset('videos/Three_toggle_cards_tap.mp4') }}" type="video/mp4">
                            </video>

                            {{-- Home indicator --}}
                            <div class="flex justify-center py-2 bg-black">
                                <div class="w-28 h-1 rounded-full bg-white/25"></div>
                            </div>
                        </div>

                    </div>

                    {{-- Side button details --}}
                    <div class="absolute -left-1.5 top-[120px] w-1.5 h-9 rounded-l-full bg-zinc-600"></div>
                    <div class="absolute -left-1.5 top-[174px] w-1.5 h-16 rounded-l-full bg-zinc-600"></div>
                    <div class="absolute -left-1.5 top-[206px] w-1.5 h-16 rounded-l-full bg-zinc-600"></div>
                    <div class="absolute -right-1.5 top-[158px] w-1.5 h-24 rounded-r-full bg-zinc-600"></div>

                    {{-- Floating badge: "Tap to interact" --}}
                    <div class="float-chip absolute -right-16 top-[18%]
                                bg-white rounded-2xl px-3.5 py-2.5
                                shadow-[0_8px_32px_rgba(0,0,0,0.25)]
                                text-xs font-semibold text-slate-700
                                flex items-center gap-2 border border-slate-100"
                         style="--dur:6s;--rot:2deg">
                        <span class="text-base">👆</span> Tap to interact
                    </div>

                    {{-- Floating badge: "Mobile-first" --}}
                    <div class="float-chip absolute -left-16 bottom-[22%]
                                bg-white rounded-2xl px-3.5 py-2.5
                                shadow-[0_8px_32px_rgba(0,0,0,0.25)]
                                text-xs font-semibold text-slate-700
                                flex items-center gap-2 border border-slate-100"
                         style="--dur:7.5s;--rot:-2deg;animation-delay:-3s">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        Mobile-first
                    </div>

                </div>
            </div>

            {{-- ── RIGHT: Copy (6 cols) ── --}}
            <div class="lg:col-span-6 order-1 lg:order-2">

                {{-- Badge --}}
                <div class="reveal inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full
                            border border-white/10 bg-white/[0.04]
                            text-xs text-white/55 font-medium mb-8">
                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3.75h3m-3 3.75h3"/>
                    </svg>
                    Beautiful on every device
                </div>

                {{-- Headline --}}
                <h2 class="reveal text-4xl lg:text-5xl font-bold text-white tracking-tight leading-[1.1] mb-6" data-delay="1">
                    Your site, perfectly<br>
                    <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400 bg-clip-text text-transparent">
                        crafted for mobile.
                    </span>
                </h2>

                {{-- Subtext --}}
                <p class="reveal text-lg text-white/45 leading-relaxed mb-10 max-w-lg" data-delay="2">
                    Every FaithStack theme is fully responsive out of the box. Members browse sermons, events, and giving pages on their phones — your site keeps up effortlessly.
                </p>

                {{-- Feature bullets --}}
                <div class="reveal space-y-4 mb-10" data-delay="3">
                    @foreach([
                        ['icon' => '📱', 'title' => 'Touch-optimized layouts',    'desc' => 'Every tap target, spacing, and scroll behavior tuned for mobile users.'],
                        ['icon' => '⚡', 'title' => 'Instant load times',         'desc' => 'Lean, fast-loading pages so nothing slows your congregation down.'],
                        ['icon' => '🎨', 'title' => 'Consistent across screens',  'desc' => 'The same beautiful design on desktop, tablet, and phone — automatically.'],
                    ] as $f)
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] transition-colors duration-300">
                        <div class="w-10 h-10 rounded-xl bg-white/[0.05] border border-white/[0.08] flex items-center justify-center text-lg flex-shrink-0">
                            {{ $f['icon'] }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white mb-0.5">{{ $f['title'] }}</p>
                            <p class="text-sm text-white/40">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- CTA --}}
                <div class="reveal" data-delay="4">
                    <a href="{{ url('/register') }}?plan=free-trial"
                       class="ripple-btn btn-spring inline-flex items-center gap-2.5 px-7 py-3.5 rounded-xl
                              bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm
                              shadow-lg shadow-indigo-600/25 transition-all duration-200
                              hover:shadow-indigo-500/40 hover:shadow-xl">
                        Start Building Free
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
