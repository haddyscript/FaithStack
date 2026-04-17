@props(['plans'])

<section id="pricing" class="py-28 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Pricing</p>
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5">
                Simple, transparent<br>pricing
            </h2>
            <p class="text-lg text-slate-500 max-w-md mx-auto">
                Start free, upgrade when you're ready. No hidden fees, no surprises.
            </p>
        </div>

        {{-- Pricing grid --}}
        <div class="grid lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach($plans as $plan)
            <div @class([
                'relative flex flex-col rounded-2xl p-8 transition-all duration-300',
                'bg-white border border-slate-200 hover:border-slate-300 hover:shadow-lg' => !$plan['featured'],
                'bg-gradient-to-b from-indigo-600 to-purple-700 shadow-2xl shadow-indigo-500/25 scale-105' => $plan['featured'],
            ])>
                @if($plan['featured'])
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-400 to-orange-400 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">
                    MOST POPULAR
                </div>
                @endif

                {{-- Plan name & price --}}
                <div class="mb-8">
                    <p @class([
                        'text-sm font-semibold uppercase tracking-widest mb-4',
                        'text-slate-500' => !$plan['featured'],
                        'text-indigo-200' => $plan['featured'],
                    ])>{{ $plan['name'] }}</p>

                    <div class="flex items-baseline gap-1 mb-2">
                        @if($plan['price'] === 0)
                        <span @class([
                            'text-5xl font-bold',
                            'text-slate-900' => !$plan['featured'],
                            'text-white' => $plan['featured'],
                        ])>Free</span>
                        @else
                        <span @class([
                            'text-2xl font-semibold',
                            'text-slate-500' => !$plan['featured'],
                            'text-indigo-200' => $plan['featured'],
                        ])>$</span>
                        <span @class([
                            'text-5xl font-bold',
                            'text-slate-900' => !$plan['featured'],
                            'text-white' => $plan['featured'],
                        ])>{{ $plan['price'] }}</span>
                        <span @class([
                            'text-sm',
                            'text-slate-500' => !$plan['featured'],
                            'text-indigo-200' => $plan['featured'],
                        ])>/mo</span>
                        @endif
                    </div>

                    <p @class([
                        'text-sm',
                        'text-slate-500' => !$plan['featured'],
                        'text-indigo-200' => $plan['featured'],
                    ])>{{ $plan['description'] }}</p>
                </div>

                {{-- CTA --}}
                <a href="/superadmin/login" @class([
                    'block text-center py-3 px-6 rounded-xl font-semibold text-sm mb-8 transition-all hover:-translate-y-0.5',
                    'bg-slate-900 text-white hover:bg-slate-800 shadow-md' => !$plan['featured'],
                    'bg-white text-indigo-700 hover:bg-indigo-50 shadow-lg' => $plan['featured'],
                ])>{{ $plan['cta'] }}</a>

                {{-- Divider --}}
                <div @class([
                    'border-t mb-8',
                    'border-slate-100' => !$plan['featured'],
                    'border-white/10' => $plan['featured'],
                ])></div>

                {{-- Included features --}}
                <ul class="space-y-3 flex-1">
                    @foreach($plan['features'] as $feature)
                    <li class="flex items-center gap-3 text-sm">
                        <svg @class([
                            'w-4 h-4 flex-shrink-0',
                            'text-indigo-600' => !$plan['featured'],
                            'text-indigo-200' => $plan['featured'],
                        ]) fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        <span @class([
                            'text-slate-700' => !$plan['featured'],
                            'text-white' => $plan['featured'],
                        ])>{{ $feature }}</span>
                    </li>
                    @endforeach

                    @foreach($plan['missing'] as $feature)
                    <li class="flex items-center gap-3 text-sm opacity-40">
                        <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span @class([
                            'text-slate-500' => !$plan['featured'],
                            'text-white' => $plan['featured'],
                        ])>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>

            </div>
            @endforeach
        </div>

        {{-- Enterprise note --}}
        <p class="text-center text-slate-400 text-sm mt-10">
            Need more than 10 sites? <a href="mailto:hello@faithstack.com" class="text-indigo-600 hover:underline font-medium">Talk to us about Enterprise</a>
        </p>

    </div>
</section>
