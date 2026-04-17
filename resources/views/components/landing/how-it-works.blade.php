@props(['steps'])

<section id="how-it-works" class="py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <p class="reveal text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Get Started</p>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5" data-delay="1">
                Up and running<br>in three steps
            </h2>
            <p class="reveal text-lg text-slate-500 max-w-md mx-auto" data-delay="2">
                From zero to live website in under five minutes. Seriously.
            </p>
        </div>

        {{-- Steps --}}
        <div class="grid lg:grid-cols-3 gap-8 lg:gap-12 mb-20">
            @foreach($steps as $i => $step)
            <div class="reveal relative flex flex-col items-center text-center" data-delay="{{ $i + 1 }}">

                {{-- Connector (desktop) --}}
                @if(!$loop->last)
                <div class="hidden lg:block absolute top-12 left-[calc(50%+48px)] right-[-50%] h-px">
                    <div class="h-full bg-gradient-to-r from-indigo-200 to-transparent"></div>
                </div>
                @endif

                {{-- Circle --}}
                <div class="relative mb-7 group">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-xl shadow-indigo-500/30 transition-all duration-500 group-hover:shadow-2xl group-hover:shadow-indigo-500/40 group-hover:scale-105">
                        <span class="text-2xl font-bold text-white">{{ $step['number'] }}</span>
                    </div>
                    {{-- Ping ring --}}
                    <div class="absolute inset-0 rounded-full border-2 border-indigo-400/30 scale-110 animate-ping" style="animation-duration:3s;animation-delay:{{ $i * 0.8 }}s"></div>
                </div>

                <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ $step['title'] }}</h3>
                <p class="text-slate-500 leading-relaxed max-w-xs text-sm">{{ $step['description'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Inline CTA --}}
        <div class="reveal" data-delay="2">
            <div class="relative rounded-3xl overflow-hidden px-8 py-14 text-center bg-gradient-to-br from-indigo-600 via-purple-600 to-violet-700 gradient-animate"
                 style="background-image: linear-gradient(135deg, #4f46e5, #7c3aed, #9333ea, #4f46e5);">
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:32px_32px]"></div>
                <div class="relative">
                    <h3 class="text-3xl font-bold text-white mb-3">Ready to get started?</h3>
                    <p class="text-indigo-200 mb-8 max-w-md mx-auto">Join hundreds of organizations already building with FaithStack.</p>
                    <a href="/superadmin/login"
                       class="ripple-btn inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-white text-indigo-700 font-semibold text-sm hover:bg-indigo-50 transition-all duration-300 shadow-xl hover:-translate-y-0.5">
                        Start your free trial
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
