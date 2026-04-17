@props(['plans'])

<section id="pricing" class="py-28 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <p class="reveal text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Pricing</p>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5" data-delay="1">
                Simple, transparent<br>pricing
            </h2>
            <p class="reveal text-lg text-slate-500 max-w-md mx-auto" data-delay="2">
                Start free, upgrade when you're ready. No hidden fees, no surprises.
            </p>
        </div>

        {{-- Plans --}}
        <div class="grid lg:grid-cols-3 gap-6 lg:gap-8 max-w-5xl mx-auto items-center">
            @foreach($plans as $i => $plan)
            <div class="reveal pricing-card {{ $plan['featured'] ? 'pricing-featured' : '' }} relative flex flex-col rounded-2xl p-8"
                 data-delay="{{ $i + 1 }}"
                 @class([
                     'bg-white border border-slate-200' => !$plan['featured'],
                     'bg-gradient-to-b from-indigo-600 to-purple-700 shadow-2xl shadow-indigo-500/30 scale-105 z-10 ring-1 ring-indigo-400/30' => $plan['featured'],
                 ])>

                @if($plan['featured'])
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-400 to-orange-400 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg tracking-wide">
                    MOST POPULAR
                </div>
                @endif

                {{-- Name & price --}}
                <div class="mb-8">
                    <p @class(['text-xs font-bold uppercase tracking-widest mb-4', 'text-slate-400' => !$plan['featured'], 'text-indigo-200' => $plan['featured']])>
                        {{ $plan['name'] }}
                    </p>

                    <div class="flex items-baseline gap-1 mb-2">
                        @if($plan['price'] === 0)
                        <span @class(['text-5xl font-bold', 'text-slate-900' => !$plan['featured'], 'text-white' => $plan['featured']])>Free</span>
                        @else
                        <span @class(['text-2xl font-semibold', 'text-slate-400' => !$plan['featured'], 'text-indigo-200' => $plan['featured']])>$</span>
                        <span @class(['text-5xl font-bold', 'text-slate-900' => !$plan['featured'], 'text-white' => $plan['featured']])>{{ $plan['price'] }}</span>
                        <span @class(['text-sm', 'text-slate-400' => !$plan['featured'], 'text-indigo-200' => $plan['featured']])}>/mo</span>
                        @endif
                    </div>

                    <p @class(['text-sm', 'text-slate-500' => !$plan['featured'], 'text-indigo-200' => $plan['featured']])>
                        {{ $plan['description'] }}
                    </p>
                </div>

                {{-- CTA --}}
                <a href="/superadmin/login" @class([
                    'ripple-btn block text-center py-3 px-6 rounded-xl font-semibold text-sm mb-8 transition-all duration-300 hover:-translate-y-0.5',
                    'bg-slate-900 hover:bg-slate-800 text-white shadow-md hover:shadow-xl hover:shadow-slate-900/20' => !$plan['featured'],
                    'bg-white text-indigo-700 hover:bg-indigo-50 shadow-xl pulse-glow' => $plan['featured'],
                ])>{{ $plan['cta'] }}</a>

                <div @class(['border-t mb-8', 'border-slate-100' => !$plan['featured'], 'border-white/10' => $plan['featured']])></div>

                {{-- Included --}}
                <ul class="space-y-3 flex-1">
                    @foreach($plan['features'] as $feature)
                    <li class="flex items-center gap-3 text-sm">
                        <svg @class(['w-4 h-4 flex-shrink-0', 'text-indigo-500' => !$plan['featured'], 'text-indigo-200' => $plan['featured']]) fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        <span @class(['text-slate-700' => !$plan['featured'], 'text-white' => $plan['featured']])>{{ $feature }}</span>
                    </li>
                    @endforeach

                    @foreach($plan['missing'] as $feature)
                    <li class="flex items-center gap-3 text-sm opacity-35">
                        <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span @class(['text-slate-500' => !$plan['featured'], 'text-white' => $plan['featured']])>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>

            </div>
            @endforeach
        </div>

        <p class="reveal text-center text-slate-400 text-sm mt-12">
            Need more than 10 sites?
            <a href="mailto:hello@faithstack.com" class="link-slide text-indigo-600 hover:text-indigo-700 font-medium">Talk to us about Enterprise</a>
        </p>

    </div>
</section>
