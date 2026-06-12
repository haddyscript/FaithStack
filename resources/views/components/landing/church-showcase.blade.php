{{--
    Church Showcase — full split-grid section
    Left  (lg:col-span-5): copy, badge, headline, subtext, CTAs, live indicator
    Right (lg:col-span-7): church image with layered depth, floating stat chips
    Isolated stacking context (isolate + inline overflow:hidden) prevents
    GSAP z-tunnel transforms from bleeding into adjacent sections.
--}}
<section class="relative isolate" style="overflow: hidden">

    {{-- ── Background: deep dark base + ambient indigo/violet glow ── --}}
    <div class="absolute inset-0 bg-slate-950"></div>
    <div class="absolute -top-40 -left-32 w-[700px] h-[600px] rounded-full
                bg-gradient-to-br from-indigo-700/20 via-violet-700/12 to-transparent
                blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[400px] rounded-full
                bg-gradient-to-tl from-violet-800/15 to-transparent
                blur-[100px] pointer-events-none"></div>

    {{-- ── Top feather — blends with white section above ── --}}
    <div class="absolute top-0 inset-x-0 h-20 bg-gradient-to-b from-white to-transparent pointer-events-none z-20"></div>

    {{-- ── Main grid ── --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

            {{-- ── LEFT: Copy (5 cols) ── --}}
            <div class="lg:col-span-5 flex flex-col">

                {{-- Badge --}}
                <div class="reveal inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full
                            bg-white/[0.07] border border-white/[0.12] backdrop-blur-sm
                            text-xs font-semibold text-white/80 uppercase tracking-[0.16em]
                            mb-8 self-start">
                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253"/>
                    </svg>
                    Built for communities that matter
                </div>

                {{-- Headline --}}
                <h2 class="reveal text-4xl lg:text-5xl xl:text-[3.4rem] font-bold text-white
                            leading-[1.08] tracking-tight mb-6"
                    data-delay="1">
                    Your mission deserves<br>
                    <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400
                                 bg-clip-text text-transparent">
                        a website worthy of it.
                    </span>
                </h2>

                {{-- Subtext --}}
                <p class="reveal text-base text-white/55 leading-[1.8] mb-10 max-w-[400px]" data-delay="2">
                    FaithStack gives churches and nonprofits everything they need to build, grow, and connect — no technical expertise required.
                </p>

                {{-- CTAs --}}
                <div class="reveal flex flex-wrap gap-4 mb-10" data-delay="3">
                    <a href="{{ url('/register') }}?plan=free-trial"
                       class="ripple-btn btn-spring group inline-flex items-center gap-2.5
                              px-7 py-3.5 rounded-xl
                              bg-white text-slate-900 font-semibold text-sm
                              shadow-[0_8px_32px_rgba(0,0,0,0.35)]
                              hover:bg-white/90 hover:shadow-[0_12px_40px_rgba(0,0,0,0.45)]
                              transition-all duration-300">
                        Start Free — No Card Needed
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                    <a href="#testimonials"
                       class="ripple-btn btn-spring inline-flex items-center gap-2.5
                              px-7 py-3.5 rounded-xl
                              bg-white/[0.07] border border-white/[0.15] backdrop-blur-sm
                              text-white font-semibold text-sm
                              hover:bg-white/[0.14] hover:border-white/30
                              transition-all duration-300">
                        See Real Results
                    </a>
                </div>

                {{-- Live indicator --}}
                <div class="reveal flex items-center gap-3" data-delay="4">
                    <div class="flex items-center gap-2 px-3.5 py-2 rounded-full
                                bg-white/[0.06] border border-white/[0.10]">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-60"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        <span class="text-xs font-semibold text-white/70">
                            <span class="text-white">500+</span> organizations live right now
                        </span>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: Church image (7 cols) ── --}}
            <div class="lg:col-span-7 relative">

                {{-- Outer glow halo --}}
                <div class="absolute -inset-6 bg-gradient-to-r from-indigo-500/15 via-violet-500/10 to-purple-500/15
                            rounded-3xl blur-2xl pointer-events-none"></div>

                {{-- Image frame --}}
                <div class="relative rounded-2xl overflow-hidden
                            border border-white/[0.10]
                            shadow-[0_40px_80px_rgba(0,0,0,0.50),0_8px_24px_rgba(0,0,0,0.30)]">

                    {{-- Church image --}}
                    <div class="aspect-[16/10] relative overflow-hidden">
                        <img src="{{ asset('images/faithstack_images/Church_with_glowing_2.jpeg') }}"
                             alt="Church illuminated at night"
                             class="w-full h-full object-cover object-center
                                    scale-[1.02] hover:scale-[1.06]
                                    transition-transform duration-[1400ms] ease-out">

                        {{-- Subtle dark vignette for depth --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-slate-950/20 pointer-events-none"></div>
                        {{-- Left edge fade into dark copy column on lg --}}
                        <div class="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-slate-950/40 to-transparent pointer-events-none hidden lg:block"></div>
                    </div>

                    {{-- Floating stat chips --}}

                    {{-- Chip: Theme applied --}}
                    <div class="float-chip absolute -left-12 top-[30%]
                                bg-white rounded-2xl px-3.5 py-2.5
                                shadow-[0_8px_32px_rgba(0,0,0,0.20)]
                                text-xs font-semibold text-slate-800
                                flex items-center gap-2 border border-slate-100/80"
                         style="--dur:6.5s;--rot:-2deg">
                        <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white text-[9px] font-bold flex-shrink-0">✓</div>
                        Theme applied!
                    </div>

                    {{-- Chip: Donations --}}
                    <div class="float-chip absolute -right-10 top-[20%]
                                bg-white rounded-2xl px-3.5 py-2.5
                                shadow-[0_8px_32px_rgba(0,0,0,0.20)]
                                text-xs font-semibold text-slate-800
                                flex items-center gap-2 border border-slate-100/80"
                         style="--dur:5.2s;--rot:2deg;animation-delay:-2.5s">
                        <span class="text-base">💰</span> $1,240 raised
                    </div>

                    {{-- Chip: Live --}}
                    <div class="float-chip absolute -right-8 bottom-[30%]
                                bg-white border border-slate-200 rounded-2xl px-3 py-2
                                shadow-[0_4px_16px_rgba(0,0,0,0.15)]
                                text-[11px] text-slate-600 flex items-center gap-2"
                         style="--dur:7s;--rot:1deg;animation-delay:-1s">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        Live
                    </div>

                    {{-- Chip: Community --}}
                    <div class="float-chip absolute -left-8 bottom-[16%]
                                bg-white border border-slate-100 rounded-2xl px-3 py-2
                                shadow-[0_4px_16px_rgba(0,0,0,0.15)]
                                text-[11px] text-slate-500 flex items-center gap-2"
                         style="--dur:8s;--rot:-1deg;animation-delay:-4s">
                        <svg class="w-3.5 h-3.5 text-indigo-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        +12 joined today
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- ── Bottom feather — blends into founders section below ── --}}
    <div class="absolute bottom-0 inset-x-0 h-20 bg-gradient-to-t from-slate-50/70 to-transparent pointer-events-none z-20"></div>

</section>
