<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Your Account — FaithStack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
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
        window.stripeKey = @json($stripeKey);
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; box-sizing: border-box; }
        [x-cloak] { display: none !important; }

        @keyframes blobFloat {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(20px,-15px) scale(1.04); }
            66%      { transform: translate(-15px,18px) scale(0.97); }
        }
        .blob { animation: blobFloat var(--dur,22s) ease-in-out infinite; will-change: transform; }

        .faith-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cpath d='M27 0h6v60h-6z' fill='%23ffffff' fill-opacity='0.018'/%3E%3Cpath d='M0 27h60v6H0z' fill='%23ffffff' fill-opacity='0.018'/%3E%3C/g%3E%3C/svg%3E");
        }

        /* ── Base input ── */
        .field-input {
            border-width: 1.5px; border-style: solid; border-color: #e2e8f0;
            background-color: #ffffff; color: #0f172a;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .field-input::placeholder { color: #cbd5e1; }
        .field-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
        .field-input.field-error { border-color: #DC2626 !important; background-color: rgba(254,242,242,0.55); }
        .field-input.field-error:focus { border-color: #DC2626 !important; box-shadow: 0 0 0 3px rgba(220,38,38,0.10) !important; }

        .subdomain-wrap { border: 1.5px solid #e2e8f0; transition: border-color 0.15s ease, box-shadow 0.15s ease; }
        .subdomain-wrap:focus-within { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
        .subdomain-wrap.field-error { border-color: #DC2626 !important; background-color: rgba(254,242,242,0.55); }

        /* ── Stripe card element container ── */
        .stripe-field {
            border: 1.5px solid #e2e8f0; background: #ffffff; border-radius: 12px;
            padding: 13px 16px; transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .stripe-field.focused { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
        .stripe-field.has-error { border-color: #DC2626 !important; background: rgba(254,242,242,0.55); }

        .err-msg { font-size: 12.5px; font-weight: 500; color: #B91C1C; margin-top: 5px; line-height: 1.4; }
        .ok-msg  { font-size: 12.5px; font-weight: 500; color: #059669; margin-top: 5px; line-height: 1.4; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(4px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.18s ease both; }

        @keyframes statusSlide { from { opacity:0; transform:translateX(-3px); } to { opacity:1; transform:translateX(0); } }
        .status-slide { animation: statusSlide 0.18s ease; }

        @keyframes stepIn { from { opacity:0; transform:translateX(18px); } to { opacity:1; transform:translateX(0); } }
        @keyframes stepOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(-18px); } }
        .step-enter { animation: stepIn 0.22s ease both; }

        .btn-primary { transition: all 0.2s cubic-bezier(0.34,1.4,0.64,1); }
        .btn-primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 10px 28px rgba(79,70,229,0.35); }
        .btn-primary:active:not(:disabled) { transform: scale(0.987); }

        .strength-seg { transition: background-color 0.3s ease; height: 4px; border-radius: 9999px; flex: 1; }

        /* Step indicator connector */
        .step-line { height: 1.5px; flex: 1; transition: background-color 0.3s ease; }
    </style>
</head>
<body class="h-full font-sans bg-slate-50">

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ═══════════════════════════════════════════
         LEFT — Brand panel (unchanged)
    ═══════════════════════════════════════════ --}}
    <div class="relative lg:w-[42%] bg-[#08080c] faith-pattern flex flex-col overflow-hidden px-8 py-10 lg:px-12 lg:py-14">

        <div class="blob absolute -top-40 -left-32 w-[520px] h-[520px] rounded-full bg-indigo-600/[0.14] blur-3xl pointer-events-none" style="--dur:26s"></div>
        <div class="blob absolute -bottom-28 -right-16 w-[400px] h-[400px] rounded-full bg-purple-700/[0.11] blur-3xl pointer-events-none" style="--dur:20s;animation-delay:-9s"></div>
        <div class="blob absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[280px] h-[280px] rounded-full bg-violet-500/[0.07] blur-3xl pointer-events-none" style="--dur:34s;animation-delay:-16s"></div>

        <div class="absolute top-16 right-10 opacity-[0.038] pointer-events-none" aria-hidden="true">
            <svg width="88" height="88" viewBox="0 0 24 24" fill="white"><path d="M11 2h2v7h7v2h-7v11h-2V11H4V9h7V2z"/></svg>
        </div>
        <div class="absolute bottom-28 left-6 opacity-[0.025] pointer-events-none" aria-hidden="true">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="white"><path d="M11 2h2v7h7v2h-7v11h-2V11H4V9h7V2z"/></svg>
        </div>

        <div class="relative flex flex-col h-full">
            <a href="/" class="flex items-center gap-2.5 mb-10 no-underline w-fit">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-indigo-600/30">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-[17px] tracking-tight">FaithStack</span>
            </a>

            @if($allPlans->count() > 1)
            <p class="text-[10.5px] font-bold text-white/50 uppercase tracking-[0.14em] mb-3">Select your plan</p>
            <div class="flex gap-1 bg-white/[0.05] border border-white/[0.07] rounded-xl p-1 mb-6">
                @foreach($allPlans as $p)
                <a href="{{ url('/register') }}?plan={{ $p->slug }}"
                   class="flex-1 rounded-lg py-2.5 px-2 text-center transition-all duration-200 no-underline group
                          {{ $p->slug === $plan->slug ? 'bg-white shadow-sm' : 'hover:bg-white/[0.05]' }}">
                    <div class="text-[12px] font-semibold leading-none transition-colors
                                {{ $p->slug === $plan->slug ? 'text-slate-900' : 'text-white/60 group-hover:text-white/85' }}">
                        {{ $p->name }}
                    </div>
                    <div class="text-[10px] mt-1 leading-none transition-colors
                                {{ $p->slug === $plan->slug ? 'text-slate-500' : 'text-white/40 group-hover:text-white/60' }}">
                        @if($p->isFree()) Free @else ${{ number_format((float)$p->price_monthly, 0) }}/mo @endif
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            <div class="bg-white/[0.04] border border-white/[0.08] rounded-2xl p-5 mb-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 min-w-0 mr-3">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-white text-[15px] font-bold tracking-tight">{{ $plan->name }}</h3>
                            @if($plan->badge)
                            <span class="px-2 py-0.5 rounded-full bg-amber-400/15 text-amber-300 text-[10px] font-bold border border-amber-400/20 leading-none flex-shrink-0">{{ $plan->badge }}</span>
                            @endif
                        </div>
                        <p class="text-white/60 text-xs leading-relaxed">{{ $plan->description }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        @if($plan->isFree())
                            <div class="text-[22px] font-bold text-white leading-none">Free</div>
                            @if($plan->trial_days)
                            <div class="text-white/55 text-[10px] mt-1">{{ $plan->trial_days }}-day trial</div>
                            @endif
                        @else
                            <div class="text-[22px] font-bold text-white leading-none">${{ number_format((float)$plan->price_monthly, 0) }}<span class="text-xs font-normal text-white/55">/mo</span></div>
                            <div class="text-white/55 text-[10px] mt-1">{{ $plan->effectiveTrialDays() }}-day trial</div>
                        @endif
                    </div>
                </div>
                <div class="border-t border-white/[0.06] pt-4 grid grid-cols-2 gap-x-4 gap-y-2.5">
                    @foreach($plan->features as $feature)
                    <div class="flex items-start gap-2 text-[11.5px] text-white/75">
                        <svg class="w-3 h-3 text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                        </svg>
                        <span class="leading-snug">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex-1"></div>

            <div class="border-t border-white/[0.06] pt-6">
                <div class="flex items-center gap-1 mb-1">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                    <span class="text-white text-[13px] font-bold ml-1">4.9</span>
                    <span class="text-white/50 text-[11px]">/ 5 &middot; 200+ reviews</span>
                </div>
                <p class="text-white/55 text-[11.5px] mb-4">Trusted by 500+ faith organizations worldwide</p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['Grace Church', 'Bethel Community', 'River of Life', 'Hope Fellowship', 'Cornerstone', 'New Life Worship'] as $org)
                    <span class="px-2.5 py-1 bg-white/[0.08] rounded-lg text-[10.5px] text-white/60 border border-white/[0.12] leading-none">{{ $org }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         RIGHT — Multi-step registration form
    ═══════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col justify-center px-8 py-12 lg:px-14 xl:px-20 bg-slate-50">
        <div class="w-full max-w-[460px] mx-auto">

            {{-- ── Step indicator ── --}}
            <div x-data="registrationForm" id="reg-root">

            <div class="flex items-center gap-0 mb-8">
                {{-- Step 1 --}}
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[12px] font-bold transition-all duration-300 flex-shrink-0"
                         :class="currentStep > 1
                             ? 'bg-indigo-600 text-white'
                             : currentStep === 1
                                 ? 'bg-indigo-600 text-white ring-4 ring-indigo-100'
                                 : 'bg-slate-200 text-slate-500'">
                        <svg x-show="currentStep > 1" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span x-show="currentStep <= 1">1</span>
                    </div>
                    <span class="text-[10px] font-semibold whitespace-nowrap transition-colors duration-300"
                          :class="currentStep >= 1 ? 'text-indigo-600' : 'text-slate-400'">Account</span>
                </div>

                <div class="step-line mx-2 mb-4"
                     :class="currentStep > 1 ? 'bg-indigo-600' : 'bg-slate-200'"></div>

                {{-- Step 2 --}}
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[12px] font-bold transition-all duration-300 flex-shrink-0"
                         :class="currentStep > 2
                             ? 'bg-indigo-600 text-white'
                             : currentStep === 2
                                 ? 'bg-indigo-600 text-white ring-4 ring-indigo-100'
                                 : 'bg-slate-200 text-slate-500'">
                        <svg x-show="currentStep > 2" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span x-show="currentStep <= 2">2</span>
                    </div>
                    <span class="text-[10px] font-semibold whitespace-nowrap transition-colors duration-300"
                          :class="currentStep >= 2 ? 'text-indigo-600' : 'text-slate-400'">Billing</span>
                </div>

                <div class="step-line mx-2 mb-4"
                     :class="currentStep > 2 ? 'bg-indigo-600' : 'bg-slate-200'"></div>

                {{-- Step 3 --}}
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[12px] font-bold transition-all duration-300 flex-shrink-0"
                         :class="currentStep === 3
                             ? 'bg-indigo-600 text-white ring-4 ring-indigo-100'
                             : 'bg-slate-200 text-slate-500'">
                        <span>3</span>
                    </div>
                    <span class="text-[10px] font-semibold whitespace-nowrap transition-colors duration-300"
                          :class="currentStep === 3 ? 'text-indigo-600' : 'text-slate-400'">Payment</span>
                </div>
            </div>

            {{-- ── Step header ── --}}
            <div class="mb-7">
                <h1 class="text-[26px] font-bold text-slate-900 tracking-tight leading-tight mb-1.5" x-text="stepTitle"></h1>
                <p class="text-slate-500 text-sm leading-relaxed" x-text="stepSubtitle"></p>
            </div>

            {{-- ── Server-side error banner ── --}}
            @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                <p class="font-semibold mb-1.5">Please fix the following:</p>
                <ul class="list-disc list-inside space-y-0.5 text-[13px]">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-5 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- ═══════════════════════════════════
                 FORM
            ═══════════════════════════════════ --}}
            <form x-ref="regForm"
                  action="{{ url('/register') }}"
                  method="POST"
                  novalidate>
                @csrf
                <input type="hidden" name="plan_slug"         value="{{ $plan->slug }}">
                <input type="hidden" name="payment_method_id" id="payment_method_id" x-model="paymentMethodId">

                {{-- ════════════════════════════
                     STEP 1 — Account info
                ════════════════════════════ --}}
                <div x-show="currentStep === 1" class="step-enter space-y-5">

                    {{-- Organization Name --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="org_name">Organization name</label>
                        <input id="org_name" name="org_name" type="text" autocomplete="organization"
                               value="{{ old('org_name') }}" placeholder="Grace Community Church"
                               x-model="orgName" @input="syncSubdomain" @blur="orgNameTouched = true"
                               :class="orgNameError ? 'field-error' : ''"
                               class="field-input w-full px-4 py-3 rounded-xl text-sm" required>
                        <p x-show="orgNameError" x-cloak x-text="orgNameError" class="err-msg fade-up"></p>
                        @error('org_name')<p x-show="!orgNameError" class="err-msg fade-up">{{ $message }}</p>@enderror
                    </div>

                    {{-- Subdomain --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="subdomain">Your website address</label>
                        <div class="flex rounded-xl overflow-hidden subdomain-wrap" :class="subStatus === 'taken' ? 'field-error' : ''">
                            <input id="subdomain" name="subdomain" type="text" autocomplete="off"
                                   value="{{ old('subdomain') }}" placeholder="gracechurch"
                                   x-model="subdomain" @input="onSubdomainInput($event.target.value)"
                                   class="flex-1 px-4 py-3 text-slate-900 text-sm placeholder-slate-300 bg-transparent outline-none min-w-0" required>
                            <div class="flex items-center px-3.5 border-l border-slate-100 bg-slate-50/80 text-[11px] text-slate-400 font-mono whitespace-nowrap flex-shrink-0">
                                .{{ config('app.base_domain', 'faithstack.test') }}
                            </div>
                        </div>
                        <div class="mt-1.5 h-5 flex items-center">
                            <template x-if="subStatus === 'checking'">
                                <div class="flex items-center gap-1.5 status-slide">
                                    <svg class="w-3.5 h-3.5 text-slate-400 animate-spin" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                    <span style="font-size:12.5px;font-weight:500;color:#64748b;">Checking availability…</span>
                                </div>
                            </template>
                            <template x-if="subStatus === 'available'">
                                <div class="flex items-center gap-1.5 status-slide">
                                    <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                    <span class="ok-msg" style="margin-top:0"><span x-text="subdomain"></span>.{{ config('app.base_domain', 'faithstack.test') }} is available</span>
                                </div>
                            </template>
                            <template x-if="subStatus === 'taken'">
                                <div class="flex items-center gap-1.5 status-slide">
                                    <svg class="w-3.5 h-3.5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                                    <span class="err-msg" style="margin-top:0">This subdomain is already taken</span>
                                </div>
                            </template>
                        </div>
                        @error('subdomain')<p class="err-msg fade-up">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="email">Email address</label>
                        <input id="email" name="email" type="email" autocomplete="email"
                               value="{{ old('email') }}" placeholder="you@yourchurch.org"
                               x-model="email" @blur="emailTouched = true"
                               :class="emailError ? 'field-error' : ''"
                               class="field-input w-full px-4 py-3 rounded-xl text-sm" required>
                        <p x-show="emailError" x-cloak x-text="emailError" class="err-msg fade-up"></p>
                        @error('email')<p x-show="!emailError" class="err-msg fade-up">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="password">Password</label>
                        <div class="relative">
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'"
                                   autocomplete="new-password" placeholder="Minimum 8 characters"
                                   x-model="password" class="field-input w-full px-4 py-3 pr-11 rounded-xl text-sm" required>
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-0.5">
                                <svg x-show="!showPassword" class="w-[17px] h-[17px]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <svg x-show="showPassword" x-cloak class="w-[17px] h-[17px]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                        </div>
                        <div x-show="password.length > 0" x-cloak class="mt-2.5 fade-up">
                            <div class="flex gap-1.5 mb-1.5">
                                <div class="strength-seg" :style="'background:' + (passwordStrength >= 1 ? strengthColor : '#e2e8f0')"></div>
                                <div class="strength-seg" :style="'background:' + (passwordStrength >= 2 ? strengthColor : '#e2e8f0')"></div>
                                <div class="strength-seg" :style="'background:' + (passwordStrength >= 3 ? strengthColor : '#e2e8f0')"></div>
                                <div class="strength-seg" :style="'background:' + (passwordStrength >= 4 ? strengthColor : '#e2e8f0')"></div>
                            </div>
                            <span style="font-size:12.5px;font-weight:500;transition:color 0.3s" :style="'color:' + strengthTextColor" x-text="strengthLabel"></span>
                        </div>
                        @error('password')<p class="err-msg fade-up">{{ $message }}</p>@enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="password_confirmation">Confirm password</label>
                        <input id="password_confirmation" name="password_confirmation"
                               :type="showPassword ? 'text' : 'password'" autocomplete="new-password"
                               placeholder="Repeat your password" x-model="confirmPassword"
                               :class="passwordMatchError ? 'field-error' : ''"
                               class="field-input w-full px-4 py-3 rounded-xl text-sm" required>
                        <p x-show="passwordMatchError" x-cloak x-text="passwordMatchError" class="err-msg fade-up"></p>
                        <p x-show="passwordMatchOk" x-cloak class="ok-msg fade-up">✓ Passwords match</p>
                    </div>

                </div>{{-- /step 1 --}}

                {{-- ════════════════════════════
                     STEP 2 — Billing address
                ════════════════════════════ --}}
                <div x-show="currentStep === 2" x-cloak class="step-enter space-y-5">

                    {{-- Cardholder name --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="cardholder_name">Name on card</label>
                        <input id="cardholder_name" name="cardholder_name" type="text" autocomplete="cc-name"
                               placeholder="Full name as it appears on your card"
                               x-model="cardholderName"
                               :class="step2Touched && !cardholderName.trim() ? 'field-error' : ''"
                               class="field-input w-full px-4 py-3 rounded-xl text-sm">
                        <p x-show="step2Touched && !cardholderName.trim()" x-cloak class="err-msg fade-up">Name on card is required</p>
                        @error('cardholder_name')<p class="err-msg fade-up">{{ $message }}</p>@enderror
                    </div>

                    {{-- Address line 1 --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="billing_line1">Street address</label>
                        <input id="billing_line1" name="billing_line1" type="text" autocomplete="billing address-line1"
                               placeholder="123 Main Street"
                               x-model="billingLine1"
                               :class="step2Touched && !billingLine1.trim() ? 'field-error' : ''"
                               class="field-input w-full px-4 py-3 rounded-xl text-sm">
                        <p x-show="step2Touched && !billingLine1.trim()" x-cloak class="err-msg fade-up">Street address is required</p>
                        @error('billing_line1')<p class="err-msg fade-up">{{ $message }}</p>@enderror
                    </div>

                    {{-- Address line 2 --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="billing_line2">
                            Apartment, suite, etc. <span class="text-slate-400 font-normal">(optional)</span>
                        </label>
                        <input id="billing_line2" name="billing_line2" type="text" autocomplete="billing address-line2"
                               placeholder="Unit 4B"
                               x-model="billingLine2"
                               class="field-input w-full px-4 py-3 rounded-xl text-sm">
                    </div>

                    {{-- City + ZIP (2 columns) --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="billing_city">City</label>
                            <input id="billing_city" name="billing_city" type="text" autocomplete="billing address-level2"
                                   placeholder="Manila"
                                   x-model="billingCity"
                                   :class="step2Touched && !billingCity.trim() ? 'field-error' : ''"
                                   class="field-input w-full px-4 py-3 rounded-xl text-sm">
                            <p x-show="step2Touched && !billingCity.trim()" x-cloak class="err-msg fade-up">Required</p>
                            @error('billing_city')<p class="err-msg fade-up">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="billing_zip">ZIP / Postal code</label>
                            <input id="billing_zip" name="billing_zip" type="text" autocomplete="billing postal-code"
                                   placeholder="1000"
                                   x-model="billingZip"
                                   :class="step2Touched && !billingZip.trim() ? 'field-error' : ''"
                                   class="field-input w-full px-4 py-3 rounded-xl text-sm">
                            <p x-show="step2Touched && !billingZip.trim()" x-cloak class="err-msg fade-up">Required</p>
                            @error('billing_zip')<p class="err-msg fade-up">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- State + Country (2 columns) --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="billing_state">
                                State / Province <span class="text-slate-400 font-normal">(optional)</span>
                            </label>
                            <input id="billing_state" name="billing_state" type="text" autocomplete="billing address-level1"
                                   placeholder="Metro Manila"
                                   x-model="billingState"
                                   class="field-input w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5" for="billing_country">Country</label>
                            <select id="billing_country" name="billing_country" autocomplete="billing country"
                                    x-model="billingCountry"
                                    class="field-input w-full px-4 py-3 rounded-xl text-sm cursor-pointer">
                                <optgroup label="Common">
                                    <option value="PH">Philippines</option>
                                    <option value="US">United States</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="AU">Australia</option>
                                    <option value="CA">Canada</option>
                                    <option value="SG">Singapore</option>
                                    <option value="MY">Malaysia</option>
                                    <option value="ID">Indonesia</option>
                                    <option value="TH">Thailand</option>
                                    <option value="VN">Vietnam</option>
                                    <option value="NG">Nigeria</option>
                                    <option value="KE">Kenya</option>
                                    <option value="ZA">South Africa</option>
                                    <option value="IN">India</option>
                                </optgroup>
                                <optgroup label="All Countries">
                                    <option value="AF">Afghanistan</option>
                                    <option value="AL">Albania</option>
                                    <option value="DZ">Algeria</option>
                                    <option value="AO">Angola</option>
                                    <option value="AR">Argentina</option>
                                    <option value="AT">Austria</option>
                                    <option value="BD">Bangladesh</option>
                                    <option value="BE">Belgium</option>
                                    <option value="BR">Brazil</option>
                                    <option value="CM">Cameroon</option>
                                    <option value="CL">Chile</option>
                                    <option value="CN">China</option>
                                    <option value="CO">Colombia</option>
                                    <option value="CD">Congo (DRC)</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CZ">Czech Republic</option>
                                    <option value="DK">Denmark</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="EG">Egypt</option>
                                    <option value="ET">Ethiopia</option>
                                    <option value="FI">Finland</option>
                                    <option value="FR">France</option>
                                    <option value="GH">Ghana</option>
                                    <option value="DE">Germany</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="HU">Hungary</option>
                                    <option value="IL">Israel</option>
                                    <option value="IT">Italy</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="JP">Japan</option>
                                    <option value="JO">Jordan</option>
                                    <option value="KR">South Korea</option>
                                    <option value="KW">Kuwait</option>
                                    <option value="LB">Lebanon</option>
                                    <option value="MX">Mexico</option>
                                    <option value="MA">Morocco</option>
                                    <option value="MM">Myanmar</option>
                                    <option value="NL">Netherlands</option>
                                    <option value="NZ">New Zealand</option>
                                    <option value="NO">Norway</option>
                                    <option value="PK">Pakistan</option>
                                    <option value="PE">Peru</option>
                                    <option value="PL">Poland</option>
                                    <option value="PT">Portugal</option>
                                    <option value="QA">Qatar</option>
                                    <option value="RO">Romania</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="SA">Saudi Arabia</option>
                                    <option value="SL">Sierra Leone</option>
                                    <option value="ES">Spain</option>
                                    <option value="LK">Sri Lanka</option>
                                    <option value="SE">Sweden</option>
                                    <option value="CH">Switzerland</option>
                                    <option value="TW">Taiwan</option>
                                    <option value="TZ">Tanzania</option>
                                    <option value="TR">Turkey</option>
                                    <option value="UG">Uganda</option>
                                    <option value="UA">Ukraine</option>
                                    <option value="AE">United Arab Emirates</option>
                                    <option value="UY">Uruguay</option>
                                    <option value="VE">Venezuela</option>
                                    <option value="ZM">Zambia</option>
                                    <option value="ZW">Zimbabwe</option>
                                </optgroup>
                            </select>
                            @error('billing_country')<p class="err-msg fade-up">{{ $message }}</p>@enderror
                        </div>
                    </div>

                </div>{{-- /step 2 --}}

                {{-- ════════════════════════════
                     STEP 3 — Card / Payment
                ════════════════════════════ --}}
                <div x-show="currentStep === 3" x-cloak class="step-enter space-y-5">

                    {{-- Plan summary --}}
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-semibold text-slate-800">{{ $plan->name }}</p>
                            @if($plan->effectiveTrialDays() > 0)
                            <p class="text-[11.5px] text-slate-500 mt-0.5">
                                {{ $plan->effectiveTrialDays() }}-day free trial, then
                                @if($plan->isFree()) free forever
                                @else ${{ number_format((float)$plan->price_monthly, 0) }}/mo
                                @endif
                            </p>
                            @else
                            <p class="text-[11.5px] text-slate-500 mt-0.5">Billed monthly, cancel anytime</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-[18px] font-bold text-slate-900">
                                @if($plan->effectiveTrialDays() > 0) $0 today
                                @else ${{ number_format((float)$plan->price_monthly, 0) }}
                                @endif
                            </p>
                            @if($plan->effectiveTrialDays() > 0)
                            <p class="text-[10.5px] text-slate-400">due today</p>
                            @endif
                        </div>
                    </div>

                    {{-- Card element --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Card details</label>
                        <div id="card-element" class="stripe-field"
                             :class="{'focused': cardFocused, 'has-error': cardError}"></div>
                        <p x-show="cardError" x-cloak x-text="cardError" class="err-msg fade-up"></p>
                        @error('payment_method_id')<p class="err-msg fade-up">{{ $message }}</p>@enderror

                        {{-- Stripe loading spinner --}}
                        <div x-show="stripeLoading" x-cloak class="flex items-center gap-2 mt-2">
                            <svg class="w-4 h-4 text-slate-400 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                            </svg>
                            <span class="text-[12px] text-slate-400">Loading secure payment form…</span>
                        </div>
                    </div>

                    {{-- Trust badges --}}
                    <div class="flex items-center justify-center gap-4 py-3 border-y border-slate-200/70">
                        <span class="flex items-center gap-1.5" style="font-size:11px;color:#94a3b8;">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                            256-bit SSL
                        </span>
                        <span class="w-px h-3 bg-slate-200 flex-shrink-0"></span>
                        <span class="flex items-center gap-1.5" style="font-size:11px;color:#94a3b8;">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944z" clip-rule="evenodd"/></svg>
                            Powered by Stripe
                        </span>
                        <span class="w-px h-3 bg-slate-200 flex-shrink-0"></span>
                        <span class="flex items-center gap-1.5" style="font-size:11px;color:#94a3b8;">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            Cancel anytime
                        </span>
                    </div>

                </div>{{-- /step 3 --}}

                {{-- ════════════════════════════
                     Navigation buttons
                ════════════════════════════ --}}
                <div class="mt-6 flex gap-3">

                    {{-- Back button --}}
                    <button type="button"
                            x-show="currentStep > 1"
                            x-cloak
                            @click="prevStep"
                            :disabled="loading"
                            class="flex items-center gap-1.5 px-5 py-[13px] rounded-xl border-1.5 border-slate-200 bg-white text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-colors flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                        </svg>
                        Back
                    </button>

                    {{-- Next — steps 1 & 2 --}}
                    <button type="button"
                            x-show="currentStep < 3"
                            @click="nextStep"
                            :disabled="loading"
                            :class="{
                                'opacity-50 cursor-not-allowed': (currentStep === 1 && !step1Valid) || (currentStep === 2 && !step2Valid),
                                'btn-primary': true
                            }"
                            class="flex-1 flex items-center justify-center gap-2 py-[13px] px-6 rounded-xl bg-indigo-600 text-white font-semibold text-sm shadow-md shadow-indigo-600/20">
                        <span x-text="currentStep === 1 ? 'Continue to Billing' : 'Continue to Payment'"></span>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>

                    {{-- Create Account — step 3 --}}
                    <button type="button"
                            x-show="currentStep === 3"
                            x-cloak
                            @click="handleFinalSubmit"
                            :disabled="loading || !cardComplete"
                            :class="{
                                'opacity-50 cursor-not-allowed': !cardComplete || loading,
                                'btn-primary': true
                            }"
                            class="flex-1 flex items-center justify-center gap-2 py-[13px] px-6 rounded-xl bg-indigo-600 text-white font-semibold text-sm shadow-md shadow-indigo-600/20">
                        <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                        <span x-text="loading ? 'Creating your account…' : '{{ $plan->effectiveTrialDays() > 0 ? 'Start Free Trial' : 'Create Account' }}'"></span>
                    </button>

                </div>

                {{-- Legal --}}
                <p x-show="currentStep === 3" x-cloak class="mt-3 text-center leading-relaxed" style="font-size:11px;color:#94a3b8;">
                    By signing up you agree to our
                    <a href="#" class="text-indigo-500 hover:underline">Terms of Service</a> and
                    <a href="#" class="text-indigo-500 hover:underline">Privacy Policy</a>.
                    @if($plan->effectiveTrialDays() > 0)
                    Your card will not be charged until your {{ $plan->effectiveTrialDays() }}-day trial ends.
                    @endif
                </p>

                <div class="mt-3 text-center">
                    <span style="font-size:12px;color:#94a3b8;">Need help?&nbsp;</span>
                    <a href="#" class="font-medium transition-colors hover:text-indigo-600" style="font-size:12px;color:#6366f1;">Chat with us</a>
                </div>

            </form>

            <div class="mt-7 pt-5 border-t border-slate-200 text-center">
                <p class="text-sm text-slate-500">
                    Already have an account?
                    <a href="{{ route('superadmin.login') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Sign in instead</a>
                </p>
            </div>

            </div>{{-- /x-data --}}
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('registrationForm', () => ({

        /* ── Steps ── */
        currentStep: {{ $errors->any() ? 1 : 1 }},

        /* ── Step 1 ── */
        orgName:         @json(old('org_name', '')),
        subdomain:       @json(old('subdomain', '')),
        email:           @json(old('email', '')),
        password:        '',
        confirmPassword: '',
        showPassword:    false,
        orgNameTouched:  false,
        emailTouched:    false,
        subStatus:  null,
        _prevAutoSubdomain: @json(old('subdomain', '')),
        _subTimer:  null,

        /* ── Step 2 ── */
        cardholderName: @json(old('cardholder_name', '')),
        billingLine1:   @json(old('billing_line1', '')),
        billingLine2:   @json(old('billing_line2', '')),
        billingCity:    @json(old('billing_city', '')),
        billingState:   @json(old('billing_state', '')),
        billingZip:     @json(old('billing_zip', '')),
        billingCountry: @json(old('billing_country', 'PH')),
        step2Touched:   false,

        /* ── Step 3 / Stripe ── */
        stripeInstance: null,
        cardElement:    null,
        cardComplete:   false,
        cardError:      '',
        cardFocused:    false,
        stripeLoading:  false,
        setupClientSecret: null,
        paymentMethodId: '',

        /* ── Global ── */
        loading: false,

        /* ── Init ── */
        init() {
            if (this.email)     this.emailTouched = true;
            if (this.orgName)   this.orgNameTouched = true;
            if (this.subdomain.length >= 2) this.checkSubdomain();
        },

        /* ════════════════════
           Step titles
        ════════════════════ */
        get stepTitle() {
            return ['Create your account', 'Billing address', 'Secure your account'][this.currentStep - 1];
        },
        get stepSubtitle() {
            const trialDays = {{ $plan->effectiveTrialDays() }};
            if (this.currentStep === 1) return 'Set up your organization on FaithStack.';
            if (this.currentStep === 2) return 'Enter your billing address. This is linked to your card.';
            if (this.currentStep === 3) {
                if (trialDays > 0) return `Your card will not be charged for ${trialDays} days. Cancel anytime before your trial ends.`;
                return 'Add your card to complete registration. Payments are secured by Stripe.';
            }
            return '';
        },

        /* ════════════════════
           Step validity
        ════════════════════ */
        get step1Valid() {
            return this.orgName.trim().length >= 3
                && this.subdomain.length >= 2
                && this.subStatus === 'available'
                && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)
                && this.password.length >= 8
                && this.password === this.confirmPassword
                && this.confirmPassword.length > 0;
        },
        get step2Valid() {
            return this.cardholderName.trim().length >= 2
                && this.billingLine1.trim().length >= 3
                && this.billingCity.trim().length >= 2
                && this.billingZip.trim().length >= 2
                && this.billingCountry.length === 2;
        },

        /* ════════════════════
           Password strength
        ════════════════════ */
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
        get strengthLabel()    { return ['','Weak','Fair','Strong','Excellent'][this.passwordStrength] || ''; },
        get strengthColor()    { return ['','#f87171','#fb923c','#3b82f6','#10b981'][this.passwordStrength] || '#e2e8f0'; },
        get strengthTextColor(){ return ['','#dc2626','#d97706','#2563eb','#059669'][this.passwordStrength] || '#94a3b8'; },

        /* ════════════════════
           Validation helpers
        ════════════════════ */
        get orgNameError() {
            if (!this.orgNameTouched) return '';
            if (!this.orgName.trim()) return 'Organization name is required';
            if (this.orgName.trim().length < 3) return 'Must be at least 3 characters';
            return '';
        },
        get emailError() {
            if (!this.emailTouched) return '';
            if (!this.email) return 'Email address is required';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) return 'Please enter a valid email address';
            return '';
        },
        get passwordMatchError() {
            if (!this.confirmPassword) return '';
            if (this.password !== this.confirmPassword) return 'Passwords do not match';
            return '';
        },
        get passwordMatchOk() {
            return this.confirmPassword.length > 0 && this.password === this.confirmPassword;
        },

        /* ════════════════════
           Navigation
        ════════════════════ */
        async nextStep() {
            if (this.currentStep === 1) {
                this.orgNameTouched = true;
                this.emailTouched   = true;
                if (!this.step1Valid) return;
            }
            if (this.currentStep === 2) {
                this.step2Touched = true;
                if (!this.step2Valid) return;
            }
            this.currentStep++;
            if (this.currentStep === 3) {
                await this.$nextTick();
                await this.initStripe();
            }
        },

        prevStep() {
            if (this.currentStep > 1) this.currentStep--;
        },

        /* ════════════════════
           Stripe init
        ════════════════════ */
        async initStripe() {
            if (this.cardElement) return; // already mounted
            this.stripeLoading = true;

            try {
                const resp = await fetch('/register/setup-intent');
                const data = await resp.json();
                if (data.error) throw new Error(data.error);
                this.setupClientSecret = data.client_secret;
            } catch (e) {
                this.cardError = 'Could not load payment form. Please go back and try again.';
                this.stripeLoading = false;
                return;
            }

            this.stripeInstance = Stripe(window.stripeKey);
            const elements = this.stripeInstance.elements();

            this.cardElement = elements.create('card', {
                hidePostalCode: true,
                style: {
                    base: {
                        fontFamily: "'Inter', ui-sans-serif, system-ui, sans-serif",
                        fontSize: '14px',
                        fontWeight: '400',
                        color: '#0f172a',
                        '::placeholder': { color: '#cbd5e1' },
                        iconColor: '#6366f1',
                    },
                    invalid: { color: '#dc2626', iconColor: '#dc2626' },
                },
            });

            this.cardElement.mount('#card-element');

            this.cardElement.on('focus',  () => { this.cardFocused = true; });
            this.cardElement.on('blur',   () => { this.cardFocused = false; });
            this.cardElement.on('change', (e) => {
                this.cardError    = e.error ? e.error.message : '';
                this.cardComplete = e.complete;
            });

            this.stripeLoading = false;
        },

        /* ════════════════════
           Final submit
        ════════════════════ */
        async handleFinalSubmit() {
            if (!this.cardComplete || this.loading) return;
            this.loading   = true;
            this.cardError = '';

            try {
                const { setupIntent, error } = await this.stripeInstance.confirmCardSetup(
                    this.setupClientSecret,
                    {
                        payment_method: {
                            card: this.cardElement,
                            billing_details: {
                                name:  this.cardholderName,
                                email: this.email,
                                address: {
                                    line1:       this.billingLine1,
                                    line2:       this.billingLine2 || undefined,
                                    city:        this.billingCity,
                                    state:       this.billingState  || undefined,
                                    postal_code: this.billingZip,
                                    country:     this.billingCountry,
                                },
                            },
                        },
                    }
                );

                if (error) {
                    this.cardError = error.message;
                    this.loading   = false;
                    return;
                }

                this.paymentMethodId = setupIntent.payment_method;
                this.$refs.regForm.submit();

            } catch (err) {
                this.cardError = 'An unexpected error occurred. Please try again.';
                this.loading   = false;
            }
        },

        /* ════════════════════
           Subdomain helpers
        ════════════════════ */
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
                        this.subStatus = d.available ? 'available' : 'taken';
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
