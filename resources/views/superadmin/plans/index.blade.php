@extends('superadmin.layouts.app')

@section('title', 'Plans')
@section('heading', 'Subscription Plans')
@section('breadcrumbs')
    <x-breadcrumb :items="[['label'=>'Platform','url'=>route('superadmin.dashboard')],['label'=>'Plans']]" />
@endsection

@section('header-actions')
    <a href="{{ route('superadmin.plans.create') }}"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Plan
    </a>
@endsection

@section('content')

{{-- Success/Error Messages --}}
@if($message = session('success'))
    <div x-data="{ open: true }" x-show="open" x-transition:leave="transition duration-200 ease-in-out" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" class="mb-6 flex items-start gap-3 bg-emerald-50 border border-emerald-200 rounded-xl p-4">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <div class="flex-1">
            <p class="text-sm font-semibold text-emerald-900">{{ $message }}</p>
        </div>
        <button @click="open = false" class="text-emerald-400 hover:text-emerald-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
    </div>
@endif

@if($message = session('error'))
    <div x-data="{ open: true }" x-show="open" x-transition:leave="transition duration-200 ease-in-out" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl p-4">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <div class="flex-1">
            <p class="text-sm font-semibold text-red-900">{{ $message }}</p>
        </div>
        <button @click="open = false" class="text-red-400 hover:text-red-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
    </div>
@endif

{{-- Delete Modal --}}
<div x-data="{ open: false, name: '', action: '' }"
     x-on:open-delete.window="open = true; name = $event.detail.name; action = $event.detail.action; error = ''"
     x-show="open"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display:none">
    <div x-show="open" @click="open = false"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="open" @click.stop
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-sm bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-2">Delete Plan</h3>
            <p class="text-sm text-gray-500 mb-5">Delete <strong x-text="name" class="text-gray-800"></strong>? This action cannot be undone.</p>
            <div class="flex gap-3">
                <button @click="open = false" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
                <form :action="action" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-colors">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Empty State --}}
@if($plans->isEmpty())
    <div class="flex flex-col items-center justify-center py-16">
        <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-3H6"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">No plans yet</h3>
        <p class="text-sm text-gray-500 mb-6">Create your first subscription plan to get started.</p>
        <a href="{{ route('superadmin.plans.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Create Plan
        </a>
    </div>
@else
    {{-- Plans Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-gray-200 transition-all duration-200 overflow-hidden group">
                {{-- Featured Badge --}}
                @if($plan->is_featured)
                    <div class="absolute top-0 right-0 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">
                        FEATURED
                    </div>
                @endif

                {{-- Header --}}
                <div class="p-6 pb-4 border-b border-gray-50">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                            @if($plan->badge)
                                <p class="text-xs text-gray-500 mt-1">{{ $plan->badge }}</p>
                            @endif
                        </div>
                        {{-- Status Badge --}}
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ring-1 ring-inset
                            {{ $plan->is_active
                                ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                                : 'bg-gray-100 text-gray-500 ring-gray-200' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                {{-- Price & Description --}}
                <div class="px-6 py-4 border-b border-gray-50">
                    <div class="mb-3">
                        <p class="text-3xl font-black text-gray-900">
                            @if($plan->isFree())
                                <span>Free</span>
                            @else
                                <span>${{ number_format($plan->price_monthly, 0) }}</span>
                                <span class="text-sm font-normal text-gray-500">/mo</span>
                            @endif
                        </p>
                        @if($plan->trial_days)
                            <p class="text-xs text-emerald-600 font-medium mt-1">{{ $plan->trial_days }}-day trial</p>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $plan->description }}</p>
                </div>

                {{-- Features --}}
                <div class="px-6 py-4 border-b border-gray-50">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Features</p>
                    <ul class="space-y-2.5">
                        @foreach($plan->features ?? [] as $feature)
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-600">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @if($plan->limits && count($plan->limits) > 0)
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-xs font-medium text-blue-900 mb-2">Limits</p>
                            <ul class="space-y-1">
                                @foreach($plan->limits as $key => $value)
                                    <li class="text-xs text-blue-800">
                                        <span class="font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                        {{ is_numeric($value) ? number_format($value) : $value }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Stats --}}
                @if($plan->tenants_count !== null)
                    <div class="px-6 py-4 border-b border-gray-50">
                        <p class="text-xs text-gray-500">
                            <span class="font-semibold text-gray-700">{{ $plan->tenants_count }}</span>
                            {{ str_plural('subscriber', $plan->tenants_count) }}
                        </p>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="px-6 py-4 flex items-center justify-between">
                    <p class="text-xs text-gray-400">{{ $plan->created_at->format('M d, Y') }}</p>
                    <div class="flex items-center gap-1.5">
                        {{-- Toggle Active Status --}}
                        <form method="POST" action="{{ route('superadmin.plans.toggle', $plan) }}" class="inline">
                            @csrf
                            <button type="submit"
                                title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}"
                                class="p-2 rounded-lg text-xs font-semibold transition-colors
                                    {{ $plan->is_active
                                        ? 'text-amber-500 hover:bg-amber-50 hover:text-amber-700'
                                        : 'text-emerald-500 hover:bg-emerald-50 hover:text-emerald-700' }}">
                                @if($plan->is_active)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </button>
                        </form>

                        {{-- Edit --}}
                        <a href="{{ route('superadmin.plans.edit', $plan) }}"
                           class="p-2 rounded-lg text-gray-400 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>

                        {{-- Delete --}}
                        <button
                            @click="$dispatch('open-delete', { name: '{{ addslashes($plan->name) }}', action: '{{ route('superadmin.plans.destroy', $plan) }}' })"
                            class="p-2 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
