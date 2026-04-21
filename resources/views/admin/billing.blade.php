@extends('admin.layouts.app')

@section('title', 'Plan & Billing')
@section('heading', 'Plan & Billing')

@section('content')
@php
    $isOnTrial  = $tenant->subscription_status === 'trial';
    $isActive   = $tenant->subscription_status === 'active';
    $isExpired  = $tenant->subscription_status === 'expired';
    $currentPlan = $tenant->plan;
    $urgent = $isOnTrial && $trialDaysLeft !== null && $trialDaysLeft <= 6;
@endphp

{{-- Stripe.js --}}
<script src="https://js.stripe.com/v3/"></script>

<style>
@keyframes btn-shimmer { to { background-position: 200% center; } }
.btn-shimmer { background: linear-gradient(90deg,#4338ca,#6d28d9,#a78bfa,#6d28d9,#4338ca); background-size:250% auto; animation:btn-shimmer 1.8s linear infinite; }
@keyframes modal-shake { 0%,100%{transform:translateX(0)} 15%{transform:translateX(-10px)} 30%{transform:translateX(10px)} 45%{transform:translateX(-7px)} 60%{transform:translateX(7px)} 75%{transform:translateX(-4px)} 90%{transform:translateX(4px)} }
.modal-shake { animation:modal-shake .45s ease-in-out; }
@keyframes stroke-draw { 100%{stroke-dashoffset:0} }
@keyframes pop-scale   { 0%,100%{transform:none} 50%{transform:scale3d(1.08,1.08,1)} }
.cm-circle { stroke-dasharray:166; stroke-dashoffset:166; animation:stroke-draw .55s cubic-bezier(.65,0,.45,1) .1s forwards; }
.cm-check  { stroke-dasharray:48;  stroke-dashoffset:48;  animation:stroke-draw .3s  cubic-bezier(.65,0,.45,1) .7s  forwards; }
.cm-svg    { animation:pop-scale .3s ease-in-out 1s both; }
.stripe-wrap:focus-within { box-shadow:0 0 0 3px rgba(99,102,241,.15); border-color:#818cf8 !important; }
@keyframes pp-dot { 0%,80%,100%{transform:scale(0);opacity:.3} 40%{transform:scale(1);opacity:1} }
.pp-dot:nth-child(1){animation:pp-dot 1.3s ease-in-out 0s   infinite}
.pp-dot:nth-child(2){animation:pp-dot 1.3s ease-in-out .2s  infinite}
.pp-dot:nth-child(3){animation:pp-dot 1.3s ease-in-out .4s  infinite}
</style>

{{-- ── Premium checkout modal ── --}}
{{--
    hasSavedCard: true  → user registered with a card → go straight to 'confirm' step
    hasSavedCard: false → no saved card → show 'select' step (card or PayPal)
--}}
<div x-data="checkoutModal()" x-cloak
     @open-checkout.window="openFor($event.detail.slug, $event.detail.name, $event.detail.price)">

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"></div>

    {{-- Modal panel --}}
    <div x-show="open"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="close()"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl pointer-events-auto overflow-hidden" @click.stop>

            {{-- ── Header ── --}}
            <div class="bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-700 p-6 relative overflow-hidden">
                <div class="absolute inset-0 opacity-[0.07] pointer-events-none" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:22px 22px"></div>
                <div class="relative flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        {{-- Back breadcrumb (card step only) --}}
                        <div class="flex items-center gap-1.5 mb-2" x-show="step === 'card'">
                            <button @click="step = 'select'" class="text-indigo-200 hover:text-white text-xs flex items-center gap-1 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                                Back
                            </button>
                            <span class="text-indigo-300 text-xs">/ Enter Card</span>
                        </div>

                        {{-- confirm: value-prop headline --}}
                        <p class="text-indigo-300 text-[10px] font-bold uppercase tracking-[0.15em] mb-1.5"
                           x-show="step === 'confirm'">Ready to upgrade</p>
                        <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest mb-1"
                           x-show="step !== 'confirm' && step !== 'success'">Upgrading to</p>

                        <h2 class="text-white font-bold leading-tight"
                            :class="step === 'confirm' ? 'text-2xl' : 'text-xl'"
                            x-text="step === 'success' ? 'You\'re all set!' : (step === 'confirm' ? 'Unlock Full Power' : selectedPlanName)"></h2>

                        {{-- confirm sub-headline --}}
                        <p class="text-indigo-200 text-sm mt-1.5 font-medium" x-show="step === 'confirm'">
                            <span x-text="selectedPlanName"></span> plan ·
                            <span class="text-white font-bold">$<span x-text="selectedPlanPrice"></span></span><span class="text-indigo-300 font-normal">/mo</span>
                        </p>
                        {{-- other steps sub-headline --}}
                        <p class="text-indigo-200 text-sm mt-1" x-show="step !== 'confirm' && step !== 'success'">
                            $<span x-text="selectedPlanPrice"></span>/month — billed monthly
                        </p>
                    </div>
                    <button @click="close()" :disabled="loading"
                            class="text-white/60 hover:text-white ml-4 flex-shrink-0 mt-0.5 disabled:opacity-40">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ════════════════════════════════════════
                 STEP: confirm (has saved card — primary path)
            ════════════════════════════════════════ --}}
            <div x-show="step === 'confirm'" class="p-6">

                {{-- Plan features list --}}
                <div class="mb-5" x-show="selectedPlanFeatures.length > 0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.12em] mb-3">What you're unlocking</p>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2.5">
                        <template x-for="feat in selectedPlanFeatures" :key="feat">
                            <div class="flex items-center gap-2">
                                <div class="w-4.5 h-4.5 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0" style="width:18px;height:18px">
                                    <svg style="width:10px;height:10px" fill="currentColor" class="text-emerald-600" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-slate-700 text-xs font-medium leading-tight" x-text="feat"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="border-t border-slate-100 mb-5" x-show="selectedPlanFeatures.length > 0"></div>

                {{-- Payment method — "selected" state --}}
                <div class="mb-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.12em] mb-2">Payment Method</p>
                    <div class="flex items-center gap-3 p-3 bg-indigo-50 border-2 border-indigo-200 rounded-xl" style="box-shadow:0 0 0 3px rgba(99,102,241,.08)">
                        {{-- Selected indicator --}}
                        <div class="w-5 h-5 rounded-full bg-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm shadow-indigo-600/40">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        {{-- Card icon --}}
                        <div class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-slate-800 font-semibold text-sm">{{ $cardBrand ?? 'Card' }} ···· {{ $cardLast4 ?? '••••' }}</p>
                            <p class="text-slate-400 text-xs mt-0.5">Saved · Ready to use</p>
                        </div>
                        <button @click="step = 'select'"
                                class="text-xs text-indigo-500 hover:text-indigo-700 font-semibold flex-shrink-0 transition-colors px-2.5 py-1 hover:bg-indigo-100 rounded-lg">
                            Edit
                        </button>
                    </div>
                </div>

                {{-- Error --}}
                <div x-show="payError" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-2">
                    <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p x-text="payError" class="text-red-700 text-sm"></p>
                </div>

                {{-- Trust row --}}
                <div class="flex items-center justify-center gap-3 mb-4">
                    <span class="flex items-center gap-1 text-[11px] text-slate-400">
                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        Secure checkout
                    </span>
                    <span class="text-slate-200 select-none">|</span>
                    <span class="flex items-center gap-1 text-[11px] text-slate-400">
                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Cancel anytime
                    </span>
                    <span class="text-slate-200 select-none">|</span>
                    <span class="flex items-center gap-1 text-[11px] text-slate-400">
                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33"/>
                        </svg>
                        No hidden fees
                    </span>
                </div>

                {{-- Shimmer CTA --}}
                <button @click="confirmWithSavedCard()"
                        :disabled="loading"
                        :class="loading ? 'opacity-70 cursor-not-allowed' : 'hover:-translate-y-0.5'"
                        class="w-full flex items-center justify-center gap-2.5 py-3.5 px-6 rounded-xl font-bold text-sm text-white transition-all duration-200 btn-shimmer shadow-lg shadow-indigo-600/30 hover:shadow-xl hover:shadow-indigo-600/40">
                    <svg x-show="loading" class="w-4 h-4 animate-spin flex-shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <svg x-show="!loading" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="loading ? 'Processing…' : 'Confirm Upgrade — $' + selectedPlanPrice + '/mo'"></span>
                </button>

                <p class="mt-3 text-center text-xs text-slate-400">
                    Charged $<span x-text="selectedPlanPrice"></span> today · Cancel anytime · No long-term commitment
                </p>
            </div>

            {{-- ════════════════════════════════════════
                 STEP: select payment method (no saved card)
            ════════════════════════════════════════ --}}
            <div x-show="step === 'select'" class="p-6">
                <p class="text-slate-700 font-semibold text-sm mb-4">Choose your payment method</p>
                <div class="space-y-3">

                    {{-- Card --}}
                    <button @click="goToCard()"
                            class="w-full flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 hover:border-indigo-400 hover:bg-indigo-50/40 transition-all duration-200 group text-left">
                        <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-slate-800 font-semibold text-sm">Pay with Card</p>
                            <p class="text-slate-500 text-xs mt-0.5">Visa, Mastercard, Amex — powered by Stripe</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>

                    {{-- PayPal --}}
                    <form method="POST" action="{{ route('admin.billing.upgrade') }}" @submit="loading = true">
                        @csrf
                        <input type="hidden" name="plan_slug" :value="selectedPlanSlug">
                        <input type="hidden" name="provider" value="paypal">
                        <button type="submit"
                                class="w-full flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 hover:border-blue-400 hover:bg-blue-50/40 transition-all duration-200 group text-left"
                                :disabled="loading" :class="loading ? 'opacity-60 cursor-not-allowed' : ''">
                            <div class="w-10 h-10 rounded-lg bg-[#003087] flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-bold text-sm tracking-tight">PP</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-slate-800 font-semibold text-sm">Pay with PayPal</p>
                                <p class="text-slate-500 text-xs mt-0.5">Use your PayPal balance or linked account</p>
                            </div>
                            <svg x-show="!loading" class="w-4 h-4 text-slate-400 group-hover:text-blue-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                            <svg x-show="loading" class="w-4 h-4 text-blue-500 animate-spin flex-shrink-0" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </button>
                    </form>
                </div>
                <p class="mt-4 text-center text-xs text-slate-400">🔒 Secure checkout — cancel anytime — no hidden fees</p>
            </div>

            {{-- ════════════════════════════════════════
                 STEP: card details (new card entry)
            ════════════════════════════════════════ --}}
            <div x-show="step === 'card'" class="p-6" x-ref="cardStep">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Cardholder Name</label>
                        <input type="text" x-model="cardName" placeholder="Jane Smith"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none text-sm text-slate-800 transition-all"
                               :class="cardNameError ? 'border-red-400 bg-red-50' : ''">
                        <p x-show="cardNameError" x-text="cardNameError" class="text-red-600 text-xs mt-1 font-medium"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Email</label>
                        <input type="email" x-model="cardEmail" placeholder="you@example.com"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none text-sm text-slate-800 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Card Details</label>
                        <div id="stripe-card-element"
                             class="px-3.5 py-3 rounded-xl border border-slate-200 bg-white transition-all stripe-wrap"
                             :class="cardElementError ? 'border-red-400 bg-red-50' : ''"></div>
                        <p x-show="cardElementError" x-text="cardElementError" class="text-red-600 text-xs mt-1 font-medium"></p>
                    </div>
                    <div x-show="payError" class="p-3 bg-red-50 border border-red-200 rounded-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p x-text="payError" class="text-red-700 text-sm"></p>
                    </div>
                    <button @click="submitCard()"
                            :disabled="loading"
                            :class="loading ? 'opacity-70 cursor-not-allowed' : 'hover:bg-indigo-500 hover:-translate-y-0.5'"
                            class="w-full flex items-center justify-center gap-2.5 py-3.5 px-6 bg-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-600/25 transition-all duration-200">
                        <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        <span x-text="loading ? 'Processing…' : 'Pay $' + selectedPlanPrice + '/month'"></span>
                    </button>
                    <p class="text-center text-xs text-slate-400">Charged $<span x-text="selectedPlanPrice"></span> today. Cancel anytime.</p>
                </div>
            </div>

            {{-- ════════════════════════════════════════
                 STEP: success
            ════════════════════════════════════════ --}}
            <div x-show="step === 'success'" class="p-8 text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-slate-900 font-bold text-lg mb-1" x-text="successMessage"></h3>
                <p class="text-slate-500 text-sm mb-6">A confirmation email is on its way. Redirecting you now…</p>
                <div class="w-8 h-1 bg-indigo-600 rounded-full mx-auto animate-pulse"></div>
            </div>

        </div>
    </div>
</div>

@php
    $plansForJs = $plans->map(function ($p) {
        return ['slug' => $p->slug, 'features' => array_slice($p->features ?? [], 0, 4)];
    })->values()->toArray();
@endphp

<script>
function checkoutModal() {
    return {
        open: false,
        // 'confirm' = has saved card (fast path)
        // 'select'  = no saved card (choose stripe or paypal)
        // 'card'    = enter new card via Stripe Elements
        // 'success' = done
        step: 'confirm',
        loading: false,

        hasSavedCard: {{ $hasCard ? 'true' : 'false' }},

        // Plan feature data for the confirm modal
        _plans: @json($plansForJs),

        get selectedPlanFeatures() {
            const p = this._plans.find(p => p.slug === this.selectedPlanSlug);
            return p ? p.features : [];
        },

        selectedPlanSlug:  '',
        selectedPlanName:  '',
        selectedPlanPrice: '',

        cardName:         '',
        cardEmail:        '{{ addslashes($tenant->email ?? '') }}',
        cardNameError:    '',
        cardElementError: '',
        payError:         '',
        successMessage:   '',

        _stripe:      null,
        _cardElement: null,

        openFor(slug, name, price) {
            this.selectedPlanSlug  = slug;
            this.selectedPlanName  = name;
            this.selectedPlanPrice = price;
            // Smart routing: saved card → one-click confirm; no card → method select
            this.step    = this.hasSavedCard ? 'confirm' : 'select';
            this.loading = false;
            this.payError = '';
            this.cardNameError = '';
            this.cardElementError = '';
            this.open = true;
        },

        close() {
            if (this.loading) return;
            this.open = false;
        },

        goToCard() {
            this.step = 'card';
            this.$nextTick(() => this._mountCard());
        },

        _mountCard() {
            if (this._stripe) return;
            this._stripe = Stripe('{{ config('services.stripe.key') }}');
            const elements = this._stripe.elements();
            this._cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '14px',
                        color: '#1e293b',
                        fontFamily: 'Inter, Helvetica Neue, Arial, sans-serif',
                        '::placeholder': { color: '#94a3b8' },
                    },
                    invalid: { color: '#dc2626' },
                },
                hidePostalCode: true,
            });
            this._cardElement.mount('#stripe-card-element');
            this._cardElement.on('change', (e) => {
                this.cardElementError = e.error ? e.error.message : '';
            });
        },

        // ── Fast-path: upgrade using saved card (no card entry needed) ──────────
        async confirmWithSavedCard() {
            this.payError = '';
            this.loading  = true;

            try {
                const res = await fetch('{{ route('admin.billing.stripe.upgrade-saved') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ plan_slug: this.selectedPlanSlug }),
                });
                const data = await res.json();

                if (!res.ok) {
                    this.payError = data.error || 'Upgrade failed. Please try again.';
                    this.loading  = false;
                    return;
                }

                this.successMessage = data.message;
                this.step    = 'success';
                this.loading = false;
                setTimeout(() => { window.location.href = data.redirect; }, 2500);

            } catch (err) {
                this.payError = 'An unexpected error occurred. Please try again.';
                this.loading  = false;
            }
        },

        // ── Slow-path: new card entry via Stripe Elements ────────────────────────
        async submitCard() {
            this.cardNameError    = '';
            this.cardElementError = '';
            this.payError         = '';

            if (!this.cardName.trim()) {
                this.cardNameError = 'Cardholder name is required';
                return;
            }

            this.loading = true;

            try {
                const intentRes = await fetch('{{ route('admin.billing.stripe.intent') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ plan_slug: this.selectedPlanSlug }),
                });
                const intentData = await intentRes.json();
                if (!intentRes.ok) {
                    this.payError = intentData.error || 'Could not initiate payment.';
                    this.loading  = false;
                    return;
                }

                const { paymentIntent, error } = await this._stripe.confirmCardPayment(
                    intentData.client_secret,
                    {
                        payment_method: {
                            card:            this._cardElement,
                            billing_details: { name: this.cardName, email: this.cardEmail },
                        },
                    }
                );

                if (error) {
                    this.payError = error.message;
                    this.loading  = false;
                    return;
                }

                const confirmRes = await fetch('{{ route('admin.billing.stripe.confirm') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent.id,
                        plan_slug:         this.selectedPlanSlug,
                    }),
                });
                const confirmData = await confirmRes.json();

                if (!confirmRes.ok) {
                    this.payError = confirmData.error || 'Payment succeeded but activation failed. Contact support.';
                    this.loading  = false;
                    return;
                }

                this.successMessage = confirmData.message;
                this.step    = 'success';
                this.loading = false;
                setTimeout(() => { window.location.href = confirmData.redirect; }, 2500);

            } catch (err) {
                this.payError = 'An unexpected error occurred. Please try again.';
                this.loading  = false;
            }
        },
    };
}
</script>

{{-- ── Trial countdown card (only on trial) ── --}}
@if($isOnTrial)
<div class="mb-6 relative overflow-hidden rounded-2xl p-6 {{ $urgent
    ? 'bg-gradient-to-br from-red-600 via-orange-500 to-rose-600'
    : 'bg-gradient-to-br from-amber-500 via-orange-400 to-amber-400' }}
    shadow-xl {{ $urgent ? 'shadow-red-500/25' : 'shadow-amber-400/25' }}">

    {{-- Subtle grid overlay --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:32px_32px] pointer-events-none"></div>

    <div class="relative flex flex-col sm:flex-row sm:items-center gap-5">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1.5">
                <span class="text-2xl">{{ $urgent ? '🔥' : '⚡' }}</span>
                <h2 class="text-white text-xl font-bold">
                    @if($trialDaysLeft !== null && $trialDaysLeft === 0)
                        Your trial expires today
                    @elseif($trialDaysLeft !== null)
                        {{ $trialDaysLeft }} day{{ $trialDaysLeft !== 1 ? 's' : '' }} left on your trial
                    @else
                        Free Trial Active
                    @endif
                </h2>
            </div>
            <p class="text-white/80 text-sm leading-relaxed">
                @if($urgent)
                    Upgrade now to keep your website live and all your content intact. Downgrading after expiry means losing access until renewed.
                @else
                    You're currently on the Free Trial. Upgrade to unlock custom branding, unlimited pages, priority support, and more.
                @endif
            </p>
        </div>

        @if($trialDaysLeft !== null)
        <div class="flex items-center gap-4 sm:flex-col sm:items-end flex-shrink-0">
            {{-- Countdown ring --}}
            <div class="relative w-20 h-20 flex-shrink-0">
                @php
                    $totalDays = optional($currentPlan)->trial_days ?? 7;
                    $pct = $totalDays > 0 ? max(0, min(100, round(($trialDaysLeft / $totalDays) * 100))) : 0;
                    $dashoffset = 100 - $pct;
                @endphp
                <svg class="w-20 h-20 -rotate-90" viewBox="0 0 36 36">
                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2.5"/>
                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="white" stroke-width="2.5"
                            stroke-dasharray="100" stroke-dashoffset="{{ $dashoffset }}"
                            stroke-linecap="round" style="transition: stroke-dashoffset 0.6s ease"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-white text-xl font-bold leading-none">{{ $trialDaysLeft }}</span>
                    <span class="text-white/70 text-[9px] font-semibold uppercase tracking-wide">days</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- ── Current Plan Card ── --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <h3 class="text-base font-bold text-slate-800">Current Plan</h3>
        <span class="px-3 py-1 rounded-full text-xs font-bold
            {{ $isOnTrial ? 'bg-amber-100 text-amber-700 border border-amber-200' :
               ($isActive  ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' :
                             'bg-red-100 text-red-700 border border-red-200') }}">
            {{ $isOnTrial ? '⚡ Trial' : ($isActive ? '✓ Active' : '⚠ Expired') }}
        </span>
    </div>

    <div class="p-6">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 mb-1">
                    <span class="text-2xl">{{ $isActive ? '💎' : ($isOnTrial ? '⚡' : '⚠️') }}</span>
                    <h4 class="text-2xl font-bold text-slate-900">
                        {{ optional($currentPlan)->name ?? 'Free Trial' }}
                    </h4>
                </div>
                <p class="text-slate-500 text-sm mb-4">
                    {{ optional($currentPlan)->description ?? 'Your current active plan.' }}
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Price</p>
                        <p class="text-sm font-semibold text-slate-800">
                            {{ optional($currentPlan)->formattedPrice() ?? 'Free' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Status</p>
                        <p class="text-sm font-semibold text-slate-800">
                            {{ ucfirst($tenant->subscription_status) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">
                            {{ $isOnTrial ? 'Trial Ends' : 'Renews' }}
                        </p>
                        <p class="text-sm font-semibold {{ ($isOnTrial && $urgent) ? 'text-red-600' : 'text-slate-800' }}">
                            {{ $tenant->subscription_ends_at
                                ? $tenant->subscription_ends_at->format('M j, Y')
                                : '—' }}
                        </p>
                    </div>
                </div>
            </div>

            @if($isOnTrial)
            <div class="flex-shrink-0">
                <button @click="$dispatch('open-checkout', { slug: '{{ optional($plans->firstWhere('is_featured', true) ?? $plans->first())->slug }}', name: '{{ optional($plans->firstWhere('is_featured', true) ?? $plans->first())->name }}', price: '{{ number_format((float)(optional($plans->firstWhere('is_featured', true) ?? $plans->first())->price_monthly), 2) }}' })"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/25 transition-all duration-200 hover:-translate-y-0.5">
                    <span>⚡</span> Upgrade Now
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </button>
            </div>
            @endif
        </div>

        {{-- Current features --}}
        @if($currentPlan && count($currentPlan->features ?? []) > 0)
        <div class="mt-6 pt-6 border-t border-slate-100">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">What's included</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6">
                @foreach($currentPlan->features as $feature)
                <div class="flex items-center gap-2 text-sm text-slate-700">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                    </svg>
                    {{ $feature }}
                </div>
                @endforeach
                @foreach(($currentPlan->missing_features ?? []) as $missing)
                <div class="flex items-center gap-2 text-sm text-slate-400">
                    <svg class="w-4 h-4 text-slate-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="line-through decoration-slate-300">{{ $missing }}</span>
                    <span class="text-[10px] text-indigo-500 font-semibold bg-indigo-50 px-1.5 py-0.5 rounded-md ml-1">Upgrade</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ── Choose a Plan / Upgrade section ── --}}
@if($plans->count() > 0)
<div id="upgrade">
    <h3 class="text-base font-bold text-slate-800 mb-4">
        {{ $isOnTrial ? '🚀 Choose a Plan' : '🔄 Switch Plan' }}
    </h3>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($plans as $plan)
        @php
            $isCurrent = optional($currentPlan)->slug === $plan->slug;
            $isFeatured = $plan->is_featured;
        @endphp

        <div class="relative flex flex-col rounded-2xl border overflow-hidden transition-all duration-300
            {{ $isCurrent
                ? 'border-indigo-200 bg-indigo-50/40 ring-2 ring-indigo-500/20'
                : ($isFeatured
                    ? 'border-transparent bg-gradient-to-b from-indigo-600 to-purple-700 shadow-xl shadow-indigo-500/20 scale-[1.02]'
                    : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-md') }}">

            {{-- Featured gradient border glow --}}
            @if($isFeatured && !$isCurrent)
            <div class="absolute -inset-[1.5px] rounded-2xl bg-gradient-to-br from-indigo-400 via-purple-400 to-pink-400 -z-10"></div>
            @endif

            @if(!empty($plan->badge))
            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 z-10">
                <span class="px-3 py-1 bg-gradient-to-r from-amber-400 to-orange-400 text-white text-[10px] font-bold rounded-full shadow-md uppercase tracking-wide whitespace-nowrap">
                    {{ $plan->badge }}
                </span>
            </div>
            @endif

            <div class="p-5 flex-1 {{ $plan->badge ? 'pt-7' : '' }}">

                <p class="text-[10px] font-bold uppercase tracking-widest mb-3
                    {{ $isFeatured && !$isCurrent ? 'text-indigo-200' : 'text-slate-400' }}">
                    {{ $plan->name }}
                </p>

                <div class="flex items-baseline gap-1 mb-1">
                    @if($plan->isFree())
                    <span class="text-4xl font-bold {{ $isFeatured && !$isCurrent ? 'text-white' : 'text-slate-900' }}">Free</span>
                    @else
                    <span class="text-xl font-semibold {{ $isFeatured && !$isCurrent ? 'text-indigo-200' : 'text-slate-400' }}">$</span>
                    <span class="text-4xl font-bold {{ $isFeatured && !$isCurrent ? 'text-white' : 'text-slate-900' }}">{{ number_format((float)$plan->price_monthly, 0) }}</span>
                    <span class="text-sm {{ $isFeatured && !$isCurrent ? 'text-indigo-200' : 'text-slate-400' }}">/mo</span>
                    @endif
                </div>

                <p class="text-sm mb-5 {{ $isFeatured && !$isCurrent ? 'text-indigo-200' : 'text-slate-500' }}">
                    {{ $plan->description }}
                </p>

                {{-- CTA button --}}
                @if($isCurrent)
                <div class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border-2 border-indigo-300 text-indigo-600 text-sm font-bold bg-white mb-5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                    Current Plan
                </div>
                @elseif($plan->isFree())
                <a href="mailto:hello@faithstack.com?subject=Downgrade+request&body=Tenant:+{{ $tenant->subdomain }}"
                   class="block text-center py-2.5 px-4 rounded-xl text-sm font-semibold mb-5 transition-all duration-200
                          border border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50">
                    Contact Support
                </a>
                @else
                <button @click="$dispatch('open-checkout', { slug: '{{ $plan->slug }}', name: '{{ addslashes($plan->name) }}', price: '{{ number_format((float)$plan->price_monthly, 2) }}' })"
                        class="block w-full text-center py-2.5 px-4 rounded-xl text-sm font-semibold mb-5 transition-all duration-200 hover:-translate-y-0.5
                               {{ $isFeatured
                                   ? 'bg-white text-indigo-700 hover:bg-indigo-50 shadow-lg'
                                   : 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/20' }}">
                    {{ $plan->cta_label ?? 'Upgrade to ' . $plan->name }}
                </button>
                @endif

                {{-- Feature list --}}
                <ul class="space-y-2.5">
                    @foreach($plan->features as $feature)
                    <li class="flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 flex-shrink-0 {{ $isFeatured && !$isCurrent ? 'text-indigo-200' : 'text-indigo-500' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                        </svg>
                        <span class="{{ $isFeatured && !$isCurrent ? 'text-white' : 'text-slate-700' }}">{{ $feature }}</span>
                    </li>
                    @endforeach
                    @foreach(($plan->missing_features ?? []) as $missing)
                    <li class="flex items-center gap-2.5 text-sm opacity-40">
                        <svg class="w-4 h-4 flex-shrink-0 {{ $isFeatured && !$isCurrent ? 'text-white/40' : 'text-slate-300' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="{{ $isFeatured && !$isCurrent ? 'text-white' : 'text-slate-500' }}">{{ $missing }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Enterprise callout --}}
    <div class="mt-6 p-5 bg-slate-900 rounded-2xl flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1 min-w-0">
            <p class="text-white font-bold text-sm mb-0.5">Need more? Enterprise plans available.</p>
            <p class="text-slate-400 text-sm">Custom page limits, white-label branding, dedicated support, and SLA agreements.</p>
        </div>
        <a href="mailto:hello@faithstack.com?subject=Enterprise+inquiry&body=Tenant:+{{ $tenant->subdomain }}"
           class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-white/20 text-white text-sm font-semibold hover:bg-white/10 transition-colors whitespace-nowrap">
            Contact Sales
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>
</div>
@endif

{{-- ── Billing support note ── --}}
<div class="mt-6 p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3">
    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="text-slate-500 text-sm">
        Questions about billing? Email us at
        <a href="mailto:hello@faithstack.com" class="text-indigo-600 font-semibold hover:underline">hello@faithstack.com</a>
        — we typically respond within a few hours.
    </p>
</div>

@endsection
