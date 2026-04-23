@extends('superadmin.layouts.app')

@section('title', $isEditing ? 'Edit Plan' : 'Create Plan')
@section('heading', $isEditing ? 'Edit Plan' : 'Create Plan')
@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label'=>'Platform','url'=>route('superadmin.dashboard')],
        ['label'=>'Plans','url'=>route('superadmin.plans.index')],
        ['label' => $isEditing ? 'Edit' : 'Create']
    ]" />
@endsection

@section('content')

<div class="max-w-3xl mx-auto">
    <form method="POST" action="{{ $isEditing ? route('superadmin.plans.update', $plan) : route('superadmin.plans.store') }}" class="space-y-6">
        @csrf
        @if($isEditing)
            @method('PUT')
        @endif

        {{-- Basic Information --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Basic Information</h3>

            {{-- Plan Name --}}
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Plan Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $plan?->name) }}"
                       placeholder="e.g., Starter, Professional, Enterprise"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all @error('name') border-red-300 @enderror"
                       maxlength="255">
                @error('name')
                    <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                <textarea id="description" name="description" rows="3"
                          placeholder="E.g., Perfect for growing churches..."
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all resize-none @error('description') border-red-300 @enderror"
                          maxlength="1000">{{ old('description', $plan?->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Grid: Price, Trial Days, CTA Label --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Monthly Price --}}
                <div>
                    <label for="price_monthly" class="block text-sm font-semibold text-gray-700 mb-2">Monthly Price ($) *</label>
                    <input type="number" id="price_monthly" name="price_monthly" value="{{ old('price_monthly', $plan?->price_monthly ?? '0') }}"
                           placeholder="0"
                           step="0.01" min="0" max="99999.99"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all @error('price_monthly') border-red-300 @enderror">
                    @error('price_monthly')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Trial Days --}}
                <div>
                    <label for="trial_days" class="block text-sm font-semibold text-gray-700 mb-2">Trial Period (days)</label>
                    <input type="number" id="trial_days" name="trial_days" value="{{ old('trial_days', $plan?->trial_days) }}"
                           placeholder="14"
                           min="0" max="365"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all @error('trial_days') border-red-300 @enderror">
                    @error('trial_days')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CTA Label --}}
                <div>
                    <label for="cta_label" class="block text-sm font-semibold text-gray-700 mb-2">Button Label *</label>
                    <input type="text" id="cta_label" name="cta_label" value="{{ old('cta_label', $plan?->cta_label ?? 'Get Started') }}"
                           placeholder="Get Started"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all @error('cta_label') border-red-300 @enderror"
                           maxlength="50">
                    @error('cta_label')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Grid: Badge, Status, Featured --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Badge --}}
                <div>
                    <label for="badge" class="block text-sm font-semibold text-gray-700 mb-2">Badge (optional)</label>
                    <input type="text" id="badge" name="badge" value="{{ old('badge', $plan?->badge) }}"
                           placeholder="e.g., Most Popular"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all @error('badge') border-red-300 @enderror"
                           maxlength="100">
                    @error('badge')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Is Featured --}}
                <div class="flex items-center">
                    <label class="flex items-center gap-3 cursor-pointer h-full">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $plan?->is_featured) == '1' || old('is_featured', $plan?->is_featured) == true ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">Mark as Featured</span>
                    </label>
                </div>

                {{-- Is Active --}}
                <div class="flex items-center">
                    <label class="flex items-center gap-3 cursor-pointer h-full">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan?->is_active ?? true) == '1' || old('is_active', $plan?->is_active ?? true) == true ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">Active (visible to tenants)</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Features & Inclusions --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Features & Inclusions *</h3>
                <button type="button"
                        onclick="addFeatureRow()"
                        class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 px-3 py-1.5 rounded-lg hover:bg-emerald-50 transition-colors">
                    + Add Feature
                </button>
            </div>

            <div data-features-container class="space-y-2.5">
                @forelse(old('features', $plan?->features ?? $defaultFeatures) as $feature)
                    <div class="flex items-center gap-2.5">
                        <input type="text" name="features[]" value="{{ $feature }}"
                               placeholder="Feature name"
                               class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all"
                               maxlength="255">
                        <button type="button"
                                onclick="this.parentElement.remove()"
                                class="p-2 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @empty
                    <div class="flex items-center gap-2.5">
                        <input type="text" name="features[]" value=""
                               placeholder="Feature name"
                               class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all"
                               maxlength="255">
                        <button type="button"
                                onclick="this.parentElement.remove()"
                                class="p-2 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endforelse
            </div>

            @error('features')
                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
            @enderror

            <p class="text-xs text-gray-500 mt-4">These features are displayed to tenants when browsing plans.</p>
        </div>

        {{-- Limits & Constraints --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Limits & Constraints (optional)</h3>
                <button type="button"
                        onclick="addLimitRow()"
                        class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 px-3 py-1.5 rounded-lg hover:bg-emerald-50 transition-colors">
                    + Add Limit
                </button>
            </div>

            <div data-limits-container class="space-y-2.5">
                @forelse(old('limits', $plan?->limits ?? []) as $key => $value)
                    <div class="flex items-center gap-2.5">
                        <select name="limits[{{ $key }}]"
                                class="px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all bg-white">
                            <option value="">Select limit type</option>
                            <option value="max_members" {{ $key === 'max_members' ? 'selected' : '' }}>Max Members</option>
                            <option value="max_storage_mb" {{ $key === 'max_storage_mb' ? 'selected' : '' }}>Max Storage (MB)</option>
                        </select>
                        <input type="number" name="limits[{{ $key }}_value]" value="{{ $value }}"
                               placeholder="Enter value"
                               min="1"
                               class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all">
                        <button type="button"
                                onclick="this.parentElement.remove()"
                                class="p-2 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                    @empty
                @endforelse
            </div>

            <p class="text-xs text-gray-500 mt-4">Define technical limits for this plan (e.g., max members, storage). These can be enforced by your app logic.</p>
        </div>

        {{-- Form Actions --}}
        <div class="flex gap-3">
            <a href="{{ route('superadmin.plans.index') }}"
               class="flex-1 px-6 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors text-center">
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition-colors">
                {{ $isEditing ? 'Update Plan' : 'Create Plan' }}
            </button>
        </div>
    </form>
</div>

<script>
function addFeatureRow() {
    const html = '<div class="flex items-center gap-2.5"><input type="text" name="features[]" value="" placeholder="Feature name" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all" maxlength="255"><button type="button" onclick="this.parentElement.remove()" class="p-2 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button></div>';
    document.querySelector('[data-features-container]').insertAdjacentHTML('beforeend', html);
}

function addLimitRow() {
    const html = '<div class="flex items-center gap-2.5"><select name="limits[new_' + Date.now() + ']" class="px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all bg-white"><option value="">Select limit type</option><option value="max_members">Max Members</option><option value="max_storage_mb">Max Storage (MB)</option></select><input type="number" name="limits[new_' + Date.now() + '_value]" placeholder="Enter value" min="1" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all"><button type="button" onclick="this.parentElement.remove()" class="p-2 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button></div>';
    document.querySelector('[data-limits-container]').insertAdjacentHTML('beforeend', html);
}
</script>

@endsection
