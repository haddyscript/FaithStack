<footer class="bg-[#09090b] border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6 py-16">

        <div class="grid md:grid-cols-5 gap-12 mb-16">

            {{-- Brand column --}}
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-white font-bold text-lg tracking-tight">FaithStack</span>
                </div>

                <p class="text-sm text-white/35 leading-relaxed mb-6 max-w-xs">
                    The modern CMS platform for churches, nonprofits, and community organizations. Build beautiful websites in minutes.
                </p>

                <div class="flex gap-3">
                    {{-- Twitter/X --}}
                    <a href="#" class="w-9 h-9 rounded-lg border border-white/10 flex items-center justify-center text-white/40 hover:text-white hover:border-white/20 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    {{-- GitHub --}}
                    <a href="#" class="w-9 h-9 rounded-lg border border-white/10 flex items-center justify-center text-white/40 hover:text-white hover:border-white/20 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                    </a>
                    {{-- LinkedIn --}}
                    <a href="#" class="w-9 h-9 rounded-lg border border-white/10 flex items-center justify-center text-white/40 hover:text-white hover:border-white/20 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Nav columns --}}
            <div>
                <h4 class="text-white text-sm font-semibold mb-5">Product</h4>
                <ul class="space-y-3">
                    @foreach(['Features', 'Themes', 'Pricing', 'Changelog'] as $link)
                    <li><a href="#{{ strtolower($link) }}" class="text-sm text-white/35 hover:text-white/70 transition-colors">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-white text-sm font-semibold mb-5">Company</h4>
                <ul class="space-y-3">
                    @foreach(['About', 'Blog', 'Careers', 'Contact'] as $link)
                    <li><a href="#" class="text-sm text-white/35 hover:text-white/70 transition-colors">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-white text-sm font-semibold mb-5">Account</h4>
                <ul class="space-y-3">
                    <li><a href="/superadmin/login" class="text-sm text-white/35 hover:text-white/70 transition-colors">Log in</a></li>
                    <li><a href="/superadmin/login" class="text-sm text-white/35 hover:text-white/70 transition-colors">Sign up free</a></li>
                    <li><a href="#" class="text-sm text-white/35 hover:text-white/70 transition-colors">Documentation</a></li>
                    <li><a href="#" class="text-sm text-white/35 hover:text-white/70 transition-colors">Support</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-white/5 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs text-white/20">
                &copy; {{ date('Y') }} FaithStack. All rights reserved.
            </p>
            <div class="flex gap-6 text-xs text-white/20">
                <a href="#" class="hover:text-white/50 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white/50 transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-white/50 transition-colors">Cookie Policy</a>
            </div>
        </div>

    </div>
</footer>
