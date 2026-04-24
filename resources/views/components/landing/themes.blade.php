@props(['themes'])

<section id="themes" class="py-28 bg-slate-50" x-data="themeGallery">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <p class="reveal text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Theme Library</p>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5" data-delay="1">
                Beautiful themes for<br>every organization
            </h2>
            <p class="reveal text-lg text-slate-500 max-w-xl mx-auto" data-delay="2">
                80+ professionally designed themes across 13 categories. Find yours, apply it instantly, and customize every detail.
            </p>
        </div>

        {{-- Grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($themes as $i => $theme)
            @php $themeJson = json_encode($theme); @endphp
            <div class="reveal theme-card group relative rounded-2xl overflow-hidden bg-white border border-slate-100 cursor-pointer select-none"
                 data-delay="{{ $i + 1 }}"
                 data-tilt="6"
                 @click="open({{ $themeJson }})">

                {{-- Preview --}}
                <div class="relative h-56 overflow-hidden">
                    <div class="theme-preview-img absolute inset-0 bg-gradient-to-br {{ $theme['gradient'] }}">
                        {{-- Subtle grid texture --}}
                        <div class="absolute inset-0 opacity-[0.07]"
                             style="background-image:linear-gradient(rgba(255,255,255,.15) 1px,transparent 1px),linear-gradient(to right,rgba(255,255,255,.15) 1px,transparent 1px);background-size:20px 20px;"></div>

                        {{-- ── BROWSER SHELL ── --}}
                        <div class="absolute inset-2.5 rounded-xl overflow-hidden flex flex-col"
                             style="box-shadow:0 8px 32px rgba(0,0,0,0.40);border:1px solid rgba(255,255,255,0.20)">

                            {{-- Chrome bar --}}
                            <div class="flex items-center gap-1 px-2 py-1.5 bg-white border-b border-slate-200 flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                <div class="flex-1 mx-1.5 h-3 rounded bg-slate-100 flex items-center px-1.5 overflow-hidden">
                                    @if($theme['slug'] === 'church')
                                        <span class="text-[4.5px] text-slate-400 font-mono truncate">gracecommunity.church</span>
                                    @elseif($theme['slug'] === 'saas')
                                        <span class="text-[4.5px] text-slate-400 font-mono truncate">app.orbitplatform.io/dashboard</span>
                                    @elseif($theme['slug'] === 'ecommerce')
                                        <span class="text-[4.5px] text-slate-400 font-mono truncate">shopnova.store/new-arrivals</span>
                                    @elseif($theme['slug'] === 'portfolio')
                                        <span class="text-[4.5px] text-slate-400 font-mono truncate">johndoe.design</span>
                                    @elseif($theme['slug'] === 'agency')
                                        <span class="text-[4.5px] text-slate-400 font-mono truncate">apexcreative.agency</span>
                                    @else
                                        <span class="text-[4.5px] text-slate-400 font-mono truncate">bistronoir.restaurant</span>
                                    @endif
                                </div>
                            </div>

                            {{-- ── WEBSITE CONTENT ── --}}
                            <div class="flex-1 overflow-hidden">

                            @if($theme['slug'] === 'church')
                            {{-- ════ CHURCH & MINISTRY ════ --}}
                            <div class="h-full flex flex-col bg-white">
                                {{-- Nav --}}
                                <div class="flex items-center justify-between px-3 py-1.5 border-b border-violet-100 bg-white flex-shrink-0">
                                    <div class="flex items-center gap-1">
                                        <div class="w-3.5 h-3.5 rounded bg-violet-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd"/></svg>
                                        </div>
                                        <span class="text-[6px] font-bold text-slate-800">Grace Church</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="text-[5px] text-slate-500">Sermons</span>
                                        <span class="text-[5px] text-slate-500">Events</span>
                                        <span class="text-[5px] text-slate-500">Give</span>
                                    </div>
                                    <div class="px-1.5 py-0.5 bg-violet-600 rounded text-[4.5px] text-white font-bold">Join Us</div>
                                </div>
                                {{-- Hero --}}
                                <div class="flex-1 flex flex-col items-center justify-center bg-gradient-to-b from-violet-50 to-white px-3">
                                    <svg class="w-5 h-5 text-violet-500 mb-1.5" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 2v20M2 12h20" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                        <path d="M5 9h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.4"/>
                                    </svg>
                                    <div class="text-[7.5px] font-bold text-slate-900 text-center leading-tight mb-0.5">Welcome to Grace<br>Community Church</div>
                                    <div class="text-[5px] text-slate-400 mb-2 text-center">Find a Service Near You</div>
                                    <div class="flex gap-1.5">
                                        <div class="px-2.5 py-0.5 bg-violet-600 rounded text-[4.5px] text-white font-bold">Watch Online</div>
                                        <div class="px-2.5 py-0.5 border border-slate-300 rounded text-[4.5px] text-slate-600">Get Directions</div>
                                    </div>
                                </div>
                                {{-- Service times --}}
                                <div class="flex gap-1 px-2 py-1.5 bg-slate-50 border-t border-slate-100 flex-shrink-0">
                                    @foreach(['Sun 8:00 AM','Sun 10:30 AM','Wed 7:00 PM'] as $time)
                                    <div class="flex-1 py-0.5 rounded bg-white border border-slate-200 text-center">
                                        <div class="text-[4.5px] font-bold text-violet-700">{{ $time }}</div>
                                        <div class="text-[3.5px] text-slate-400">In Person</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @elseif($theme['slug'] === 'saas')
                            {{-- ════ SAAS & TECHNOLOGY ════ --}}
                            <div class="h-full flex flex-col" style="background:#0f172a">
                                {{-- Nav --}}
                                <div class="flex items-center justify-between px-3 py-1.5 border-b flex-shrink-0" style="border-color:rgba(255,255,255,0.06)">
                                    <div class="flex items-center gap-1">
                                        <div class="w-3 h-3 rounded bg-blue-500 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0z"/></svg>
                                        </div>
                                        <span class="text-[6px] font-bold text-white">Orbit SaaS</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="text-[5px]" style="color:rgba(255,255,255,0.45)">Analytics</span>
                                        <span class="text-[5px]" style="color:rgba(255,255,255,0.45)">Teams</span>
                                        <span class="text-[5px]" style="color:rgba(255,255,255,0.45)">API</span>
                                    </div>
                                    <div class="px-1.5 py-0.5 bg-blue-500 rounded text-[4.5px] text-white font-bold">Try Free</div>
                                </div>
                                {{-- Metric cards --}}
                                <div class="flex gap-1 px-2 py-1.5 flex-shrink-0">
                                    @foreach([['Users','12.4K','+8.2%'],['Revenue','$48.2K','+12.1%'],['Uptime','99.9%','+0.1%']] as $stat)
                                    <div class="flex-1 rounded-lg px-1.5 py-1" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.07)">
                                        <div class="text-[4px] mb-0.5" style="color:rgba(255,255,255,0.35)">{{ $stat[0] }}</div>
                                        <div class="text-[7px] font-bold text-white">{{ $stat[1] }}</div>
                                        <div class="text-[4px] text-emerald-400 font-medium">{{ $stat[2] }}</div>
                                    </div>
                                    @endforeach
                                </div>
                                {{-- SVG Line chart --}}
                                <div class="flex-1 px-2 pb-0.5 flex flex-col">
                                    <div class="text-[4px] mb-0.5" style="color:rgba(255,255,255,0.28)">Monthly Revenue Trend</div>
                                    <svg viewBox="0 0 200 52" class="w-full flex-1" preserveAspectRatio="none">
                                        <defs>
                                            <linearGradient id="cg{{ $i }}" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.35"/>
                                                <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.02"/>
                                            </linearGradient>
                                        </defs>
                                        <line x1="0" y1="13" x2="200" y2="13" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                                        <line x1="0" y1="26" x2="200" y2="26" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                                        <line x1="0" y1="39" x2="200" y2="39" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                                        <path d="M0,48 L28,42 L56,36 L84,28 L112,22 L140,15 L168,9 L200,4 L200,52 L0,52 Z" fill="url(#cg{{ $i }})"/>
                                        <polyline points="0,48 28,42 56,36 84,28 112,22 140,15 168,9 200,4"
                                                  fill="none" stroke="#3b82f6" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="0"   cy="48" r="2" fill="#3b82f6"/>
                                        <circle cx="56"  cy="36" r="2" fill="#3b82f6"/>
                                        <circle cx="112" cy="22" r="2" fill="#3b82f6"/>
                                        <circle cx="168" cy="9"  r="2" fill="#3b82f6"/>
                                        <circle cx="200" cy="4"  r="2.5" fill="#60a5fa"/>
                                    </svg>
                                </div>
                                {{-- Feature chips --}}
                                <div class="flex gap-1 px-2 py-1 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,0.05)">
                                    @foreach(['Real-time Analytics','Team Collab','API Access'] as $f)
                                    <div class="px-1.5 py-0.5 rounded text-[4px] font-medium" style="background:rgba(59,130,246,0.14);border:1px solid rgba(59,130,246,0.22);color:#93c5fd">{{ $f }}</div>
                                    @endforeach
                                </div>
                            </div>

                            @elseif($theme['slug'] === 'ecommerce')
                            {{-- ════ ONLINE STORE ════ --}}
                            <div class="h-full flex flex-col bg-white">
                                {{-- Nav --}}
                                <div class="flex items-center justify-between px-3 py-1.5 border-b border-slate-100 flex-shrink-0 bg-white">
                                    <span class="text-[7px] font-black text-slate-900 tracking-tight">ShopNova</span>
                                    <div class="flex gap-2">
                                        <span class="text-[5px] text-slate-500">New</span>
                                        <span class="text-[5px] text-slate-500">Sale</span>
                                        <span class="text-[5px] text-slate-500">Brands</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <div class="relative">
                                            <svg class="w-2.5 h-2.5 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                                            <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-emerald-500 rounded-full text-[3.5px] text-white flex items-center justify-center font-bold">2</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- Promo banner --}}
                                <div class="px-3 py-1 bg-emerald-500 flex-shrink-0 flex items-center justify-between">
                                    <div class="text-[5.5px] font-bold text-white">New Arrivals — Shop Now &amp; Save</div>
                                    <div class="text-[4px] text-emerald-100">Free shipping over $50</div>
                                </div>
                                {{-- Product grid --}}
                                <div class="flex-1 grid grid-cols-3 gap-1.5 p-2">
                                    @foreach([
                                        ['from-amber-200 to-amber-300','Leather Boots','$89.99'],
                                        ['from-slate-200 to-slate-300','Travel Mug','$34.99'],
                                        ['from-stone-200 to-stone-300','Canvas Pack','$64.99'],
                                    ] as $prod)
                                    <div class="flex flex-col">
                                        <div class="rounded-lg bg-gradient-to-br {{ $prod[0] }} h-10 w-full mb-1 flex items-center justify-center">
                                            <div class="w-5 h-5 rounded bg-white/50"></div>
                                        </div>
                                        <div class="text-[4.5px] font-semibold text-slate-800 leading-tight">{{ $prod[1] }}</div>
                                        <div class="flex gap-px mt-0.5">
                                            @for($s=0;$s<5;$s++)<span class="text-amber-400" style="font-size:4.5px;line-height:1">★</span>@endfor
                                        </div>
                                        <div class="text-[5px] font-bold text-emerald-700 mt-0.5">{{ $prod[2] }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @elseif($theme['slug'] === 'portfolio')
                            {{-- ════ CREATIVE PORTFOLIO ════ --}}
                            <div class="h-full flex flex-col" style="background:#080808">
                                {{-- Nav --}}
                                <div class="flex items-center justify-between px-3 py-1.5 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                                    <span class="text-[8px] font-black text-white tracking-[0.15em]">JD</span>
                                    <div class="flex gap-2">
                                        <span class="text-[5px]" style="color:rgba(255,255,255,0.45)">Work</span>
                                        <span class="text-[5px]" style="color:rgba(255,255,255,0.45)">About</span>
                                        <span class="text-[5px]" style="color:rgba(255,255,255,0.45)">Contact</span>
                                    </div>
                                    <div class="w-4 h-4 rounded-full flex items-center justify-center" style="border:1px solid rgba(255,255,255,0.18)">
                                        <svg class="w-2 h-2" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/></svg>
                                    </div>
                                </div>
                                {{-- Hero tagline --}}
                                <div class="px-3 py-2 flex-shrink-0">
                                    <div class="text-[4.5px] uppercase tracking-[0.18em] mb-0.5" style="color:rgba(255,255,255,0.35)">Visual Designer</div>
                                    <div class="text-[10px] font-black text-white leading-none">John Doe</div>
                                    <div class="text-[4.5px] mt-0.5" style="color:rgba(255,255,255,0.4)">View My Work →</div>
                                </div>
                                {{-- Gallery grid --}}
                                <div class="flex-1 grid grid-cols-3 grid-rows-2 gap-1 px-2 pb-2">
                                    <div class="col-span-2 row-span-1 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-700 flex items-center justify-center">
                                        <span class="text-[5px] text-white/70 font-semibold">Branding</span>
                                    </div>
                                    <div class="rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                                        <span class="text-[4.5px] text-white/80 font-semibold">Logo</span>
                                    </div>
                                    <div class="rounded-lg bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center">
                                        <span class="text-[4.5px] text-white/80 font-semibold">Print</span>
                                    </div>
                                    <div class="rounded-lg bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center">
                                        <span class="text-[4.5px] text-white/70 font-semibold">Web</span>
                                    </div>
                                    <div class="rounded-lg bg-gradient-to-br from-rose-400 to-pink-600 flex items-center justify-center">
                                        <span class="text-[4.5px] text-white/80 font-semibold">Photo</span>
                                    </div>
                                </div>
                            </div>

                            @elseif($theme['slug'] === 'agency')
                            {{-- ════ CREATIVE AGENCY ════ --}}
                            <div class="h-full flex flex-col" style="background:#180a02">
                                {{-- Nav --}}
                                <div class="flex items-center justify-between px-3 py-1.5 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                                    <span class="text-[7px] font-black text-white tracking-[0.12em]">APEX</span>
                                    <div class="flex gap-2">
                                        <span class="text-[5px]" style="color:rgba(255,255,255,0.45)">Services</span>
                                        <span class="text-[5px]" style="color:rgba(255,255,255,0.45)">Portfolio</span>
                                        <span class="text-[5px]" style="color:rgba(255,255,255,0.45)">About</span>
                                    </div>
                                    <div class="px-1.5 py-0.5 rounded text-[4.5px] font-semibold" style="border:1px solid rgba(251,146,60,0.6);color:#fb923c">Talk to Us</div>
                                </div>
                                {{-- Hero --}}
                                <div class="flex-1 relative flex flex-col justify-between overflow-hidden">
                                    <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(154,52,18,0.45) 0%,rgba(159,18,57,0.25) 100%)"></div>
                                    {{-- Faint image blocks simulating office photo --}}
                                    <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-25" style="background:linear-gradient(180deg,#fdba74,#f97316,#ea580c)"></div>
                                    <div class="absolute right-2 top-2 w-10 h-8 rounded bg-orange-600/30" style="backdrop-filter:blur(2px)"></div>
                                    <div class="absolute right-6 top-5 w-6 h-5 rounded bg-rose-500/20" style="backdrop-filter:blur(2px)"></div>
                                    {{-- Text --}}
                                    <div class="relative px-3 pt-2">
                                        <div class="text-[4.5px] uppercase tracking-[0.14em] mb-0.5" style="color:#fb923c">Creative Agency</div>
                                        <div class="text-[9px] font-black text-white leading-tight mb-0.5">Ignite Your<br><span style="color:#fb923c">Brand</span></div>
                                        <div class="text-[4.5px] mb-2" style="color:rgba(255,255,255,0.45)">with Apex Agency</div>
                                        <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[4.5px] text-white font-bold" style="background:#ea580c">View Portfolio →</div>
                                    </div>
                                    {{-- Stats bar --}}
                                    <div class="relative flex gap-1 px-3 py-1.5 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,0.07)">
                                        @foreach([['50+','Projects'],['8 Yrs','Experience'],['98%','Retention']] as $s)
                                        <div class="flex-1 text-center">
                                            <div class="text-[6.5px] font-black text-white">{{ $s[0] }}</div>
                                            <div class="text-[3.5px]" style="color:rgba(255,255,255,0.38)">{{ $s[1] }}</div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @else
                            {{-- ════ RESTAURANT & FOOD ════ --}}
                            <div class="h-full flex flex-col" style="background:#fdf8f3">
                                {{-- Nav --}}
                                <div class="flex items-center justify-between px-3 py-1.5 border-b border-stone-200 flex-shrink-0 bg-white">
                                    <span class="text-[6.5px] font-bold text-stone-900" style="font-family:Georgia,serif;letter-spacing:0.03em">Bistro Noir</span>
                                    <div class="flex gap-2">
                                        <span class="text-[5px] text-stone-500">Menu</span>
                                        <span class="text-[5px] text-stone-500">Reserve</span>
                                        <span class="text-[5px] text-stone-500">About</span>
                                    </div>
                                    <div class="px-1.5 py-0.5 bg-stone-900 rounded text-[4.5px] text-white font-bold">Book a Table</div>
                                </div>
                                {{-- Hero --}}
                                <div class="flex-1 relative overflow-hidden px-3 pt-2">
                                    {{-- Decorative plate (CSS art) --}}
                                    <div class="absolute right-2 top-1 w-20 h-20 rounded-full flex items-center justify-center"
                                         style="background:radial-gradient(circle at 55% 40%,#fde68a,#fbbf24,#d97706)">
                                        <div class="absolute w-3 h-2 rounded-full bg-green-600/55 top-4 left-4"></div>
                                        <div class="absolute w-2.5 h-2.5 rounded-full bg-red-500/50 top-3 right-4"></div>
                                        <div class="absolute w-4 h-1.5 rounded-full bg-amber-900/40 bottom-4 left-4"></div>
                                        <div class="absolute w-2 h-2 rounded-full bg-yellow-300/60 bottom-5 right-5"></div>
                                    </div>
                                    {{-- Copy --}}
                                    <div class="text-[4.5px] uppercase tracking-[0.14em] text-stone-400 mb-0.5">Fine Dining</div>
                                    <div class="text-[8.5px] font-bold text-stone-900 leading-tight mb-0.5" style="font-family:Georgia,serif">Authentic<br>Bistro Cuisine</div>
                                    <div class="text-[4.5px] text-stone-400 mb-2">Reserve Your Table Today</div>
                                    <div class="flex gap-1">
                                        <div class="px-2.5 py-0.5 bg-stone-900 rounded text-[4.5px] text-white font-bold">Browse Menu</div>
                                        <div class="px-2.5 py-0.5 border border-stone-300 rounded text-[4.5px] text-stone-700">Book Now</div>
                                    </div>
                                </div>
                                {{-- Today's special --}}
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-amber-50 border-t border-amber-100 flex-shrink-0">
                                    <div class="w-5 h-5 rounded bg-amber-200 flex-shrink-0 flex items-center justify-center">
                                        <div class="w-3 h-3 rounded-full bg-gradient-to-br from-amber-400 to-orange-500"></div>
                                    </div>
                                    <div>
                                        <div class="text-[4.5px] font-bold text-stone-800">Today's Special: Truffle Pasta</div>
                                        <div class="text-[3.5px] text-stone-500">$28 · Limited availability</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            </div>{{-- end website content --}}
                        </div>{{-- end browser shell --}}
                    </div>{{-- end theme-preview-img --}}

                    {{-- Hover overlay --}}
                    <div class="theme-overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-black/20 flex flex-col items-center justify-center gap-3">
                        <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-slate-900 text-sm font-semibold shadow-2xl transform group-hover:scale-105 group-hover:-translate-y-0.5 transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Preview Theme
                        </span>
                        <span class="text-white/60 text-xs">{{ $theme['count'] }} themes in this category</span>
                    </div>

                    {{-- Count badge --}}
                    <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-md text-white text-xs font-semibold px-2.5 py-1 rounded-full border border-white/10">
                        {{ $theme['count'] }} themes
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-5">
                    <h3 class="font-semibold text-slate-900 mb-2.5 group-hover:text-indigo-600 transition-colors duration-200">
                        {{ $theme['name'] }}
                    </h3>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($theme['tags'] as $tag)
                        <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 group-hover:bg-indigo-50 text-slate-500 group-hover:text-indigo-600 text-xs font-medium transition-colors duration-200">
                            {{ $tag }}
                        </span>
                        @endforeach
                    </div>
                </div>

                {{-- Mouse-tracking glare overlay --}}
                <div class="tilt-glare absolute inset-0 pointer-events-none rounded-2xl" style="z-index:5;"></div>

            </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div class="reveal text-center mt-14" data-delay="2">
            <p class="text-slate-400 mb-5 text-sm">Plus Education, Health, Real Estate, Events, Travel, Beauty, Automotive &amp; more</p>
            <a href="{{ url('/register') }}?plan=free-trial"
               class="ripple-btn inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition-all duration-300 shadow-lg shadow-slate-900/15 hover:-translate-y-0.5">
                Browse all themes
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

    </div>

    {{-- ── Preview Modal ── --}}
    <div x-cloak
         x-show="active"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-180"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="close()"
         @keydown.escape.window="close()"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md">

        <div x-show="active"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-3xl bg-[#0f0f11] rounded-3xl border border-white/10 shadow-[0_60px_120px_rgba(0,0,0,0.8)] overflow-hidden">

            {{-- Modal header --}}
            <div class="flex items-center justify-between px-7 py-5 border-b border-white/[0.06]">
                <div>
                    <h3 class="text-white font-bold text-lg" x-text="active?.name"></h3>
                    <p class="text-white/40 text-xs mt-0.5" x-text="(active?.count || 0) + ' themes in this category'"></p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ url('/register') }}?plan=free-trial"
                       class="ripple-btn inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold transition-colors">
                        Use this theme
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <button @click="close()" class="w-8 h-8 rounded-lg border border-white/10 flex items-center justify-center text-white/40 hover:text-white hover:border-white/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Live preview (scrollable mock page) --}}
            <div class="relative overflow-hidden" style="height: 440px;">
                <div class="absolute inset-0" :class="active ? 'bg-gradient-to-br ' + active.gradient : ''"></div>
                <div class="absolute inset-0 opacity-[0.07]"
                     style="background-image:linear-gradient(rgba(255,255,255,.1) 1px,transparent 1px),linear-gradient(to right,rgba(255,255,255,.1) 1px,transparent 1px);background-size:32px 32px;"></div>

                <div class="absolute inset-x-8 top-6 bottom-0 overflow-y-auto rounded-t-xl border border-white/15 bg-black/25 backdrop-blur-sm">
                    <div class="sticky top-0 flex items-center justify-between px-4 py-2.5 bg-black/40 backdrop-blur-md border-b border-white/10">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded bg-white/30"></div>
                            <div class="w-16 h-2 rounded-full bg-white/30"></div>
                        </div>
                        <div class="flex gap-2">
                            <div class="w-7 h-1.5 rounded-full bg-white/15"></div>
                            <div class="w-7 h-1.5 rounded-full bg-white/15"></div>
                            <div class="w-7 h-1.5 rounded-full bg-white/15"></div>
                        </div>
                        <div class="w-14 h-5 rounded-md bg-white/25"></div>
                    </div>
                    <div class="flex flex-col items-center justify-center gap-2.5 py-10 px-8">
                        <div class="text-3xl" x-text="active?.icon"></div>
                        <div class="w-56 h-3.5 rounded-full bg-white/60"></div>
                        <div class="w-44 h-3.5 rounded-full bg-white/40"></div>
                        <div class="w-36 h-2.5 rounded-full bg-white/25 mt-1"></div>
                        <div class="flex gap-2 mt-3">
                            <div class="w-20 h-7 rounded-lg bg-white/35"></div>
                            <div class="w-20 h-7 rounded-lg border border-white/25"></div>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <div class="w-32 h-2 rounded-full bg-white/40 mx-auto mb-4"></div>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach(range(1,6) as $n)
                            <div class="rounded-xl bg-white/10 p-3">
                                <div class="w-6 h-6 rounded-lg bg-white/20 mb-2"></div>
                                <div class="w-full h-1.5 rounded-full bg-white/25 mb-1.5"></div>
                                <div class="w-3/4 h-1.5 rounded-full bg-white/15"></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mx-6 mb-6 p-4 rounded-xl bg-white/10 border border-white/10">
                        <div class="flex gap-1 mb-2">
                            @for($s=0;$s<5;$s++)<div class="w-3 h-3 rounded-sm bg-amber-400/70"></div>@endfor
                        </div>
                        <div class="space-y-1.5 mb-3">
                            <div class="w-full h-1.5 rounded-full bg-white/25"></div>
                            <div class="w-5/6 h-1.5 rounded-full bg-white/20"></div>
                            <div class="w-4/6 h-1.5 rounded-full bg-white/15"></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-white/30"></div>
                            <div class="w-20 h-1.5 rounded-full bg-white/25"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tags row --}}
            <div class="flex items-center gap-2 px-7 py-4 border-t border-white/[0.06]">
                <span class="text-xs text-white/30 mr-1">Includes:</span>
                <template x-for="tag in active?.tags" :key="tag">
                    <span class="inline-block px-2.5 py-0.5 rounded-md bg-white/[0.08] text-white/50 text-xs font-medium border border-white/[0.08]" x-text="tag"></span>
                </template>
            </div>

        </div>
    </div>

</section>
