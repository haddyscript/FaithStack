<section class="relative min-h-screen flex items-center overflow-hidden bg-[#09090b]">
    {{-- Background grid --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:64px_64px]"></div>

    {{-- Gradient orbs --}}
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            {{-- Left: copy --}}
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/10 bg-white/5 text-xs text-white/60 mb-8 font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Now in open beta — join 500+ organizations
                </div>

                <h1 class="text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.08] tracking-tight mb-6">
                    Build beautiful<br>
                    websites for your<br>
                    <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                        organization
                    </span>
                </h1>

                <p class="text-lg text-white/50 leading-relaxed mb-10 max-w-lg">
                    FaithStack is a multi-tenant CMS designed for churches, nonprofits, and community organizations. Pick a theme, customize your brand, and go live in minutes.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="/superadmin/login"
                       class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition-all shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/40 hover:-translate-y-0.5">
                        Start Free Trial
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="#themes"
                       class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl border border-white/10 hover:border-white/20 text-white/70 hover:text-white font-semibold text-sm transition-all hover:bg-white/5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/></svg>
                        View Themes
                    </a>
                </div>

                <div class="mt-12 flex items-center gap-6 text-sm text-white/30">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        No credit card
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        5-minute setup
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        Cancel anytime
                    </span>
                </div>
            </div>

            {{-- Right: browser mockup --}}
            <div class="hidden lg:block">
                <div class="relative mx-auto w-full max-w-lg">
                    {{-- Glow --}}
                    <div class="absolute -inset-4 bg-gradient-to-r from-indigo-600/20 to-purple-600/20 rounded-3xl blur-2xl"></div>

                    {{-- Browser frame --}}
                    <div class="relative rounded-2xl overflow-hidden border border-white/10 shadow-2xl bg-[#111113]">
                        {{-- Browser chrome --}}
                        <div class="flex items-center gap-2 px-4 py-3 bg-[#1a1a1e] border-b border-white/5">
                            <span class="w-3 h-3 rounded-full bg-red-500/60"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-500/60"></span>
                            <span class="w-3 h-3 rounded-full bg-green-500/60"></span>
                            <div class="flex-1 mx-3 px-3 py-1 rounded-md bg-white/5 text-xs text-white/20 font-mono">
                                yourchurch.faithstack.com
                            </div>
                        </div>

                        {{-- Mock website --}}
                        <div class="bg-slate-900 aspect-[4/3] flex flex-col overflow-hidden">
                            {{-- Nav --}}
                            <div class="flex items-center justify-between px-4 py-2.5 border-b border-white/5">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded bg-indigo-600"></div>
                                    <div class="w-16 h-2 rounded-full bg-white/20"></div>
                                </div>
                                <div class="hidden sm:flex gap-3">
                                    <div class="w-8 h-1.5 rounded-full bg-white/10"></div>
                                    <div class="w-8 h-1.5 rounded-full bg-white/10"></div>
                                    <div class="w-8 h-1.5 rounded-full bg-white/10"></div>
                                </div>
                                <div class="w-16 h-5 rounded-md bg-indigo-600/70"></div>
                            </div>

                            {{-- Hero --}}
                            <div class="flex-1 flex flex-col items-center justify-center gap-3 px-6 py-6 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">
                                <div class="w-48 h-3 rounded-full bg-white/70"></div>
                                <div class="w-36 h-3 rounded-full bg-white/40"></div>
                                <div class="w-24 h-2 rounded-full bg-white/20 mt-1"></div>
                                <div class="flex gap-2 mt-3">
                                    <div class="w-20 h-6 rounded-md bg-indigo-500"></div>
                                    <div class="w-20 h-6 rounded-md border border-white/20"></div>
                                </div>
                            </div>

                            {{-- Feature cards --}}
                            <div class="grid grid-cols-3 gap-1.5 px-3 pb-3">
                                @foreach(['bg-indigo-600/20', 'bg-purple-600/20', 'bg-blue-600/20'] as $c)
                                <div class="rounded-lg {{ $c }} p-2">
                                    <div class="w-4 h-4 rounded bg-white/20 mb-1.5"></div>
                                    <div class="w-full h-1.5 rounded-full bg-white/20 mb-1"></div>
                                    <div class="w-3/4 h-1.5 rounded-full bg-white/10"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Floating badges --}}
                    <div class="absolute -left-8 top-1/3 bg-white rounded-xl px-3 py-2 shadow-xl text-xs font-semibold text-slate-800 flex items-center gap-2">
                        <span class="text-emerald-500">✓</span> Theme applied
                    </div>
                    <div class="absolute -right-6 bottom-1/4 bg-white rounded-xl px-3 py-2 shadow-xl text-xs font-semibold text-slate-800 flex items-center gap-2">
                        <span>💰</span> $1,240 raised
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
