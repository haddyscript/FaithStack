<section class="py-28 px-6 text-center bg-gradient-to-br from-slate-900 to-slate-700 text-white">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-5xl md:text-6xl font-bold leading-tight tracking-tight mb-5">
            {{ $section['title'] ?? '' }}
        </h1>
        @if(!empty($section['subtitle']))
            <p class="text-xl text-slate-300 mb-8">{{ $section['subtitle'] }}</p>
        @endif
        @if(!empty($section['button_text']))
            <a href="{{ $section['button_url'] ?? '#' }}"
               class="btn-primary inline-block px-8 py-3 rounded-lg font-semibold text-sm transition">
                {{ $section['button_text'] }}
            </a>
        @endif
    </div>
</section>
