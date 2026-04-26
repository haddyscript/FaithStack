@php
    use App\Models\Event;

    $limit    = (int) ($data['limit']   ?? 3);
    $heading  = $data['heading']         ?? 'Upcoming Events';
    $subtext  = $data['subtext']         ?? '';
    $btnText  = $data['button_text']     ?? 'View All Events';
    $btnUrl   = $data['button_url']      ?? route('events.index');
    $showBtn  = !empty($data['show_view_all'] ?? true);

    $spacing = match($config['section_spacing'] ?? 'spacious') {
        'spacious' => 'py-24',
        'compact'  => 'py-12',
        default    => 'py-16',
    };

    $upcomingEvents = Event::forTenant($tenant->id)
        ->published()
        ->where('start_date', '>=', now())
        ->orderBy('start_date')
        ->limit($limit)
        ->get();
@endphp

<section style="background: var(--bg-sec);" class="{{ $spacing }}">
    <div class="max-w-6xl mx-auto px-6">

        {{-- Section Header --}}
        @if($heading)
        <div class="text-center mb-10" data-animate="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-3" style="color: var(--text-pri);">
                {{ $heading }}
            </h2>
            @if($subtext)
                <p class="text-lg max-w-2xl mx-auto" style="color: var(--text-sec);">{{ $subtext }}</p>
            @endif
        </div>
        @endif

        @if($upcomingEvents->isEmpty())
            <div class="text-center py-12" style="color: var(--text-sec);">
                <p class="text-base">No upcoming events at this time. Check back soon.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcomingEvents as $event)
                <a href="{{ route('events.show', $event) }}"
                   class="group rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5"
                   style="background: var(--bg-pri);"
                   data-animate="fade-up" data-delay="{{ $loop->index * 80 }}">

                    @if($event->image_url)
                        <div class="h-40 overflow-hidden">
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-40 flex items-center justify-center"
                             style="background: linear-gradient(135deg, var(--primary), var(--secondary));">
                            <svg class="w-10 h-10 opacity-30 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full text-white"
                                  style="background-color: var(--primary);">
                                {{ $event->start_date->format('M j') }}
                            </span>
                            <span class="text-xs" style="color: var(--text-sec);">
                                {{ $event->start_date->format('g:i A') }}
                            </span>
                        </div>
                        <h3 class="font-bold text-base mb-1.5 line-clamp-2" style="color: var(--text-pri);">
                            {{ $event->title }}
                        </h3>
                        @if($event->location)
                            <p class="text-xs truncate" style="color: var(--text-sec);">
                                {{ $event->location }}
                            </p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            @if($showBtn && $btnText)
                <div class="text-center mt-10" data-animate="fade-up">
                    <a href="{{ $btnUrl }}" class="inline-block btn-primary px-8 py-3.5 rounded-xl font-semibold text-base shadow transition-all">
                        {{ $btnText }}
                    </a>
                </div>
            @endif
        @endif

    </div>
</section>
