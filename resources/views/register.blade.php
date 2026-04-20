<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account — FaithStack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: { 50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81',950:'#1e1b4b' },
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { -webkit-font-smoothing: antialiased; box-sizing: border-box; }

        /* Noise texture */
        body::after {
            content: ''; position: fixed; inset: 0; z-index: 9999;
            pointer-events: none; opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 180px 180px;
        }

        @keyframes blobFloat {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(25px,-18px) scale(1.05); }
            66%      { transform: translate(-18px,22px) scale(0.97); }
        }
        .blob { animation: blobFloat var(--dur,22s) ease-in-out infinite; will-change: transform; }

        /* Input focus ring */
        .field-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .field-input { transition: border-color 0.2s ease, box-shadow 0.2s ease; }

        /* Subdomain preview */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(2px); } to { opacity: 1; transform: translateY(0); } }
        .subdomain-preview { animation: fadeIn 0.2s ease; }

        /* Plan card active */
        .plan-pill.active { border-color: #6366f1; background: rgba(99,102,241,0.08); }
    </style>
</head>
<body class="h-full bg-white">

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ── LEFT: Brand / Plan Summary ── --}}
    <div class="relative lg:w-5/12 bg-[#09090b] flex flex-col overflow-hidden px-8 py-10 lg:px-12 lg:py-16">

        {{-- Background grid --}}
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:48px_48px] pointer-events-none"></div>

        {{-- Blobs --}}
        <div class="blob absolute top-0 -left-40 w-[500px] h-[500px] rounded-full bg-indigo-600/12 blur-3xl pointer-events-none" style="--dur:24s"></div>
        <div class="blob absolute bottom-0 right-0 w-[400px] h-[400px] rounded-full bg-purple-600/10 blur-3xl pointer-events-none" style="--dur:18s;animation-delay:-8s"></div>

        <div class="relative flex flex-col h-full">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 mb-12 no-underline">
                <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4.5 h-4.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1H3a1 1 0 01-1-1V4zM8 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1H9a1 1 0 01-1-1V4zM15 3a1 1 0 00-1 1v12a1 1 0 001 1h2a1 1 0 001-1V4a1 1 0 00-1-1h-2z"/></svg>
                </div>
                <span class="text-white font-bold text-lg tracking-tight">FaithStack</span>
            </a>

            {{-- Plan card --}}
            <div class="flex-1">
                {{-- Selected plan heading --}}
                <p class="text-white/35 text-xs font-semibold uppercase tracking-widest mb-4">Your selected plan</p>

                <div class="bg-white/[0.04] border border-white/[0.08] rounded-2xl p-6 mb-8">
                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <div class="flex items-center gap-2.5 mb-1">
                                <h2 class="text-white text-xl font-bold">{{ $plan->name }}</h2>
                                @if($plan->badge)
                                <span class="px-2 py-0.5 rounded-full bg-amber-400/15 text-amber-300 text-[10px] font-bold border border-amber-400/20">{{ $plan->badge }}</span>
                                @endif
                            </div>
                            <p class="text-white/40 text-sm">{{ $plan->description }}</p>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            @if($plan->isFree())
                                <div class="text-2xl font-bold text-white">Free</div>
                                @if($plan->trial_days)
                                <div class="text-white/40 text-xs mt-0.5">{{ $plan->trial_days }}-day trial</div>
                                @endif
                            @else
                                <div class="text-2xl font-bold text-white">${{ number_format((float)$plan->price_monthly, 0) }}<span class="text-sm font-normal text-white/40">/mo</span></div>
                                <div class="text-white/40 text-xs mt-0.5">14-day trial included</div>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-white/[0.06] pt-5 space-y-2.5">
                        @foreach($plan->features as $feature)
                        <div class="flex items-center gap-2.5 text-sm text-white/70">
                            <svg class="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            {{ $feature }}
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Switch plan links --}}
                @if($allPlans->count() > 1)
                <div>
                    <p class="text-white/25 text-xs mb-3">Switch plan:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($allPlans as $p)
                        <a href="{{ url('/register') }}?plan={{ $p->slug }}"
                           class="plan-pill px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all duration-200 no-underline {{ $p->slug === $plan->slug ? 'border-indigo-500/70 bg-indigo-500/10 text-white' : 'border-white/[0.08] text-white/40 hover:text-white hover:border-white/20' }}">
                            {{ $p->name }}
                            @if(!$p->isFree())
                                <span class="ml-1 opacity-60">${{ number_format((float)$p->price_monthly, 0) }}</span>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Social proof --}}
            <div class="mt-10 pt-8 border-t border-white/[0.06]">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex -space-x-2">
                        @foreach(['bg-violet-600','bg-blue-600','bg-emerald-600','bg-rose-600'] as $c)
                        <div class="w-7 h-7 rounded-full {{ $c }} border-2 border-[#09090b] flex items-center justify-center text-[9px] font-bold text-white">★</div>
                        @endforeach
                    </div>
                    <span class="text-white/40 text-xs">Joined by 500+ organizations</span>
                </div>
                <div class="flex flex-wrap gap-x-5 gap-y-1.5 text-[11px] text-white/22">
                    @foreach(['SSL secured','5-minute setup','Cancel anytime','GDPR compliant'] as $badge)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        {{ $badge }}
                    </span>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- ── RIGHT: Registration Form ── --}}
    <div class="flex-1 flex flex-col justify-center px-8 py-12 lg:px-16 xl:px-24 bg-white">
        <div class="w-full max-w-md mx-auto">

            {{-- Header --}}
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight mb-2">Create your account</h1>
                <p class="text-slate-500 text-sm">
                    @if($plan->isFree())
                        Start your {{ $plan->trial_days }}-day free trial. No credit card required.
                    @else
                        Start with a 14-day trial. Upgrade or cancel anytime.
                    @endif
                </p>
            </div>

            {{-- Success flash --}}
            @if(session('success'))
            <div class="mb-6 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Validation errors summary --}}
            @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm">
                <p class="font-semibold mb-1">Please fix the following:</p>
                <ul class="list-disc list-inside space-y-0.5 text-rose-600">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ url('/register') }}" x-data="registrationForm" novalidate>
                @csrf
                <input type="hidden" name="plan_slug" value="{{ $plan->slug }}">

                <div class="space-y-5">

                    {{-- Organization name --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="org_name">
                            Organization name
                        </label>
                        <input id="org_name"
                               name="org_name"
                               type="text"
                               autocomplete="organization"
                               value="{{ old('org_name') }}"
                               placeholder="Grace Community Church"
                               x-model="orgName"
                               @input="syncSubdomain"
                               class="field-input w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-900 text-sm placeholder-slate-400 bg-white {{ $errors->has('org_name') ? 'border-rose-400 bg-rose-50' : '' }}"
                               required>
                        @error('org_name')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Subdomain --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="subdomain">
                            Choose your subdomain
                        </label>
                        <div class="flex rounded-xl border {{ $errors->has('subdomain') ? 'border-rose-400' : 'border-slate-200' }} overflow-hidden focus-within:border-indigo-500 focus-within:shadow-[0_0_0_3px_rgba(99,102,241,0.15)] transition-all duration-200">
                            <input id="subdomain"
                                   name="subdomain"
                                   type="text"
                                   autocomplete="off"
                                   value="{{ old('subdomain') }}"
                                   placeholder="gracechurch"
                                   x-model="subdomain"
                                   @input="subdomain = $event.target.value.toLowerCase().replace(/[^a-z0-9-]/g, '').replace(/^-+|-+$/g, '')"
                                   class="flex-1 px-4 py-3 text-slate-900 text-sm placeholder-slate-400 bg-white outline-none min-w-0 {{ $errors->has('subdomain') ? 'bg-rose-50' : '' }}"
                                   required>
                            <div class="flex items-center px-3 bg-slate-50 border-l border-slate-200 text-xs text-slate-400 font-medium whitespace-nowrap flex-shrink-0">
                                .{{ config('app.base_domain', 'faithstack.test') }}
                            </div>
                        </div>
                        <div x-show="subdomain.length >= 2" x-cloak class="mt-1.5 subdomain-preview">
                            <span class="text-xs text-indigo-600 font-medium">
                                Your site: <span x-text="subdomain + '.{{ config('app.base_domain', 'faithstack.test') }}'"></span>
                            </span>
                        </div>
                        @error('subdomain')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="email">
                            Email address
                        </label>
                        <input id="email"
                               name="email"
                               type="email"
                               autocomplete="email"
                               value="{{ old('email') }}"
                               placeholder="you@yourchurch.org"
                               class="field-input w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-900 text-sm placeholder-slate-400 bg-white {{ $errors->has('email') ? 'border-rose-400 bg-rose-50' : '' }}"
                               required>
                        @error('email')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <input id="password"
                                   name="password"
                                   :type="showPassword ? 'text' : 'password'"
                                   autocomplete="new-password"
                                   placeholder="Minimum 8 characters"
                                   class="field-input w-full px-4 py-3 pr-11 rounded-xl border border-slate-200 text-slate-900 text-sm placeholder-slate-400 bg-white {{ $errors->has('password') ? 'border-rose-400 bg-rose-50' : '' }}"
                                   required>
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg x-show="!showPassword" class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <svg x-show="showPassword" x-cloak class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                        </div>
                        @error('password')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm password --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="password_confirmation">
                            Confirm password
                        </label>
                        <input id="password_confirmation"
                               name="password_confirmation"
                               :type="showPassword ? 'text' : 'password'"
                               autocomplete="new-password"
                               placeholder="Repeat your password"
                               class="field-input w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-900 text-sm placeholder-slate-400 bg-white"
                               required>
                    </div>

                </div>

                {{-- Submit --}}
                <button type="submit"
                        :disabled="loading"
                        @click="loading = true"
                        class="mt-8 w-full flex items-center justify-center gap-2.5 py-3.5 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white font-semibold text-sm transition-all duration-200 shadow-lg shadow-indigo-600/25 disabled:opacity-70 disabled:cursor-not-allowed">
                    <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                    <span x-text="loading ? 'Creating your account…' : '{{ $plan->cta_label }}'">{{ $plan->cta_label }}</span>
                </button>

                {{-- Legal --}}
                <p class="mt-4 text-center text-xs text-slate-400 leading-relaxed">
                    By creating an account you agree to our
                    <a href="#" class="text-indigo-600 hover:underline">Terms of Service</a>
                    and
                    <a href="#" class="text-indigo-600 hover:underline">Privacy Policy</a>.
                </p>

            </form>

            {{-- Login link --}}
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-500">
                    Already have an account?
                    <a href="{{ url('/') }}#pricing" class="text-indigo-600 font-semibold hover:text-indigo-700">Sign in instead</a>
                </p>
            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('registrationForm', () => ({
        orgName:      '',
        subdomain:    '',
        showPassword: false,
        loading:      false,

        syncSubdomain() {
            if (this.subdomain && this.subdomain !== this._prevAutoSubdomain) return;
            const auto = this.orgName
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '')
                .slice(0, 63);
            this.subdomain = auto;
            this._prevAutoSubdomain = auto;
        },

        _prevAutoSubdomain: '',
    }));
});
</script>

{{-- Restore Alpine cloak --}}
<style>[x-cloak]{display:none!important}</style>

</body>
</html>
