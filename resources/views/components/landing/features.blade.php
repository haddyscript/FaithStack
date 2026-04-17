@props(['features'])

<section id="features" class="py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-20">
            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Platform Features</p>
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5">
                Everything you need to<br>build and grow
            </h2>
            <p class="text-lg text-slate-500 max-w-xl mx-auto">
                A complete platform built specifically for churches, nonprofits, and community organizations — with tools you'll actually use.
            </p>
        </div>

        {{-- Feature grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($features as $feature)
            @php
                $colors = [
                    'indigo'  => ['bg' => 'bg-indigo-50',  'icon' => 'text-indigo-600',  'border' => 'group-hover:border-indigo-200'],
                    'purple'  => ['bg' => 'bg-purple-50',  'icon' => 'text-purple-600',  'border' => 'group-hover:border-purple-200'],
                    'blue'    => ['bg' => 'bg-blue-50',    'icon' => 'text-blue-600',    'border' => 'group-hover:border-blue-200'],
                    'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-600', 'border' => 'group-hover:border-emerald-200'],
                    'rose'    => ['bg' => 'bg-rose-50',    'icon' => 'text-rose-600',    'border' => 'group-hover:border-rose-200'],
                    'amber'   => ['bg' => 'bg-amber-50',   'icon' => 'text-amber-600',   'border' => 'group-hover:border-amber-200'],
                ];
                $c = $colors[$feature['color']] ?? $colors['indigo'];
            @endphp
            <div class="group relative bg-white rounded-2xl border border-slate-100 p-8 hover:shadow-lg {{ $c['border'] }} transition-all duration-300">
                <div class="w-12 h-12 rounded-xl {{ $c['bg'] }} flex items-center justify-center mb-5">
                    @if($feature['icon'] === 'cube')
                    <svg class="w-6 h-6 {{ $c['icon'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                    @elseif($feature['icon'] === 'swatch')
                    <svg class="w-6 h-6 {{ $c['icon'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z"/></svg>
                    @elseif($feature['icon'] === 'users')
                    <svg class="w-6 h-6 {{ $c['icon'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    @elseif($feature['icon'] === 'currency-dollar')
                    <svg class="w-6 h-6 {{ $c['icon'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($feature['icon'] === 'paint-brush')
                    <svg class="w-6 h-6 {{ $c['icon'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>
                    @else
                    <svg class="w-6 h-6 {{ $c['icon'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3.75h3m-6-7.5h.008v.008H7.5V6zm0 3.75h.008v.008H7.5V9.75z"/></svg>
                    @endif
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ $feature['title'] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>
