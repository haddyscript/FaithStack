@props(['features'])

<style>
.fc-indigo  { --card-accent: rgba(99,102,241,0.28);  --card-glow: rgba(99,102,241,0.11);  }
.fc-purple  { --card-accent: rgba(168,85,247,0.28);  --card-glow: rgba(168,85,247,0.11);  }
.fc-blue    { --card-accent: rgba(59,130,246,0.28);  --card-glow: rgba(59,130,246,0.11);  }
.fc-emerald { --card-accent: rgba(16,185,129,0.28);  --card-glow: rgba(16,185,129,0.11);  }
.fc-rose    { --card-accent: rgba(244,63,94,0.28);   --card-glow: rgba(244,63,94,0.11);   }
.fc-amber   { --card-accent: rgba(245,158,11,0.28);  --card-glow: rgba(245,158,11,0.11);  }
</style>

<section id="features" class="relative py-28 bg-white overflow-hidden">

    {{-- Background decoration --}}
    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-indigo-200/70 to-transparent pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-64 bg-[radial-gradient(ellipse_at_top,rgba(238,242,255,0.65),transparent)] pointer-events-none"></div>
    <div class="absolute top-1/3 -left-40 w-96 h-96 rounded-full bg-indigo-50/70 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 -right-40 w-96 h-96 rounded-full bg-violet-50/70 blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <div class="reveal inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-xs font-semibold text-indigo-600 mb-5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                </svg>
                Platform Features
            </div>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5" data-delay="1">
                Everything you need to<br>build and grow
            </h2>
            <p class="reveal text-lg text-slate-500 max-w-xl mx-auto leading-relaxed" data-delay="2">
                A complete platform built specifically for churches, nonprofits, and community organizations — with tools you'll actually use.
            </p>
        </div>

        {{-- Feature grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($features as $i => $feature)
            @php
                $palettes = [
                    'indigo'  => [
                        'gradient'    => 'from-indigo-500 to-violet-600',
                        'icon_shadow' => 'shadow-indigo-300/50',
                        'card_accent' => 'rgba(99,102,241,0.28)',
                        'card_glow'   => 'rgba(99,102,241,0.11)',
                    ],
                    'purple'  => [
                        'gradient'    => 'from-purple-500 to-pink-600',
                        'icon_shadow' => 'shadow-purple-300/50',
                        'card_accent' => 'rgba(168,85,247,0.28)',
                        'card_glow'   => 'rgba(168,85,247,0.11)',
                    ],
                    'blue'    => [
                        'gradient'    => 'from-blue-500 to-indigo-600',
                        'icon_shadow' => 'shadow-blue-300/50',
                        'card_accent' => 'rgba(59,130,246,0.28)',
                        'card_glow'   => 'rgba(59,130,246,0.11)',
                    ],
                    'emerald' => [
                        'gradient'    => 'from-emerald-500 to-teal-600',
                        'icon_shadow' => 'shadow-emerald-300/50',
                        'card_accent' => 'rgba(16,185,129,0.28)',
                        'card_glow'   => 'rgba(16,185,129,0.11)',
                    ],
                    'rose'    => [
                        'gradient'    => 'from-rose-500 to-pink-600',
                        'icon_shadow' => 'shadow-rose-300/50',
                        'card_accent' => 'rgba(244,63,94,0.28)',
                        'card_glow'   => 'rgba(244,63,94,0.11)',
                    ],
                    'amber'   => [
                        'gradient'    => 'from-amber-500 to-orange-500',
                        'icon_shadow' => 'shadow-amber-300/50',
                        'card_accent' => 'rgba(245,158,11,0.28)',
                        'card_glow'   => 'rgba(245,158,11,0.11)',
                    ],
                ];
                $p    = $palettes[$feature['color']] ?? $palettes['indigo'];
                $fcClass = 'fc-' . ($feature['color'] ?? 'indigo');
            @endphp

            <div class="reveal feature-card {{ $fcClass }} group relative bg-white/80 backdrop-blur-sm rounded-2xl border border-slate-100/80 p-8 cursor-default overflow-hidden"
                 data-delay="{{ $i + 1 }}"
                 data-tilt="5">

                {{-- Mouse-tracking glare (wired by landing.blade.php JS) --}}
                <div class="tilt-glare absolute inset-0 rounded-2xl pointer-events-none z-10"></div>

                {{-- Top edge highlight --}}
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/80 to-transparent pointer-events-none"></div>

                {{-- Icon --}}
                <div class="feature-icon relative z-[1] w-12 h-12 rounded-xl bg-gradient-to-br {{ $p['gradient'] }}
                            flex items-center justify-center mb-6 shadow-lg {{ $p['icon_shadow'] }}">
                    @if($feature['icon'] === 'cube')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                    @elseif($feature['icon'] === 'swatch')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z"/></svg>
                    @elseif($feature['icon'] === 'users')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    @elseif($feature['icon'] === 'currency-dollar')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($feature['icon'] === 'paint-brush')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>
                    @else {{-- device-phone-mobile --}}
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3.75h3m-6-7.5h.008v.008H7.5V6zm0 3.75h.008v.008H7.5V9.75z"/></svg>
                    @endif
                </div>

                {{-- Text --}}
                <div class="relative z-[1]">
                    <h3 class="text-lg font-semibold text-slate-900 mb-2.5 group-hover:text-indigo-600 transition-colors duration-200">{{ $feature['title'] }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>
