@php
$founders = [
    [
        'name'        => 'Hadrian Evarula',
        'role'        => 'Software Developer',
        'initials'    => 'HE',
        'image'       => 'images/hadrian-founder.png',
        'gradient'    => 'from-indigo-500 to-violet-600',
        'glow'        => 'rgba(99,102,241,0.22)',
        'badge_bg'    => 'bg-indigo-600',
        'accent_from' => 'from-indigo-500',
        'accent_to'   => 'to-violet-600',
    ],
    [
        'name'        => 'Juls Esturco',
        'role'        => 'Project Manager',
        'initials'    => 'JE',
        'image'       => null,
        'gradient'    => 'from-violet-500 to-purple-600',
        'glow'        => 'rgba(139,92,246,0.22)',
        'badge_bg'    => 'bg-violet-600',
        'accent_from' => 'from-violet-500',
        'accent_to'   => 'to-purple-600',
    ],
    [
        'name'        => 'Carl Sarbida',
        'role'        => 'Developer',
        'initials'    => 'CS',
        'image'       => null,
        'gradient'    => 'from-blue-500 to-indigo-600',
        'glow'        => 'rgba(59,130,246,0.22)',
        'badge_bg'    => 'bg-blue-600',
        'accent_from' => 'from-blue-500',
        'accent_to'   => 'to-indigo-600',
    ],
    [
        'name'        => 'Johnny Davis',
        'role'        => 'Product & Strategy',
        'initials'    => 'JD',
        'image'       => 'images/Johnny-Davis.webp',
        'gradient'    => 'from-emerald-400 to-teal-600',
        'glow'        => 'rgba(16,185,129,0.22)',
        'badge_bg'    => 'bg-emerald-600',
        'accent_from' => 'from-emerald-400',
        'accent_to'   => 'to-teal-600',
    ],
];
@endphp

<section id="founders" class="py-24 lg:py-32 bg-slate-50/60">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-16">
            <div class="reveal inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-xs font-semibold text-indigo-600 mb-5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                The Team
            </div>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-4" data-delay="1">
                Meet the Founders
            </h2>
            <p class="reveal text-slate-500 text-lg leading-relaxed max-w-xl mx-auto" data-delay="2">
                The people behind FaithStack — builders, dreamers, and advocates for communities that matter.
            </p>
        </div>

        {{-- Cards grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($founders as $i => $founder)

            {{-- Wrapper handles the colored glow on hover via inline style --}}
            <div class="reveal founder-card group relative flex flex-col items-center text-center
                        rounded-3xl p-6 pb-7
                        bg-white/70 backdrop-blur-md
                        border border-white/60
                        shadow-sm
                        hover:-translate-y-2
                        transition-all duration-500 ease-out
                        cursor-default"
                 data-delay="{{ $i + 1 }}"
                 style="--glow: {{ $founder['glow'] }}"
                 onmouseenter="this.style.boxShadow='0 24px 64px -12px var(--glow), 0 8px 24px -4px rgba(0,0,0,0.07)'"
                 onmouseleave="this.style.boxShadow=''">

                {{-- Profile image container --}}
                <div class="relative w-full mb-5 overflow-hidden rounded-2xl aspect-square bg-gradient-to-br {{ $founder['gradient'] }}">

                    @if($founder['image'])
                        {{-- Real photo with zoom on hover --}}
                        <img src="{{ asset($founder['image']) }}"
                             alt="{{ $founder['name'] }}"
                             class="absolute inset-0 w-full h-full object-cover object-top
                                    group-hover:scale-105 transition-transform duration-700 ease-out">
                    @else
                        {{-- Styled placeholder --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                            {{-- Dot grid texture --}}
                            <div class="absolute inset-0 opacity-10"
                                 style="background-image: radial-gradient(circle, rgba(255,255,255,0.7) 1px, transparent 1px); background-size: 18px 18px;"></div>
                            <span class="relative z-10 text-4xl font-extrabold text-white/90 tracking-tight
                                         group-hover:scale-110 transition-transform duration-500 ease-out select-none">
                                {{ $founder['initials'] }}
                            </span>
                            <span class="relative z-10 text-[10px] font-semibold text-white/50 uppercase tracking-[0.18em]">
                                Coming soon
                            </span>
                        </div>
                    @endif

                    {{-- Subtle vignette at the bottom of the image --}}
                    <div class="absolute bottom-0 inset-x-0 h-16
                                bg-gradient-to-t from-black/30 to-transparent
                                pointer-events-none"></div>

                    {{-- Co-Founder badge — overlaps bottom-left corner of image --}}
                    <div class="absolute bottom-3 left-3 z-10">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                                     {{ $founder['badge_bg'] }}
                                     text-[9px] font-bold text-white uppercase tracking-[0.18em]
                                     shadow-lg ring-1 ring-white/20
                                     backdrop-blur-sm">
                            <svg class="w-2.5 h-2.5 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Co-Founder
                        </span>
                    </div>
                </div>

                {{-- Info --}}
                <div class="mt-1">
                    <p class="font-bold text-slate-900 text-base leading-snug">{{ $founder['name'] }}</p>
                    <p class="text-sm text-slate-400 mt-1">{{ $founder['role'] }}</p>
                </div>

                {{-- Animated accent line --}}
                <div class="mt-4 h-0.5 w-8 rounded-full bg-gradient-to-r {{ $founder['accent_from'] }} {{ $founder['accent_to'] }}
                            group-hover:w-16 transition-all duration-500 ease-out"></div>

            </div>
            @endforeach
        </div>

    </div>
</section>
