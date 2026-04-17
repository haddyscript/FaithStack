<footer class="border-t border-white/10" style="background-color: var(--primary);">
    <div class="max-w-4xl mx-auto px-6 py-14 text-center">
        <p class="text-xl font-bold text-white mb-6">{{ $tenant->name }}</p>

        <div class="flex flex-wrap justify-center gap-6 mb-8 text-sm text-gray-400">
            @foreach($navItems as $item)
                <a href="{{ $item->url }}" class="hover:text-white transition-colors">{{ $item->name }}</a>
            @endforeach
        </div>

        @if($tenant->email || $tenant->phone)
            <div class="flex flex-wrap justify-center gap-4 mb-8 text-sm text-gray-400">
                @if($tenant->email)
                    <a href="mailto:{{ $tenant->email }}" class="hover:text-white transition-colors">{{ $tenant->email }}</a>
                @endif
                @if($tenant->phone)
                    <span>{{ $tenant->phone }}</span>
                @endif
            </div>
        @endif

        <div class="pt-6 border-t border-white/10 text-xs text-gray-600 flex flex-col gap-1">
            <span>© {{ date('Y') }} {{ $tenant->name }}. All rights reserved.</span>
            <span>Powered by <span class="text-gray-400 font-medium">FaithStack</span></span>
        </div>
    </div>
</footer>
