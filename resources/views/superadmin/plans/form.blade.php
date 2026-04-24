@extends('superadmin.layouts.app')

@section('title', $isEditing ? 'Edit Plan' : 'Create Plan')
@section('heading', $isEditing ? 'Edit Plan' : 'Create Plan')

@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label' => 'Platform', 'url' => route('superadmin.dashboard')],
        ['label' => 'Plans',    'url' => route('superadmin.plans.index')],
        ['label' => $isEditing ? 'Edit' : 'Create'],
    ]" />
@endsection

@php
    // Build [{ key, value }] pairs for the Alpine limits array.
    // After a validation failure old('limit_keys') / old('limit_values') are present.
    // On a fresh load we derive them from the plan model.
    if (old('limit_keys')) {
        $limitPairs = collect(old('limit_keys', []))
            ->zip(old('limit_values', []))
            ->filter(fn ($p) => trim((string) ($p[0] ?? '')) !== '')
            ->map(fn ($p) => ['key' => $p[0], 'value' => (string) ($p[1] ?? '')])
            ->values()
            ->toArray();
    } else {
        $limitPairs = collect($plan?->limits ?? [])
            ->map(fn ($v, $k) => ['key' => $k, 'value' => (string) $v])
            ->values()
            ->toArray();
    }
@endphp

@section('content')

<script>
    function planForm() {
        return {
            submitting: false,
            isFeatured: {{ old('is_featured', $plan?->is_featured ?? false) ? 'true' : 'false' }},
            isActive: {{ old('is_active', $plan?->is_active ?? true) ? 'true' : 'false' }},
            features: @json(old('features', $plan?->features ?? $defaultFeatures) ?? []),
            missingFeatures: @json(old('missing_features', $plan?->missing_features ?? []) ?? []),
            limits: @json($limitPairs),

            addFeature() {
                this.features.push('');
                this.$nextTick(() => {
                    const els = this.$el.querySelectorAll('[data-feature-input]');
                    els[els.length - 1]?.focus();
                });
            },
            removeFeature(index) {
                if (this.features.length > 1) this.features.splice(index, 1);
            },
            addMissingFeature() {
                this.missingFeatures.push('');
                this.$nextTick(() => {
                    const els = this.$el.querySelectorAll('[data-missing-input]');
                    els[els.length - 1]?.focus();
                });
            },
            removeMissingFeature(index) {
                this.missingFeatures.splice(index, 1);
            },
            addLimit() {
                this.limits.push({ key: '', value: '' });
            },
            removeLimit(index) {
                this.limits.splice(index, 1);
            },
        };
    }
</script>

<div class="max-w-3xl mx-auto" x-data="planForm()">
    <form
        method="POST"
        action="{{ $isEditing ? route('superadmin.plans.update', $plan) : route('superadmin.plans.store') }}"
        @submit="submitting = true"
        class="space-y-5"
    >
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        {{-- Hidden boolean inputs — driven by Alpine toggle state --}}
        <input type="hidden" name="is_featured" :value="isFeatured ? '1' : '0'">
        <input type="hidden" name="is_active"   :value="isActive   ? '1' : '0'">

        {{-- ── Validation error summary ──────────────────────────────────── --}}
        @if ($errors->any())
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl p-4">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800 mb-1">Please fix the following before saving:</p>
                    <ul class="space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs text-red-700">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ── Section 1 · Basic Information ────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-3.5 border-b border-gray-100 bg-gray-50/60">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Basic Information</p>
            </div>
            <div class="p-6 space-y-5">

                {{-- Plan Name --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Plan Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text" id="name" name="name"
                        value="{{ old('name', $plan?->name) }}"
                        placeholder="e.g., Starter, Professional, Enterprise"
                        maxlength="255" autocomplete="off"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm transition-all
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400
                               @error('name') border-red-300 bg-red-50/40 @else border-gray-200 bg-white @enderror"
                    >
                    @error('name')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="description" name="description" rows="3"
                        placeholder="e.g., Perfect for growing churches with up to 500 members."
                        maxlength="1000"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm resize-none transition-all
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400
                               @error('description') border-red-300 bg-red-50/40 @else border-gray-200 bg-white @enderror"
                    >{{ old('description', $plan?->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ── Section 2 · Pricing & Settings ───────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-3.5 border-b border-gray-100 bg-gray-50/60">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Pricing & Settings</p>
            </div>
            <div class="p-6 space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Monthly Price --}}
                    <div>
                        <label for="price_monthly" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Monthly Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-400">$</span>
                            <input
                                type="number" id="price_monthly" name="price_monthly"
                                value="{{ old('price_monthly', $plan?->price_monthly ?? '0') }}"
                                step="0.01" min="0" max="99999.99"
                                placeholder="0.00"
                                class="w-full pl-8 pr-4 py-2.5 border rounded-xl text-sm transition-all
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400
                                       @error('price_monthly') border-red-300 bg-red-50/40 @else border-gray-200 bg-white @enderror"
                            >
                        </div>
                        @error('price_monthly')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Trial Days --}}
                    <div>
                        <label for="trial_days" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Free Trial
                            <span class="ml-1 text-xs font-normal text-gray-400">optional</span>
                        </label>
                        <div class="relative">
                            <input
                                type="number" id="trial_days" name="trial_days"
                                value="{{ old('trial_days', $plan?->trial_days) }}"
                                min="0" max="365"
                                placeholder="0"
                                class="w-full pl-4 pr-14 py-2.5 border rounded-xl text-sm transition-all
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400
                                       @error('trial_days') border-red-300 bg-red-50/40 @else border-gray-200 bg-white @enderror"
                            >
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">days</span>
                        </div>
                        @error('trial_days')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- CTA Label --}}
                    <div>
                        <label for="cta_label" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Button Label <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text" id="cta_label" name="cta_label"
                            value="{{ old('cta_label', $plan?->cta_label ?? 'Get Started') }}"
                            placeholder="Get Started"
                            maxlength="50"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm transition-all
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400
                                   @error('cta_label') border-red-300 bg-red-50/40 @else border-gray-200 bg-white @enderror"
                        >
                        @error('cta_label')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Badge --}}
                    <div>
                        <label for="badge" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Badge
                            <span class="ml-1 text-xs font-normal text-gray-400">optional</span>
                        </label>
                        <input
                            type="text" id="badge" name="badge"
                            value="{{ old('badge', $plan?->badge) }}"
                            placeholder="e.g., Most Popular"
                            maxlength="100"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm transition-all
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400
                                   @error('badge') border-red-300 bg-red-50/40 @else border-gray-200 bg-white @enderror"
                        >
                        @error('badge')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Toggle Switches --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">

                    {{-- Featured toggle --}}
                    <div class="flex items-center justify-between gap-4 px-4 py-3.5 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800">Featured Plan</p>
                            <p class="text-xs text-gray-500 mt-0.5 truncate">Highlighted with a ribbon on the pricing page</p>
                        </div>
                        <button
                            type="button"
                            @click="isFeatured = !isFeatured"
                            :class="isFeatured ? 'bg-amber-400' : 'bg-gray-200'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 focus-visible:ring-offset-2"
                            role="switch"
                            :aria-checked="isFeatured.toString()"
                        >
                            <span
                                :class="isFeatured ? 'translate-x-6' : 'translate-x-1'"
                                class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform duration-200 ease-in-out"
                            ></span>
                        </button>
                    </div>

                    {{-- Active toggle --}}
                    <div class="flex items-center justify-between gap-4 px-4 py-3.5 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800">Active</p>
                            <p class="text-xs text-gray-500 mt-0.5 truncate">Visible to tenants on the sign-up page</p>
                        </div>
                        <button
                            type="button"
                            @click="isActive = !isActive"
                            :class="isActive ? 'bg-emerald-500' : 'bg-gray-200'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2"
                            role="switch"
                            :aria-checked="isActive.toString()"
                        >
                            <span
                                :class="isActive ? 'translate-x-6' : 'translate-x-1'"
                                class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform duration-200 ease-in-out"
                            ></span>
                        </button>
                    </div>

                </div>

            </div>
        </div>

        {{-- ── Section 3 · Included Features ────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-3.5 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Included Features <span class="text-red-400">*</span></p>
                    <p class="text-xs text-gray-400 mt-0.5">Shown with a checkmark on the pricing page</p>
                </div>
                <button
                    type="button"
                    @click="addFeature()"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Feature
                </button>
            </div>
            <div class="p-6">
                <div class="space-y-2.5">
                    <template x-for="(feature, index) in features" :key="index">
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 flex-shrink-0 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <input
                                type="text"
                                name="features[]"
                                x-model="features[index]"
                                placeholder="e.g., Unlimited member profiles"
                                maxlength="255"
                                data-feature-input
                                class="flex-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400"
                            >
                            <button
                                type="button"
                                @click="removeFeature(index)"
                                x-show="features.length > 1"
                                class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                title="Remove feature"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                            {{-- spacer so rows align when remove button is hidden --}}
                            <div x-show="features.length <= 1" class="w-8 flex-shrink-0"></div>
                        </div>
                    </template>
                </div>
                @error('features')
                    <p class="mt-3 flex items-center gap-1 text-xs text-red-600">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- ── Section 4 · Not Included (Missing Features) ──────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-3.5 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Not Included <span class="text-gray-300 font-normal normal-case tracking-normal">(optional)</span></p>
                    <p class="text-xs text-gray-400 mt-0.5">Shown crossed-out on the pricing page to highlight plan limits</p>
                </div>
                <button
                    type="button"
                    @click="addMissingFeature()"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add
                </button>
            </div>
            <div class="p-6">
                <div class="space-y-2.5">
                    <template x-if="missingFeatures.length === 0">
                        <p class="text-xs text-gray-400 italic">None set — click "Add" to show what this plan does not include.</p>
                    </template>
                    <template x-for="(feature, index) in missingFeatures" :key="index">
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 flex-shrink-0 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </div>
                            <input
                                type="text"
                                name="missing_features[]"
                                x-model="missingFeatures[index]"
                                placeholder="e.g., Advanced analytics"
                                maxlength="255"
                                data-missing-input
                                class="flex-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm transition-all focus:outline-none focus:ring-2 focus:ring-gray-300/40 focus:border-gray-300"
                            >
                            <button
                                type="button"
                                @click="removeMissingFeature(index)"
                                class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                title="Remove"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ── Section 5 · Technical Limits ─────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-3.5 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Technical Limits <span class="text-gray-300 font-normal normal-case tracking-normal">(optional)</span></p>
                    <p class="text-xs text-gray-400 mt-0.5">Hard limits enforced by application logic</p>
                </div>
                <button
                    type="button"
                    @click="addLimit()"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Limit
                </button>
            </div>
            <div class="p-6">
                <div class="space-y-2.5">
                    <template x-if="limits.length === 0">
                        <p class="text-xs text-gray-400 italic">No limits set — this plan has no hard restrictions.</p>
                    </template>
                    <template x-for="(limit, index) in limits" :key="index">
                        <div class="flex items-center gap-2.5">
                            <select
                                name="limit_keys[]"
                                x-model="limits[index].key"
                                class="w-52 flex-shrink-0 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400"
                            >
                                <option value="">Select type…</option>
                                <option value="max_members">Max Members</option>
                                <option value="max_admins">Max Admins</option>
                                <option value="max_storage_mb">Max Storage (MB)</option>
                                <option value="max_events_per_month">Max Events / Month</option>
                                <option value="max_announcements">Max Announcements</option>
                            </select>
                            <input
                                type="number"
                                name="limit_values[]"
                                x-model="limits[index].value"
                                placeholder="e.g., 500"
                                min="1"
                                class="flex-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400"
                            >
                            <button
                                type="button"
                                @click="removeLimit(index)"
                                class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                title="Remove limit"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ── Form Actions ──────────────────────────────────────────────── --}}
        <div class="flex items-center gap-3 pb-2">
            <a
                href="{{ route('superadmin.plans.index') }}"
                class="px-6 py-3 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors"
            >
                Cancel
            </a>
            <button
                type="submit"
                :disabled="submitting"
                class="flex flex-1 items-center justify-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-semibold shadow-sm transition-all"
            >
                <svg
                    x-show="submitting"
                    class="animate-spin w-4 h-4"
                    fill="none" viewBox="0 0 24 24"
                    style="display:none"
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="submitting ? 'Saving…' : '{{ $isEditing ? 'Update Plan' : 'Save Plan' }}'">
                    {{ $isEditing ? 'Update Plan' : 'Save Plan' }}
                </span>
            </button>
        </div>

    </form>
</div>

@endsection
