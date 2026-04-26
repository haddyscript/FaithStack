@php
    use App\Models\Event;

    $limit   = (int) ($section['limit']       ?? 3);
    $heading = $section['heading']             ?? 'Upcoming Events';
    $subtext = $section['subtext']             ?? '';
    $btnText = $section['button_text']         ?? 'View All Events';
    $btnUrl  = $section['button_url']          ?? route('events.index');
    $showBtn = $section['show_view_all']       ?? true;

    $upcomingEvents = Event::forTenant($tenant->id)
        ->published()
        ->where('start_date', '>=', now())
        ->orderBy('start_date')
        ->limit($limit)
        ->get();
@endphp

<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">

        @if($heading)
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-3">{{ $heading }}</h2>
                @if($subtext)
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $subtext }}</p>
                @endif
            </div>
        @endif

        @if($upcomingEvents->isEmpty())
            <div class="text-center py-10 text-gray-500">
                <p>No upcoming events at this time. Check back soon.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcomingEvents as $event)
                <a href="{{ route('events.show', $event) }}"
                   class="group bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    @if($event->image_url)
                        <div class="h-40 overflow-hidden">
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-40 bg-primary/10 flex items-center justify-center">
                            <svg class="w-10 h-10 text-primary/30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full text-white bg-primary">
                                {{ $event->start_date->format('M j') }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $event->start_date->format('g:i A') }}</span>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-1 line-clamp-2 group-hover:text-primary transition-colors">
                            {{ $event->title }}
                        </h3>
                        @if($event->location)
                            <p class="text-xs text-gray-500 truncate">{{ $event->location }}</p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            @if($showBtn && $btnText)
                <div class="text-center mt-8">
                    <a href="{{ $btnUrl }}" class="btn-primary inline-block px-8 py-3 rounded-full font-semibold text-sm shadow transition-all hover:opacity-90">
                        {{ $btnText }}
                    </a>
                </div>
            @endif
        @endif

    </div>
</section>
