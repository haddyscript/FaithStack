@props(['plans'])

<section id="pricing" class="py-28 bg-slate-50" x-data="{ billing: 'monthly' }">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-10">
            <p class="reveal text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Pricing</p>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5" data-delay="1">
                Simple, transparent<br>pricing
            </h2>
            <p class="reveal text-lg text-slate-500 max-w-md mx-auto" data-delay="2">
                Start free, upgrade when you're ready. No hidden fees, no surprises.
            </p>
        </div>

        {{-- ── Monthly / Yearly Toggle ── --}}
        <div class="reveal flex items-center justify-center gap-4 mb-16" data-delay="3">
            <span class="text-sm font-medium transition-colors duration-200"
                  :class="billing === 'monthly' ? 'text-slate-900' : 'text-slate-400'">Monthly</span>

            <button @click="billing = billing === 'monthly' ? 'yearly' : 'monthly'"
                    class="relative w-12 h-6 rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/50"
                    :class="billing === 'yearly' ? 'bg-indigo-600' : 'bg-slate-300'"
                    aria-label="Toggle billing period">
                <div class="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow-md transition-all duration-300"
                     :class="billing === 'yearly' ? 'left-6' : 'left-0.5'"></div>
            </button>

            <div class="flex items-center gap-2">
                <span class="text-sm font-medium transition-colors duration-200"
                      :class="billing === 'yearly' ? 'text-slate-900' : 'text-slate-400'">Yearly</span>
                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200/60">
                    Save 20%
                </span>
            </div>
        </div>

        {{-- Plans --}}
        <div class="grid lg:grid-cols-3 gap-6 lg:gap-8 max-w-5xl mx-auto items-center">
            @foreach($plans as $i => $plan)

            {{-- Pro / featured card: gradient border wrapper --}}
            @if($plan['featured'])
            <div class="reveal relative scale-105 z-10 pt-5" data-delay="{{ $i + 1 }}">
                {{-- Badge (outside overflow-hidden so it's never clipped) --}}
                @if(!empty($plan['badge']))
                <div class="absolute top-0 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-400 to-orange-400 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg tracking-wide uppercase whitespace-nowrap z-20">
                    {{ $plan['badge'] }}
                </div>
                @endif

                {{-- Glow halo --}}
                <div class="absolute -inset-3 rounded-3xl bg-gradient-to-br from-indigo-500/25 via-purple-500/20 to-pink-500/15 blur-2xl pointer-events-none"></div>
                {{-- Gradient border shell --}}
                <div class="relative p-[1.5px] rounded-[20px] bg-gradient-to-br from-indigo-400 via-purple-400 to-pink-400 shadow-2xl shadow-indigo-500/30">
                    <div class="relative flex flex-col rounded-[18.5px] bg-gradient-to-b from-indigo-600 to-purple-700 p-8 overflow-hidden">

                        {{-- Name & price --}}
                        <div class="mb-8">
                            <p class="text-xs font-bold uppercase tracking-widest mb-4 text-indigo-200">{{ $plan['name'] }}</p>

                            <div class="flex items-baseline gap-1 mb-2">
                                @if($plan['price'] == 0)
                                <span class="text-5xl font-bold text-white">Free</span>
                                @else
                                <span class="text-2xl font-semibold text-indigo-200">$</span>
                                <span x-show="billing === 'monthly'" class="text-5xl font-bold text-white">{{ number_format((float)$plan['price'], 0) }}</span>
                                <span x-show="billing === 'yearly'" x-cloak class="text-5xl font-bold text-white">{{ number_format(round((float)$plan['price'] * 0.8), 0) }}</span>
                                <span class="text-sm text-indigo-200">/mo</span>
                                @endif
                            </div>

                            <p x-show="billing === 'yearly' && {{ $plan['price'] > 0 ? 'true' : 'false' }}" x-cloak
                               class="text-xs text-indigo-200 mb-1">billed ${{ number_format(round((float)$plan['price'] * 0.8 * 12), 0) }}/year</p>

                            <p class="text-sm text-indigo-200">{{ $plan['description'] }}</p>
                        </div>

                        {{-- CTA --}}
                        <a href="{{ url('/register') }}?plan={{ $plan['slug'] ?? 'free-trial' }}"
                           class="ripple-btn block text-center py-3 px-6 rounded-xl font-semibold text-sm mb-8 transition-all duration-300 hover:-translate-y-0.5 bg-white text-indigo-700 hover:bg-indigo-50 shadow-xl">
                            {{ $plan['cta'] }}
                        </a>

                        <div class="border-t border-white/10 mb-8"></div>

                        {{-- Features --}}
                        <ul class="space-y-3 flex-1">
                            @foreach($plan['features'] as $feature)
                            <li class="flex items-center gap-3 text-sm">
                                <svg class="w-4 h-4 flex-shrink-0 text-indigo-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-white">{{ $feature }}</span>
                            </li>
                            @endforeach
                            @foreach($plan['missing'] as $feature)
                            <li class="flex items-center gap-3 text-sm opacity-35">
                                <svg class="w-4 h-4 flex-shrink-0 text-white/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span class="text-white">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>

            {{-- Regular (non-featured) card --}}
            @else
            <div class="reveal relative flex flex-col rounded-2xl p-8 bg-white border border-slate-200 hover:border-slate-300 hover:shadow-lg transition-all duration-300"
                 data-delay="{{ $i + 1 }}">

                @if(!empty($plan['badge']))
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-400 to-orange-400 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg tracking-wide uppercase whitespace-nowrap">
                    {{ $plan['badge'] }}
                </div>
                @endif

                {{-- Name & price --}}
                <div class="mb-8">
                    <p class="text-xs font-bold uppercase tracking-widest mb-4 text-slate-400">{{ $plan['name'] }}</p>

                    <div class="flex items-baseline gap-1 mb-2">
                        @if($plan['price'] == 0)
                        <span class="text-5xl font-bold text-slate-900">Free</span>
                        @else
                        <span class="text-2xl font-semibold text-slate-400">$</span>
                        <span x-show="billing === 'monthly'" class="text-5xl font-bold text-slate-900">{{ number_format((float)$plan['price'], 0) }}</span>
                        <span x-show="billing === 'yearly'" x-cloak class="text-5xl font-bold text-slate-900">{{ number_format(round((float)$plan['price'] * 0.8), 0) }}</span>
                        <span class="text-sm text-slate-400">/mo</span>
                        @endif
                    </div>

                    <p x-show="billing === 'yearly' && {{ $plan['price'] > 0 ? 'true' : 'false' }}" x-cloak
                       class="text-xs text-emerald-600 font-medium mb-1">billed ${{ number_format(round((float)$plan['price'] * 0.8 * 12), 0) }}/year</p>

                    <p class="text-sm text-slate-500">{{ $plan['description'] }}</p>

                    @if(!empty($plan['trial_days']) && $plan['price'] == 0)
                    <p class="mt-2 text-xs font-medium text-emerald-600">✓ No credit card required</p>
                    @endif
                </div>

                {{-- CTA --}}
                <a href="{{ url('/register') }}?plan={{ $plan['slug'] ?? 'free-trial' }}"
                   class="ripple-btn block text-center py-3 px-6 rounded-xl font-semibold text-sm mb-8 transition-all duration-300 hover:-translate-y-0.5 bg-slate-900 hover:bg-slate-800 text-white shadow-md hover:shadow-xl hover:shadow-slate-900/20">
                    {{ $plan['cta'] }}
                </a>

                <div class="border-t border-slate-100 mb-8"></div>

                {{-- Features --}}
                <ul class="space-y-3 flex-1">
                    @foreach($plan['features'] as $feature)
                    <li class="flex items-center gap-3 text-sm">
                        <svg class="w-4 h-4 flex-shrink-0 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-700">{{ $feature }}</span>
                    </li>
                    @endforeach
                    @foreach($plan['missing'] as $feature)
                    <li class="flex items-center gap-3 text-sm opacity-40">
                        <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="text-slate-500">{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>

            </div>
            @endif

            @endforeach
        </div>

        <p class="reveal text-center text-slate-400 text-sm mt-14">
            Need more than 10 sites?
            <a href="mailto:hello@faithstack.com" class="link-slide text-indigo-600 hover:text-indigo-700 font-medium">Talk to us about Enterprise</a>
        </p>

    </div>
</section>
