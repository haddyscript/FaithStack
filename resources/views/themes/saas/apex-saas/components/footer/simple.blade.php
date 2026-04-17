<footer class="border-t border-white/10" style="background-color: var(--primary);">
    <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
        <span>© {{ date('Y') }} {{ $tenant->name }}. All rights reserved.</span>

        <div class="flex items-center gap-6">
            @foreach($navItems->take(4) as $item)
                <a href="{{ $item->url }}" class="hover:text-gray-300 transition-colors">{{ $item->name }}</a>
            @endforeach
        </div>

        <span>Powered by <span class="text-gray-400 font-medium">FaithStack</span></span>
    </div>
</footer>
