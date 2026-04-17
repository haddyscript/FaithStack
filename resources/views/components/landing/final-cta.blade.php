<section class="relative py-32 overflow-hidden bg-[#09090b]">

    {{-- Animated gradient background --}}
    <div class="absolute inset-0 gradient-animate opacity-80 pointer-events-none"
         style="background-image: linear-gradient(135deg, #09090b 0%, #1e1b4b 25%, #3b0764 50%, #1e1b4b 75%, #09090b 100%);"></div>

    {{-- Grid overlay --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:64px_64px] pointer-events-none"></div>

    {{-- Radial glow --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">

        {{-- Badge --}}
        <div class="reveal inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 bg-white/5 text-xs text-white/50 mb-10 font-medium">
            <span>✨</span>
            Free forever plan — no credit card required
        </div>

        {{-- Headline --}}
        <h2 class="reveal text-5xl lg:text-6xl xl:text-7xl font-bold text-white tracking-tight leading-[1.08] mb-6" data-delay="1">
            Start building your<br>
            <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                website today
            </span>
        </h2>

        <p class="reveal text-lg text-white/40 max-w-xl mx-auto mb-12 leading-relaxed" data-delay="2">
            Join 500+ organizations already using FaithStack to engage their communities, accept donations, and grow online.
        </p>

        {{-- CTAs --}}
        <div class="reveal flex flex-wrap justify-center gap-4 mb-14" data-delay="3">
            <a href="/superadmin/login"
               class="ripple-btn group inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition-all duration-300 shadow-2xl shadow-indigo-600/40 hover:shadow-indigo-600/60 hover:-translate-y-1">
                Start Free Trial
                <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            <a href="#features"
               class="inline-flex items-center gap-2 px-8 py-4 rounded-xl border border-white/10 hover:border-white/25 text-white/60 hover:text-white font-semibold text-sm transition-all duration-300 hover:bg-white/5 hover:-translate-y-1">
                Learn more
            </a>
        </div>

        {{-- Trust signals --}}
        <div class="reveal flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm text-white/20" data-delay="4">
            @foreach([
                ['SSL secured', 'M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z'],
                ['99.9% uptime', 'M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z'],
                ['GDPR compliant', 'M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z'],
                ['Cancel anytime', 'M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z'],
            ] as [$label, $path])
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="{{ $path }}" clip-rule="evenodd"/></svg>
                {{ $label }}
            </span>
            @endforeach
        </div>

    </div>
</section>
