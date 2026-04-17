@props(['steps'])

<section id="how-it-works" class="py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Get Started</p>
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5">
                Up and running<br>in three steps
            </h2>
            <p class="text-lg text-slate-500 max-w-md mx-auto">
                From zero to live website in under five minutes. Seriously.
            </p>
        </div>

        {{-- Steps --}}
        <div class="relative grid lg:grid-cols-3 gap-12">

            {{-- Connector line (desktop) --}}
            <div class="hidden lg:block absolute top-12 left-1/6 right-1/6 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent" style="left:16.66%;right:16.66%;"></div>

            @foreach($steps as $i => $step)
            <div class="relative flex flex-col items-center text-center">

                {{-- Step number circle --}}
                <div class="relative mb-8">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-xl shadow-indigo-500/25">
                        <span class="text-2xl font-bold text-white">{{ $step['number'] }}</span>
                    </div>
                    @if(!$loop->last)
                    <div class="hidden lg:block absolute top-1/2 -right-full w-full h-px bg-slate-200 -z-10"></div>
                    @endif
                </div>

                <h3 class="text-xl font-semibold text-slate-900 mb-3">{{ $step['title'] }}</h3>
                <p class="text-slate-500 leading-relaxed max-w-xs">{{ $step['description'] }}</p>
            </div>
            @endforeach

        </div>

        {{-- CTA block --}}
        <div class="mt-20 relative rounded-3xl overflow-hidden bg-gradient-to-br from-indigo-600 to-purple-700 px-8 py-12 text-center">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:32px_32px]"></div>
            <div class="relative">
                <h3 class="text-3xl font-bold text-white mb-3">Ready to get started?</h3>
                <p class="text-indigo-200 mb-8 max-w-md mx-auto">Join hundreds of organizations already building with FaithStack.</p>
                <a href="/superadmin/login"
                   class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-white text-indigo-700 font-semibold text-sm hover:bg-indigo-50 transition-all shadow-lg hover:-translate-y-0.5">
                    Start your free trial
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>

    </div>
</section>
