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
                <a href="#upgrade"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/25 transition-all duration-200 hover:-translate-y-0.5">
                    <span>⚡</span> Upgrade Now
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
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
                {{-- Upgrade CTA — links to public register as placeholder; replace with Stripe checkout --}}
                <a href="{{ url('/register') }}?plan={{ $plan->slug }}&existing_tenant={{ $tenant->subdomain }}"
                   class="block text-center py-2.5 px-4 rounded-xl text-sm font-semibold mb-5 transition-all duration-200 hover:-translate-y-0.5
                          {{ $isFeatured
                              ? 'bg-white text-indigo-700 hover:bg-indigo-50 shadow-lg'
                              : 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/20' }}">
                    {{ $plan->cta_label ?? 'Upgrade to ' . $plan->name }}
                </a>
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

{{-- ── Billing note ── --}}
<div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-3">
    <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="text-blue-700 text-sm">
        <strong>Stripe billing coming soon.</strong>
        Upgrade links currently route to the registration flow.
        To manually upgrade your plan or for billing questions, email
        <a href="mailto:hello@faithstack.com" class="font-semibold underline hover:text-blue-900">hello@faithstack.com</a>.
    </p>
</div>

@endsection
