<footer class="border-t border-white/10" style="background-color: var(--primary);">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid md:grid-cols-4 gap-10 mb-12">
            {{-- Brand --}}
            <div class="md:col-span-2">
                <p class="text-xl font-bold text-white mb-3">{{ $tenant->name }}</p>
                @if($tenant->address)
                    <p class="text-gray-400 text-sm mb-1">{{ $tenant->address }}</p>
                @endif
                @if($tenant->phone)
                    <p class="text-gray-400 text-sm mb-1">{{ $tenant->phone }}</p>
                @endif
                @if($tenant->email)
                    <a href="mailto:{{ $tenant->email }}" class="text-gray-400 text-sm hover:text-white transition-colors">
                        {{ $tenant->email }}
                    </a>
                @endif
            </div>

            {{-- Nav links --}}
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-gray-500 mb-4">Navigation</p>
                <ul class="space-y-2">
                    @foreach($navItems as $item)
                        <li>
                            <a href="{{ $item->url }}" class="text-gray-400 text-sm hover:text-white transition-colors">
                                {{ $item->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- CTA --}}
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-gray-500 mb-4">Support</p>
                <a href="{{ route('donate') }}"
                   class="btn-primary inline-block px-6 py-3 {{ $config['button_radius'] ?? 'rounded-lg' }} text-sm font-semibold shadow-lg transition-all duration-150">
                    Get Started
                </a>
            </div>
        </div>

        <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-600">
            <span>© {{ date('Y') }} {{ $tenant->name }}. All rights reserved.</span>
            <span>Powered by <span class="text-gray-400 font-medium">FaithStack</span></span>
        </div>
    </div>
</footer>
