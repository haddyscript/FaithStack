<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account — FaithStack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        indigo: {
                            50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',
                            400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',
                            800:'#3730a3',900:'#312e81',950:'#1e1b4b'
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            box-sizing: border-box;
        }
        [x-cloak] { display: none !important; }

        @keyframes blobFloat {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(20px,-15px) scale(1.04); }
            66%      { transform: translate(-15px,18px) scale(0.97); }
        }
        .blob { animation: blobFloat var(--dur,22s) ease-in-out infinite; will-change: transform; }

        /* Faith cross pattern */
        .faith-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cpath d='M27 0h6v60h-6z' fill='%23ffffff' fill-opacity='0.018'/%3E%3Cpath d='M0 27h60v6H0z' fill='%23ffffff' fill-opacity='0.018'/%3E%3C/g%3E%3C/svg%3E");
        }

        /* Input focus */
        .field-input {
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        }
        .field-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        }
        .field-input.is-error {
            border-color: #f43f5e !important;
            background-color: #fff1f2;
        }
        .field-input.is-error:focus {
            box-shadow: 0 0 0 3px rgba(244,63,94,0.10);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(5px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.2s ease both; }

        @keyframes statusFade {
            from { opacity: 0; transform: translateX(-3px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .status-fade { animation: statusFade 0.18s ease; }

        .btn-submit {
            transition: all 0.2s cubic-bezier(0.34, 1.4, 0.64, 1);
        }
        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(79,70,229,0.4), 0 4px 12px rgba(79,70,229,0.25);
        }
        .btn-submit:active:not(:disabled) { transform: scale(0.987); }

        .strength-bar { transition: background-color 0.3s ease; }
    </style>
</head>
<body class="h-full font-sans bg-slate-50">

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ══════════════════════════════════════════════════════
         LEFT PANEL — Plan context, social proof
    ══════════════════════════════════════════════════════ --}}
    <div class="relative lg:w-[42%] bg-[#08080c] faith-pattern flex flex-col overflow-hidden px-8 py-10 lg:px-12 lg:py-14">

        {{-- Blobs --}}
        <div class="blob absolute -top-40 -left-32 w-[520px] h-[520px] rounded-full bg-indigo-600/[0.14] blur-3xl pointer-events-none" style="--dur:26s"></div>
        <div class="blob absolute -bottom-28 -right-16 w-[400px] h-[400px] rounded-full bg-purple-700/[0.11] blur-3xl pointer-events-none" style="--dur:20s;animation-delay:-9s"></div>
        <div class="blob absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[280px] h-[280px] rounded-full bg-violet-500/[0.07] blur-3xl pointer-events-none" style="--dur:34s;animation-delay:-16s"></div>

        {{-- Decorative faith cross (top-right) --}}
        <div class="absolute top-16 right-10 opacity-[0.035] pointer-events-none" aria-hidden="true">
            <svg width="88" height="88" viewBox="0 0 24 24" fill="white">
                <path d="M11 2h2v7h7v2h-7v11h-2V11H4V9h7V2z"/>
            </svg>
        </div>
        <div class="absolute bottom-28 left-6 opacity-[0.025] pointer-events-none" aria-hidden="true">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="white">
                <path d="M11 2h2v7h7v2h-7v11h-2V11H4V9h7V2z"/>
            </svg>
        </div>

        <div class="relative flex flex-col h-full">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 mb-10 no-underline w-fit">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-indigo-600/30">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-[17px] tracking-tight">FaithStack</span>
            </a>

            {{-- Plan selector label --}}
            <p class="text-[10.5px] font-bold text-white/25 uppercase tracking-[0.14em] mb-3">Select your plan</p>

            {{-- ── Plan Tabs ── --}}
            @if($allPlans->count() > 1)
            <div class="flex gap-1 bg-white/[0.05] border border-white/[0.07] rounded-xl p-1 mb-6">
                @foreach($allPlans as $p)
                <a href="{{ url('/register') }}?plan={{ $p->slug }}"
                   class="flex-1 rounded-lg py-2.5 px-2 text-center transition-all duration-200 no-underline group
                          {{ $p->slug === $plan->slug
                             ? 'bg-white shadow-sm'
                             : 'hover:bg-white/[0.05]' }}">
                    <div class="text-[12px] font-semibold leading-none transition-colors
                                {{ $p->slug === $plan->slug ? 'text-slate-900' : 'text-white/45 group-hover:text-white/70' }}">
                        {{ $p->name }}
                    </div>
                    <div class="text-[10px] mt-1 leading-none transition-colors
                                {{ $p->slug === $plan->slug ? 'text-slate-500' : 'text-white/25 group-hover:text-white/40' }}">
                        @if($p->isFree()) Free @else ${{ number_format((float)$p->price_monthly, 0) }}/mo @endif
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            {{-- ── Active Plan Card ── --}}
            <div class="bg-white/[0.04] border border-white/[0.08] rounded-2xl p-5 mb-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 min-w-0 mr-3">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-white text-[15px] font-bold tracking-tight">{{ $plan->name }}</h3>
                            @if($plan->badge)
                            <span class="px-2 py-0.5 rounded-full bg-amber-400/15 text-amber-300 text-[10px] font-bold border border-amber-400/20 leading-none flex-shrink-0">{{ $plan->badge }}</span>
                            @endif
                        </div>
                        <p class="text-white/38 text-xs leading-relaxed">{{ $plan->description }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        @if($plan->isFree())
                            <div class="text-[22px] font-bold text-white leading-none">Free</div>
                            @if($plan->trial_days)
                            <div class="text-white/32 text-[10px] mt-1">{{ $plan->trial_days }}-day trial</div>
                            @endif
                        @else
                            <div class="text-[22px] font-bold text-white leading-none">${{ number_format((float)$plan->price_monthly, 0) }}<span class="text-xs font-normal text-white/32">/mo</span></div>
                            <div class="text-white/32 text-[10px] mt-1">14-day trial</div>
                        @endif
                    </div>
                </div>

                <div class="border-t border-white/[0.06] pt-4 grid grid-cols-2 gap-x-4 gap-y-2.5">
                    @foreach($plan->features as $feature)
                    <div class="flex items-start gap-2 text-[11.5px] text-white/58">
                        <svg class="w-3 h-3 text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                        </svg>
                        <span class="leading-snug">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Spacer --}}
            <div class="flex-1"></div>

            {{-- ── Social Proof ── --}}
            <div class="border-t border-white/[0.06] pt-6">

                {{-- Stars + rating --}}
                <div class="flex items-center gap-1 mb-1">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                    <span class="text-white/75 text-[13px] font-bold ml-1">4.9</span>
                    <span class="text-white/28 text-[11px]">/ 5 &middot; 200+ reviews</span>
                </div>
                <p class="text-white/32 text-[11.5px] mb-4">Trusted by 500+ faith organizations worldwide</p>

                {{-- Org name chips --}}
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['Grace Church', 'Bethel Community', 'River of Life', 'Hope Fellowship', 'Cornerstone', 'New Life Worship'] as $org)
                    <span class="px-2.5 py-1 bg-white/[0.05] rounded-lg text-[10.5px] text-white/38 border border-white/[0.07] leading-none">{{ $org }}</span>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         RIGHT PANEL — Registration Form
    ══════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col justify-center px-8 py-12 lg:px-14 xl:px-20 bg-slate-50">
        <div class="w-full max-w-[440px] mx-auto">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-[27px] font-bold text-slate-900 tracking-tight leading-tight mb-2">
                    Create your account
                </h1>
                <p class="text-slate-500 text-sm leading-relaxed">
                    @if($plan->isFree())
                        Start your {{ $plan->trial_days }}-day free trial — no credit card required.
                    @else
                        Start with a 14-day free trial. Upgrade or cancel anytime.
                    @endif
                </p>
            </div>

            {{-- Validation errors --}}
            @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm">
                <p class="font-semibold mb-1.5">Please fix the following:</p>
                <ul class="list-disc list-inside space-y-0.5 text-rose-600 text-[13px]">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Success flash --}}
            @if(session('success'))
            <div class="mb-6 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ url('/register') }}" x-data="registrationForm" novalidate>
                @csrf
                <input type="hidden" name="plan_slug" value="{{ $plan->slug }}">

                <div class="space-y-4">

                    {{-- Organization name --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5 tracking-tight" for="org_name">
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
                               class="field-input w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-900 text-sm placeholder-slate-300 bg-white {{ $errors->has('org_name') ? 'is-error' : '' }}"
                               required>
                        @error('org_name')
                        <p class="mt-1.5 text-[12px] text-rose-500 fade-up">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Subdomain --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5 tracking-tight" for="subdomain">
                            Your website address
                        </label>
                        <div class="flex rounded-xl border overflow-hidden focus-within:shadow-[0_0_0_3px_rgba(99,102,241,0.12)] transition-all duration-150
                                    {{ $errors->has('subdomain') ? 'border-rose-400' : 'border-slate-200 focus-within:border-indigo-500' }}">
                            <input id="subdomain"
                                   name="subdomain"
                                   type="text"
                                   autocomplete="off"
                                   value="{{ old('subdomain') }}"
                                   placeholder="gracechurch"
                                   x-model="subdomain"
                                   @input="onSubdomainInput($event.target.value)"
                                   class="flex-1 px-4 py-3 text-slate-900 text-sm placeholder-slate-300 bg-white outline-none min-w-0"
                                   required>
                            <div class="flex items-center px-3.5 border-l border-slate-100 bg-slate-50 text-[11px] text-slate-400 font-mono whitespace-nowrap flex-shrink-0">
                                .{{ config('app.base_domain', 'faithstack.test') }}
                            </div>
                        </div>

                        {{-- Live availability status --}}
                        <div class="mt-2 h-[18px] flex items-center">
                            <template x-if="subStatus === 'checking'">
                                <div class="flex items-center gap-1.5 status-fade">
                                    <svg class="w-3.5 h-3.5 text-slate-400 animate-spin" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                    </svg>
                                    <span class="text-[12px] text-slate-400">Checking availability…</span>
                                </div>
                            </template>
                            <template x-if="subStatus === 'available'">
                                <div class="flex items-center gap-1.5 status-fade">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-[12px] text-emerald-600 font-medium">
                                        <span x-text="subdomain + '.{{ config('app.base_domain', 'faithstack.test') }}'"></span> is available
                                    </span>
                                </div>
                            </template>
                            <template x-if="subStatus === 'taken'">
                                <div class="flex items-center gap-1.5 status-fade">
                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-[12px] text-rose-500 font-medium">Already taken — try another</span>
                                </div>
                            </template>
                        </div>

                        @error('subdomain')
                        <p class="mt-0.5 text-[12px] text-rose-500 fade-up">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5 tracking-tight" for="email">
                            Email address
                        </label>
                        <input id="email"
                               name="email"
                               type="email"
                               autocomplete="email"
                               value="{{ old('email') }}"
                               placeholder="you@yourchurch.org"
                               class="field-input w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-900 text-sm placeholder-slate-300 bg-white {{ $errors->has('email') ? 'is-error' : '' }}"
                               required>
                        @error('email')
                        <p class="mt-1.5 text-[12px] text-rose-500 fade-up">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5 tracking-tight" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <input id="password"
                                   name="password"
                                   :type="showPassword ? 'text' : 'password'"
                                   autocomplete="new-password"
                                   placeholder="Minimum 8 characters"
                                   x-model="password"
                                   class="field-input w-full px-4 py-3 pr-11 rounded-xl border border-slate-200 text-slate-900 text-sm placeholder-slate-300 bg-white {{ $errors->has('password') ? 'is-error' : '' }}"
                                   required>
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-0.5">
                                <svg x-show="!showPassword" class="w-[17px] h-[17px]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-[17px] h-[17px]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Password strength meter --}}
                        <div x-show="password.length > 0" x-cloak class="mt-2.5 fade-up">
                            <div class="flex gap-1.5 mb-1.5">
                                <div class="strength-bar h-1 flex-1 rounded-full" :class="passwordStrength >= 1 ? strengthBarColor : 'bg-slate-200'"></div>
                                <div class="strength-bar h-1 flex-1 rounded-full" :class="passwordStrength >= 2 ? strengthBarColor : 'bg-slate-200'"></div>
                                <div class="strength-bar h-1 flex-1 rounded-full" :class="passwordStrength >= 3 ? strengthBarColor : 'bg-slate-200'"></div>
                                <div class="strength-bar h-1 flex-1 rounded-full" :class="passwordStrength >= 4 ? strengthBarColor : 'bg-slate-200'"></div>
                            </div>
                            <span class="text-[12px] font-medium transition-colors duration-300" :class="strengthTextColor" x-text="strengthLabel"></span>
                        </div>

                        @error('password')
                        <p class="mt-1.5 text-[12px] text-rose-500 fade-up">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm password --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5 tracking-tight" for="password_confirmation">
                            Confirm password
                        </label>
                        <input id="password_confirmation"
                               name="password_confirmation"
                               :type="showPassword ? 'text' : 'password'"
                               autocomplete="new-password"
                               placeholder="Repeat your password"
                               class="field-input w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-900 text-sm placeholder-slate-300 bg-white"
                               required>
                    </div>

                </div>

                {{-- ── Trust badges above submit ── --}}
                <div class="mt-6 mb-4 flex items-center justify-center gap-4 py-3.5 border-y border-slate-200/70">
                    <span class="flex items-center gap-1.5 text-[11px] text-slate-400">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/>
                        </svg>
                        SSL secured
                    </span>
                    <span class="w-px h-3 bg-slate-200/80 flex-shrink-0"></span>
                    <span class="flex items-center gap-1.5 text-[11px] text-slate-400">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
                        </svg>
                        GDPR compliant
                    </span>
                    <span class="w-px h-3 bg-slate-200/80 flex-shrink-0"></span>
                    <span class="flex items-center gap-1.5 text-[11px] text-slate-400">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                        </svg>
                        Cancel anytime
                    </span>
                </div>

                {{-- ── Submit button ── --}}
                <button type="submit"
                        :disabled="loading"
                        @click="loading = true"
                        class="btn-submit w-full flex items-center justify-center gap-2.5 py-[14px] px-6 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/25 disabled:opacity-60 disabled:cursor-not-allowed group">
                    <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    <span x-text="loading ? 'Creating your account…' : '{{ $plan->cta_label }}'">{{ $plan->cta_label }}</span>
                    <svg x-show="!loading" class="w-4 h-4 flex-shrink-0 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </button>

                {{-- Legal --}}
                <p class="mt-3 text-center text-[11px] text-slate-400 leading-relaxed">
                    By signing up you agree to our
                    <a href="#" class="text-indigo-500 hover:text-indigo-600 hover:underline transition-colors">Terms of Service</a>
                    and
                    <a href="#" class="text-indigo-500 hover:text-indigo-600 hover:underline transition-colors">Privacy Policy</a>
                </p>

                {{-- Need help --}}
                <div class="mt-3.5 text-center">
                    <span class="text-[12px] text-slate-400">Need help?&nbsp;</span>
                    <a href="#" class="text-[12px] text-indigo-500 hover:text-indigo-600 font-medium transition-colors">Chat with us</a>
                    <span class="text-[12px] text-slate-300">&nbsp;·&nbsp;</span>
                    <a href="#" class="text-[12px] text-indigo-500 hover:text-indigo-600 font-medium transition-colors">View FAQ</a>
                </div>

            </form>

            {{-- Login link --}}
            <div class="mt-7 pt-5 border-t border-slate-200 text-center">
                <p class="text-sm text-slate-500">
                    Already have an account?
                    <a href="{{ route('superadmin.login') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Sign in instead</a>
                </p>
            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('registrationForm', () => ({
        orgName:      @json(old('org_name', '')),
        subdomain:    @json(old('subdomain', '')),
        showPassword: false,
        loading:      false,
        password:     '',
        subStatus:    null,
        subMessage:   '',
        _prevAutoSubdomain: @json(old('subdomain', '')),
        _subTimer:    null,

        init() {
            if (this.subdomain && this.subdomain.length >= 2) {
                this.checkSubdomain();
            }
        },

        get passwordStrength() {
            const p = this.password;
            if (!p) return 0;
            let s = 0;
            if (p.length >= 8)           s++;
            if (/[A-Z]/.test(p))         s++;
            if (/[0-9]/.test(p))         s++;
            if (/[^A-Za-z0-9]/.test(p))  s++;
            return s;
        },

        get strengthLabel()    { return ['','Weak','Fair','Good','Strong'][this.passwordStrength] || ''; },
        get strengthBarColor() { return ['','bg-rose-400','bg-amber-400','bg-blue-500','bg-emerald-500'][this.passwordStrength] || 'bg-slate-200'; },
        get strengthTextColor(){ return ['','text-rose-500','text-amber-500','text-blue-600','text-emerald-500'][this.passwordStrength] || ''; },

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
            this.checkSubdomain();
        },

        onSubdomainInput(val) {
            const clean = val.toLowerCase().replace(/[^a-z0-9-]/g, '').replace(/^-+|-+$/g, '');
            this.subdomain = clean;
            this.checkSubdomain();
        },

        checkSubdomain() {
            const sub = this.subdomain;
            if (!sub || sub.length < 2) { this.subStatus = null; return; }
            this.subStatus = 'checking';
            clearTimeout(this._subTimer);
            this._subTimer = setTimeout(async () => {
                try {
                    const r = await fetch(`/register/check-subdomain?subdomain=${encodeURIComponent(sub)}`);
                    const d = await r.json();
                    if (this.subdomain === sub) {
                        this.subStatus  = d.available ? 'available' : 'taken';
                        this.subMessage = d.message || '';
                    }
                } catch {
                    this.subStatus = null;
                }
            }, 650);
        },
    }));
});
</script>

</body>
</html>
