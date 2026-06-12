{{--
    Video Showcase — cinematic full-width video section
    Placed after Platform Features.
    Dark section with ambient glow, wide browser frame, autoplay video.
    isolate + inline overflow:hidden prevents GSAP stacking bleed.
--}}
<section class="relative isolate py-24 lg:py-32" style="overflow: hidden; background: #09090b;">

    {{-- Grid texture --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff03_1px,transparent_1px),linear-gradient(to_bottom,#ffffff03_1px,transparent_1px)] bg-[size:56px_56px] pointer-events-none"></div>

    {{-- Ambient glow blobs --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                w-[900px] h-[500px] rounded-full
                bg-gradient-to-r from-indigo-700/18 via-violet-700/14 to-purple-700/10
                blur-[140px] pointer-events-none"></div>
    <div class="absolute -top-32 -left-40 w-[500px] h-[400px] rounded-full
                bg-indigo-600/10 blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-20 -right-32 w-[400px] h-[320px] rounded-full
                bg-violet-600/10 blur-[90px] pointer-events-none"></div>

    {{-- Top feather — blends with white section above --}}
    <div class="absolute top-0 inset-x-0 h-24 bg-gradient-to-b from-white to-transparent pointer-events-none z-20"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-14">
            <div class="reveal inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full
                        border border-white/[0.10] bg-white/[0.05]
                        text-xs font-semibold text-white/60 uppercase tracking-[0.15em]
                        mb-6">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-400"></span>
                </span>
                See it in action
            </div>

            <h2 class="reveal text-4xl lg:text-5xl font-bold text-white tracking-tight leading-[1.1] mb-5" data-delay="1">
                The complete platform,<br>
                <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400 bg-clip-text text-transparent">
                    built for your mission.
                </span>
            </h2>

            <p class="reveal text-base text-white/45 leading-relaxed max-w-xl mx-auto" data-delay="2">
                From drag-and-drop editing to donation management — watch how FaithStack brings your organization's vision to life.
            </p>
        </div>

        {{-- Video frame --}}
        <div class="reveal relative" data-delay="3">

            {{-- Outer ambient halo --}}
            <div class="absolute -inset-6 lg:-inset-10
                        bg-gradient-to-r from-indigo-500/20 via-violet-500/15 to-purple-500/20
                        rounded-3xl blur-3xl pointer-events-none"></div>

            {{-- Browser chrome frame --}}
            <div class="relative rounded-2xl overflow-hidden
                        border border-white/[0.10]
                        shadow-[0_60px_120px_rgba(0,0,0,0.70),0_8px_32px_rgba(0,0,0,0.40),0_0_0_1px_rgba(255,255,255,0.06)]">

                {{-- Browser top bar --}}
                <div class="flex items-center gap-2 px-4 py-3
                            bg-zinc-900 border-b border-white/[0.07]">
                    {{-- Traffic lights --}}
                    <span class="w-3 h-3 rounded-full bg-red-500/70"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-500/70"></span>
                    <span class="w-3 h-3 rounded-full bg-green-500/70"></span>
                    {{-- URL bar --}}
                    <div class="flex-1 mx-3 px-3 py-1.5 rounded-md
                                bg-white/[0.06] border border-white/[0.08]
                                text-[11px] text-white/35 font-mono select-none flex items-center gap-2">
                        <svg class="w-3 h-3 text-emerald-400/70 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        app.faithstack.com
                    </div>
                    {{-- Live badge --}}
                    <div class="hidden sm:flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                bg-emerald-500/10 border border-emerald-500/20
                                text-[10px] font-semibold text-emerald-400">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-400"></span>
                        </span>
                        Live preview
                    </div>
                </div>

                {{-- Video --}}
                <div class="relative bg-black aspect-video">
                    <video
                        autoplay muted loop playsinline
                        preload="auto"
                        class="absolute inset-0 w-full h-full object-cover">
                        <source src="{{ asset('videos/3D_motion_graphics_SaaS_ad.mp4') }}" type="video/mp4">
                    </video>

                    {{-- Subtle bottom vignette --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent pointer-events-none"></div>
                </div>

            </div>

            {{-- Floating chips --}}
            <div class="float-chip absolute -left-6 sm:-left-12 top-[28%]
                        bg-white rounded-2xl px-3.5 py-2.5
                        shadow-[0_8px_32px_rgba(0,0,0,0.25)]
                        text-xs font-semibold text-slate-700
                        hidden sm:flex items-center gap-2 border border-slate-100"
                 style="--dur:6.5s;--rot:-2deg">
                <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white text-[9px] font-bold flex-shrink-0">✓</div>
                Theme applied!
            </div>

            <div class="float-chip absolute -right-6 sm:-right-12 top-[18%]
                        bg-white rounded-2xl px-3.5 py-2.5
                        shadow-[0_8px_32px_rgba(0,0,0,0.25)]
                        text-xs font-semibold text-slate-700
                        hidden sm:flex items-center gap-2 border border-slate-100"
                 style="--dur:5.5s;--rot:2deg;animation-delay:-2s">
                <span class="text-base">💰</span> $1,240 raised
            </div>

            <div class="float-chip absolute -right-4 sm:-right-10 bottom-[22%]
                        bg-white border border-slate-200 rounded-2xl px-3 py-2
                        shadow-[0_4px_16px_rgba(0,0,0,0.15)]
                        text-[11px] text-slate-600 hidden sm:flex items-center gap-2"
                 style="--dur:7s;--rot:1deg;animation-delay:-1s">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                </span>
                Live
            </div>

        </div>

        {{-- Bottom supporting stats --}}
        <div class="reveal mt-14 grid grid-cols-3 gap-4 max-w-lg mx-auto" data-delay="4">
            @foreach([
                ['stat' => '500+',   'label' => 'Organizations live'],
                ['stat' => '80+',    'label' => 'Premium themes'],
                ['stat' => '5 min',  'label' => 'Average setup time'],
            ] as $s)
            <div class="text-center p-4 rounded-2xl bg-white/[0.03] border border-white/[0.06]">
                <p class="text-xl font-bold text-white mb-1">{{ $s['stat'] }}</p>
                <p class="text-[11px] text-white/40 font-medium">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>

    </div>

    {{-- Bottom feather --}}
    <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-white to-transparent pointer-events-none z-20"></div>

</section>
