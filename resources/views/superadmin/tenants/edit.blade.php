@extends('superadmin.layouts.app')

@section('title', 'Edit Tenant')
@section('heading', 'Edit Tenant')
@section('breadcrumbs')
    <x-breadcrumb :items="[['label'=>'Platform','url'=>route('superadmin.dashboard')],['label'=>'Tenants','url'=>route('superadmin.tenants.index')],['label'=>$tenant->name]]" />
@endsection

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('superadmin.tenants.index') }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
        <a href="http://{{ $tenant->subdomain }}.{{ config('app.base_domain') }}/admin" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium text-emerald-600 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Open Admin ↗
        </a>
    </div>
@endsection

@section('content')

@if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
        <p class="font-semibold mb-1">Please fix the following:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('superadmin.tenants.update', $tenant) }}" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    @csrf
    @method('PUT')

    {{-- Main --}}
    <div class="xl:col-span-2 space-y-6">

        {{-- Organization --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-5">Organization</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Subdomain <span class="text-red-400">*</span></label>
                    <div class="flex items-stretch border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500/30 focus-within:border-emerald-400 transition-all">
                        <input type="text" name="subdomain" value="{{ old('subdomain', $tenant->subdomain) }}" required
                               class="flex-1 px-4 py-2.5 text-sm font-mono focus:outline-none bg-white">
                        <span class="inline-flex items-center px-3 bg-gray-50 text-gray-400 text-xs border-l border-gray-200 select-none">.{{ config('app.base_domain') }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $tenant->email) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Address</label>
                    <input type="text" name="address" value="{{ old('address', $tenant->address) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all">
                </div>
            </div>
        </div>

        {{-- Admin accounts (read-only list) --}}
        @if($admins->count())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Admin Users</h3>
            <div class="space-y-2.5">
                @foreach($admins as $admin)
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 font-bold text-xs flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($admin->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $admin->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $admin->email }}</p>
                        </div>
                        <span class="ml-auto text-xs text-gray-400">Admin</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">

        {{-- Subscription --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-5">Subscription</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Status</label>
                    <select name="subscription_status"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 bg-white text-gray-700 transition-all">
                        @foreach(['trial' => 'Trial', 'active' => 'Active', 'expired' => 'Expired', 'cancelled' => 'Cancelled'] as $val => $label)
                            <option value="{{ $val }}" {{ old('subscription_status', $tenant->subscription_status) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Expires At</label>
                    <input type="date" name="subscription_ends_at"
                           value="{{ old('subscription_ends_at', $tenant->subscription_ends_at?->format('Y-m-d')) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all text-gray-700">
                </div>
            </div>
        </div>

        {{-- Theme --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-5">Theme</h3>
            <select name="theme_id"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 bg-white text-gray-700 transition-all">
                <option value="">— No theme —</option>
                @foreach($themes->groupBy('category') as $category => $categoryThemes)
                    <optgroup label="{{ ucfirst($category) }}">
                        @foreach($categoryThemes as $theme)
                            <option value="{{ $theme->id }}" {{ old('theme_id', $tenant->theme_id) == $theme->id ? 'selected' : '' }}>
                                {{ $theme->name }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        {{-- Save --}}
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3.5 rounded-2xl text-sm shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save Changes
        </button>

        {{-- Danger zone --}}
        <div class="bg-red-50 rounded-2xl border border-red-100 p-5">
            <h4 class="text-xs font-bold text-red-600 uppercase tracking-wider mb-3">Danger Zone</h4>
            <p class="text-xs text-red-500 mb-3">Permanently delete this tenant and all associated data.</p>
            <form method="POST" action="{{ route('superadmin.tenants.destroy', $tenant) }}"
                  onsubmit="return confirm('Delete {{ addslashes($tenant->name) }} permanently? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-colors">
                    Delete Tenant
                </button>
            </form>
        </div>

    </div>

</form>

@endsection
