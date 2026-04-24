@php
$founders = [
    [
        'name'     => 'Hadrian Evarula',
        'role'     => 'Software Developer',
        'initials' => 'HE',
        'gradient' => 'from-indigo-500 to-violet-600',
        'shadow'   => 'shadow-indigo-200',
    ],
    [
        'name'     => 'Juls Esturco',
        'role'     => 'Project Manager',
        'initials' => 'JE',
        'gradient' => 'from-violet-500 to-purple-600',
        'shadow'   => 'shadow-violet-200',
    ],
    [
        'name'     => 'Carl Sarbida',
        'role'     => 'Developer',
        'initials' => 'CS',
        'gradient' => 'from-blue-500 to-indigo-600',
        'shadow'   => 'shadow-blue-200',
    ],
    [
        'name'     => 'Johnny Davis',
        'role'     => 'Product & Strategy',
        'initials' => 'JD',
        'gradient' => 'from-emerald-400 to-teal-600',
        'shadow'   => 'shadow-emerald-200',
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
            <div class="reveal group flex flex-col items-center text-center p-8 bg-white rounded-2xl border border-slate-100
                        shadow-sm hover:shadow-xl hover:shadow-slate-200/60 hover:-translate-y-1.5
                        transition-all duration-300 cursor-default"
                 data-delay="{{ $i + 1 }}">

                {{-- Avatar --}}
                <div class="relative mb-5">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br {{ $founder['gradient'] }}
                                flex items-center justify-center text-white text-2xl font-bold
                                shadow-lg {{ $founder['shadow'] }}
                                group-hover:scale-105 transition-transform duration-300">
                        {{ $founder['initials'] }}
                    </div>
                    {{-- Co-Founder badge --}}
                    <span class="absolute -bottom-2.5 left-1/2 -translate-x-1/2
                                 whitespace-nowrap px-2.5 py-0.5 rounded-full
                                 bg-white border border-slate-200 shadow-sm
                                 text-[10px] font-semibold text-slate-500 tracking-wide uppercase">
                        Co-Founder
                    </span>
                </div>

                {{-- Info --}}
                <div class="mt-4">
                    <p class="font-bold text-slate-900 text-base leading-snug">{{ $founder['name'] }}</p>
                    <p class="text-sm text-slate-400 mt-1">{{ $founder['role'] }}</p>
                </div>

                {{-- Decorative accent line --}}
                <div class="mt-5 w-8 h-0.5 rounded-full bg-gradient-to-r {{ $founder['gradient'] }} opacity-50
                            group-hover:w-14 group-hover:opacity-100 transition-all duration-300"></div>
            </div>
            @endforeach
        </div>

    </div>
</section>
