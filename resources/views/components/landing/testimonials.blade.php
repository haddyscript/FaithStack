@props(['testimonials'])

<section id="testimonials" class="py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <p class="reveal text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Testimonials</p>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5" data-delay="1">
                Trusted by organizations<br>just like yours
            </h2>
        </div>

        {{-- Animated stat counters --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-20">
            @php
            $stats = [
                ['counter' => '500',  'suffix' => '+',   'label' => 'Organizations'],
                ['counter' => '80',   'suffix' => '+',   'label' => 'Themes available'],
                ['counter' => '9800', 'suffix' => '+',   'prefix' => '$', 'label' => 'Avg donations/mo'],
                ['counter' => '99.9', 'suffix' => '%',   'decimals' => '1', 'label' => 'Uptime SLA'],
            ];
            @endphp
            @foreach($stats as $i => $s)
            <div class="reveal text-center" data-delay="{{ $i + 1 }}">
                <div class="text-4xl font-bold text-slate-900 mb-1 tabular-nums">
                    <span data-counter="{{ $s['counter'] }}"
                          data-suffix="{{ $s['suffix'] ?? '' }}"
                          data-prefix="{{ $s['prefix'] ?? '' }}"
                          data-decimals="{{ $s['decimals'] ?? '0' }}">
                        {{ ($s['prefix'] ?? '') . number_format((float)$s['counter'], $s['decimals'] ?? 0) . ($s['suffix'] ?? '') }}
                    </span>
                </div>
                <div class="text-sm text-slate-400">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Cards --}}
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($testimonials as $i => $t)
            <div class="reveal testimonial-card group bg-slate-50 rounded-2xl p-8 border border-slate-100 cursor-default"
                 data-delay="{{ $i + 1 }}"
                 data-tilt="4">

                {{-- Quote mark --}}
                <div class="text-5xl font-serif text-indigo-200 leading-none mb-4 select-none group-hover:text-indigo-300 transition-colors duration-300">&ldquo;</div>

                {{-- Stars --}}
                <div class="flex gap-1 mb-5">
                    @for($s = 0; $s < 5; $s++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>

                <blockquote class="text-slate-600 leading-relaxed mb-7 text-[0.9375rem]">
                    "{{ $t['quote'] }}"
                </blockquote>

                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-full {{ $t['avatar_color'] }} flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-md">
                        {{ $t['initials'] }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-900">{{ $t['author'] }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">{{ $t['role'] }}</div>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>
