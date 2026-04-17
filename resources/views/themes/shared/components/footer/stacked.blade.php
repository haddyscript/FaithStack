<footer class="border-t border-white/10" style="background-color: var(--primary);">
    <div class="max-w-3xl mx-auto px-6 py-16 text-center">
        <p class="text-2xl font-bold text-white mb-2">{{ $tenant->name }}</p>
        @if($tenant->address)<p class="text-gray-400 text-sm mb-1">{{ $tenant->address }}</p>@endif
        @if($tenant->phone)<p class="text-gray-400 text-sm mb-1">{{ $tenant->phone }}</p>@endif
        @if($tenant->email)<a href="mailto:{{ $tenant->email }}" class="text-gray-400 text-sm hover:text-white mb-6 inline-block transition-colors">{{ $tenant->email }}</a>@endif

        <div class="flex flex-wrap justify-center gap-6 my-8 text-sm text-gray-400">
            @foreach($navItems as $item)
                <a href="{{ $item->url }}" class="hover:text-white transition-colors">{{ $item->name }}</a>
            @endforeach
        </div>

        <a href="{{ route('donate') }}"
           class="btn-primary inline-block px-8 py-3 {{ $config['button_radius'] ?? 'rounded-lg' }} text-sm font-semibold shadow-lg transition-all mb-8">
            Get Started
        </a>

        <div class="pt-6 border-t border-white/10 text-xs text-gray-600 flex flex-col gap-1">
            <span>© {{ date('Y') }} {{ $tenant->name }}. All rights reserved.</span>
            <span>Powered by <span class="text-gray-400 font-medium">FaithStack</span></span>
        </div>
    </div>
</footer>
