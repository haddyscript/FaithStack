<section id="modules" class="py-28 relative overflow-hidden" style="background: #09090b">

    {{-- Ambient background glow blobs --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="blob absolute -top-32 left-1/4 w-96 h-96 rounded-full opacity-20"
             style="background: radial-gradient(circle, #4f46e5, transparent 70%); --dur: 22s"></div>
        <div class="blob absolute bottom-0 right-1/4 w-80 h-80 rounded-full opacity-15"
             style="background: radial-gradient(circle, #7c3aed, transparent 70%); --dur: 27s"></div>
        <div class="blob absolute top-1/2 -right-20 w-64 h-64 rounded-full opacity-10"
             style="background: radial-gradient(circle, #2563eb, transparent 70%); --dur: 18s"></div>
        {{-- Dot grid --}}
        <div class="absolute inset-0"
             style="background-image: radial-gradient(circle, rgba(255,255,255,.04) 1px, transparent 1px); background-size: 28px 28px;"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">

        {{-- ── Section Header ── --}}
        <div class="text-center mb-16">
            <div class="reveal inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border mb-5"
                 style="border-color: rgba(99,102,241,0.35); background: rgba(99,102,241,0.08)">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:#6366f1"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2" style="background:#6366f1"></span>
                </span>
                <span class="text-xs font-semibold tracking-widest uppercase" style="color:#a5b4fc">Platform Modules</span>
            </div>
            <h2 class="reveal text-4xl lg:text-5xl font-bold tracking-tight text-white mb-5 leading-tight" data-delay="1">
                Everything you need to<br>
                <span class="bg-clip-text text-transparent" style="background-image: linear-gradient(135deg,#a5b4fc 0%,#c084fc 50%,#818cf8 100%)">run your church</span>
            </h2>
            <p class="reveal text-lg max-w-2xl mx-auto leading-relaxed" style="color: rgba(255,255,255,0.5)" data-delay="2">
                One platform, ten powerful modules. Launch what you need today and activate more as your community grows.
            </p>
        </div>

        {{-- ── Modules Grid ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            {{-- 1 - Website Builder --}}
            <div class="module-card reveal group relative rounded-2xl border p-5 cursor-default overflow-hidden"
                 style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.07)"
                 data-delay="1">
                <div class="module-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-500"
                     style="background: radial-gradient(180px circle at 50% 0%, rgba(99,102,241,0.2), transparent)"></div>
                <div class="relative z-10">
                    <div class="module-icon w-11 h-11 rounded-xl flex items-center justify-center mb-4 transition-transform duration-300"
                         style="background: rgba(99,102,241,0.15)">
                        <svg class="w-5 h-5" fill="none" stroke="#818cf8" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white text-sm mb-1.5 leading-snug">Website Builder</h3>
                    <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.45)">Launch a beautiful church website with drag-and-drop simplicity.</p>
                </div>
                <div class="module-border-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300" style="box-shadow: inset 0 0 0 1px rgba(99,102,241,0.4)"></div>
            </div>

            {{-- 2 - Member Management (CRM) — HIGHLIGHTED --}}
            <div class="module-card reveal group relative rounded-2xl p-5 cursor-default overflow-hidden sm:col-span-2 lg:col-span-1"
                 style="background: linear-gradient(135deg, rgba(79,70,229,0.18) 0%, rgba(124,58,237,0.12) 100%); border: 1px solid rgba(129,140,248,0.35)"
                 data-delay="2">
                {{-- Glow shimmer --}}
                <div class="absolute inset-0 rounded-2xl pointer-events-none"
                     style="background: radial-gradient(120px circle at 30% 20%, rgba(129,140,248,0.2), transparent)"></div>
                {{-- "In Development" badge --}}
                <div class="absolute top-3.5 right-3.5">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide"
                          style="background: rgba(99,102,241,0.25); color: #a5b4fc; border: 1px solid rgba(129,140,248,0.3)">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 inline-block animate-pulse"></span>
                        Ongoing
                    </span>
                </div>
                <div class="relative z-10">
                    <div class="module-icon w-11 h-11 rounded-xl flex items-center justify-center mb-4 transition-transform duration-300"
                         style="background: rgba(99,102,241,0.25)">
                        <svg class="w-5 h-5" fill="none" stroke="#a5b4fc" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white text-sm mb-1.5 leading-snug">Member Management</h3>
                    <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.55)">Track profiles, groups, and member journeys from one unified CRM.</p>
                </div>
                {{-- Animated border glow — opacity driven by CSS @keyframes crmGlowPulse (no paint) --}}
                <div class="js-crm-glow absolute inset-0 rounded-2xl pointer-events-none" style="box-shadow: 0 0 30px rgba(99,102,241,0.12), inset 0 0 0 1px rgba(129,140,248,0.35)"></div>
            </div>

            {{-- 3 - Giving / Payments --}}
            <div class="module-card reveal group relative rounded-2xl border p-5 cursor-default overflow-hidden"
                 style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.07)"
                 data-delay="3">
                <div class="module-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-500"
                     style="background: radial-gradient(180px circle at 50% 0%, rgba(16,185,129,0.2), transparent)"></div>
                <div class="relative z-10">
                    <div class="module-icon w-11 h-11 rounded-xl flex items-center justify-center mb-4 transition-transform duration-300"
                         style="background: rgba(16,185,129,0.15)">
                        <svg class="w-5 h-5" fill="none" stroke="#34d399" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white text-sm mb-1.5 leading-snug">Giving & Payments</h3>
                    <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.45)">Accept tithes and donations online with secure payment processing.</p>
                </div>
                <div class="module-border-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300" style="box-shadow: inset 0 0 0 1px rgba(16,185,129,0.4)"></div>
            </div>

            {{-- 4 - Events --}}
            <div class="module-card reveal group relative rounded-2xl border p-5 cursor-default overflow-hidden"
                 style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.07)"
                 data-delay="4">
                <div class="module-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-500"
                     style="background: radial-gradient(180px circle at 50% 0%, rgba(245,158,11,0.2), transparent)"></div>
                <div class="relative z-10">
                    <div class="module-icon w-11 h-11 rounded-xl flex items-center justify-center mb-4 transition-transform duration-300"
                         style="background: rgba(245,158,11,0.15)">
                        <svg class="w-5 h-5" fill="none" stroke="#fbbf24" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white text-sm mb-1.5 leading-snug">Events</h3>
                    <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.45)">Plan, publish, and promote services, conferences, and community gatherings.</p>
                </div>
                <div class="module-border-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300" style="box-shadow: inset 0 0 0 1px rgba(245,158,11,0.4)"></div>
            </div>

            {{-- 5 - Sermons / Media --}}
            <div class="module-card reveal group relative rounded-2xl border p-5 cursor-default overflow-hidden"
                 style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.07)"
                 data-delay="5">
                <div class="module-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-500"
                     style="background: radial-gradient(180px circle at 50% 0%, rgba(236,72,153,0.18), transparent)"></div>
                <div class="relative z-10">
                    <div class="module-icon w-11 h-11 rounded-xl flex items-center justify-center mb-4 transition-transform duration-300"
                         style="background: rgba(236,72,153,0.15)">
                        <svg class="w-5 h-5" fill="none" stroke="#f472b6" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.868v6.264a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white text-sm mb-1.5 leading-snug">Sermons & Media</h3>
                    <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.45)">Host and stream sermons, podcasts, and worship media for your community.</p>
                </div>
                <div class="module-border-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300" style="box-shadow: inset 0 0 0 1px rgba(236,72,153,0.4)"></div>
            </div>

            {{-- 6 - Messaging (Email Only) --}}
            <div class="module-card reveal group relative rounded-2xl border p-5 cursor-default overflow-hidden"
                 style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.07)"
                 data-delay="1">
                <div class="module-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-500"
                     style="background: radial-gradient(180px circle at 50% 0%, rgba(59,130,246,0.2), transparent)"></div>
                <div class="relative z-10">
                    <div class="module-icon w-11 h-11 rounded-xl flex items-center justify-center mb-4 transition-transform duration-300"
                         style="background: rgba(59,130,246,0.15)">
                        <svg class="w-5 h-5" fill="none" stroke="#60a5fa" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white text-sm mb-1.5 leading-snug">Messaging</h3>
                    <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.45)">Send announcements and updates directly to your members' inbox.</p>
                    <span class="mt-2 inline-block text-[10px] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded"
                          style="color: rgba(96,165,250,0.8); background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2)">Email only</span>
                </div>
                <div class="module-border-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300" style="box-shadow: inset 0 0 0 1px rgba(59,130,246,0.4)"></div>
            </div>

            {{-- 7 - Prayer Requests --}}
            <div class="module-card reveal group relative rounded-2xl border p-5 cursor-default overflow-hidden"
                 style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.07)"
                 data-delay="2">
                <div class="module-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-500"
                     style="background: radial-gradient(180px circle at 50% 0%, rgba(244,63,94,0.18), transparent)"></div>
                <div class="relative z-10">
                    <div class="module-icon w-11 h-11 rounded-xl flex items-center justify-center mb-4 transition-transform duration-300"
                         style="background: rgba(244,63,94,0.15)">
                        <svg class="w-5 h-5" fill="none" stroke="#fb7185" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white text-sm mb-1.5 leading-snug">Prayer Requests</h3>
                    <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.45)">Collect, organize, and respond to prayer needs from your congregation.</p>
                </div>
                <div class="module-border-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300" style="box-shadow: inset 0 0 0 1px rgba(244,63,94,0.4)"></div>
            </div>

            {{-- 8 - Volunteer Management --}}
            <div class="module-card reveal group relative rounded-2xl border p-5 cursor-default overflow-hidden"
                 style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.07)"
                 data-delay="3">
                <div class="module-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-500"
                     style="background: radial-gradient(180px circle at 50% 0%, rgba(20,184,166,0.2), transparent)"></div>
                <div class="relative z-10">
                    <div class="module-icon w-11 h-11 rounded-xl flex items-center justify-center mb-4 transition-transform duration-300"
                         style="background: rgba(20,184,166,0.15)">
                        <svg class="w-5 h-5" fill="none" stroke="#2dd4bf" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white text-sm mb-1.5 leading-snug">Volunteer Management</h3>
                    <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.45)">Schedule, coordinate, and empower volunteers across all ministries.</p>
                </div>
                <div class="module-border-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300" style="box-shadow: inset 0 0 0 1px rgba(20,184,166,0.4)"></div>
            </div>

            {{-- 9 - Attendance Tracking --}}
            <div class="module-card reveal group relative rounded-2xl border p-5 cursor-default overflow-hidden"
                 style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.07)"
                 data-delay="4">
                <div class="module-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-500"
                     style="background: radial-gradient(180px circle at 50% 0%, rgba(168,85,247,0.2), transparent)"></div>
                <div class="relative z-10">
                    <div class="module-icon w-11 h-11 rounded-xl flex items-center justify-center mb-4 transition-transform duration-300"
                         style="background: rgba(168,85,247,0.15)">
                        <svg class="w-5 h-5" fill="none" stroke="#c084fc" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white text-sm mb-1.5 leading-snug">Attendance Tracking</h3>
                    <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.45)">Monitor service attendance and engagement trends across your church.</p>
                </div>
                <div class="module-border-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300" style="box-shadow: inset 0 0 0 1px rgba(168,85,247,0.4)"></div>
            </div>

            {{-- 10 - Analytics Dashboard --}}
            <div class="module-card reveal group relative rounded-2xl border p-5 cursor-default overflow-hidden"
                 style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.07)"
                 data-delay="5">
                <div class="module-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-500"
                     style="background: radial-gradient(180px circle at 50% 0%, rgba(234,179,8,0.18), transparent)"></div>
                <div class="relative z-10">
                    <div class="module-icon w-11 h-11 rounded-xl flex items-center justify-center mb-4 transition-transform duration-300"
                         style="background: rgba(234,179,8,0.15)">
                        <svg class="w-5 h-5" fill="none" stroke="#facc15" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white text-sm mb-1.5 leading-snug">Analytics Dashboard</h3>
                    <p class="text-xs leading-relaxed" style="color: rgba(255,255,255,0.45)">Gain clear insights into growth, giving, and engagement across your org.</p>
                </div>
                <div class="module-border-glow absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300" style="box-shadow: inset 0 0 0 1px rgba(234,179,8,0.4)"></div>
            </div>

        </div>

        {{-- ── Bottom CTA strip ── --}}
        <div class="reveal mt-14 text-center" data-delay="3">
            <p class="text-sm mb-4" style="color: rgba(255,255,255,0.35)">All modules included in every plan. No add-on fees.</p>
            <a href="{{ url('/register') }}"
               class="ripple-btn btn-spring inline-flex items-center gap-2.5 px-7 py-3.5 rounded-2xl text-sm font-semibold text-white no-underline"
               style="background: linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%); box-shadow: 0 0 30px rgba(99,102,241,0.35)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Start Building for Free
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

    </div>
</section>

{{-- Module card hover styles (added here so they scope only to this component) --}}
<style>
    .module-card {
        transition: transform 0.3s cubic-bezier(0.16,1,0.3,1),
                    border-color 0.3s ease,
                    box-shadow 0.3s ease;
        will-change: transform;
    }
    .module-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255,255,255,0.14) !important;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }
    .module-card:hover .module-glow,
    .module-card:hover .module-border-glow { opacity: 1; }
    .module-card:hover .module-icon {
        transform: scale(1.15) rotate(8deg);
    }
    .module-icon { transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1); }

    @media (prefers-reduced-motion: reduce) {
        .module-card, .module-icon { transition: none !important; transform: none !important; }
        .module-glow, .module-border-glow { display: none; }
    }
</style>
