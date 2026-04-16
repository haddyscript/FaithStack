@extends('superadmin.layouts.app')

@section('title', 'Tenants')
@section('heading', 'Tenants')
@section('breadcrumbs')
    <x-breadcrumb :items="[['label'=>'Platform','url'=>route('superadmin.dashboard')],['label'=>'Tenants']]" />
@endsection

@section('header-actions')
    <a href="{{ route('superadmin.tenants.create') }}"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Tenant
    </a>
@endsection

@section('content')

{{-- Delete modal --}}
<div x-data="{ open: false, name: '', action: '' }"
     x-on:open-delete.window="open = true; name = $event.detail.name; action = $event.detail.action"
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
            <h3 class="text-base font-semibold text-gray-900 mb-2">Delete Tenant</h3>
            <p class="text-sm text-gray-500 mb-5">Delete <strong x-text="name" class="text-gray-800"></strong> and all their data? This cannot be undone.</p>
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

{{-- Filters --}}
<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, subdomain, or email…"
               class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 bg-white placeholder-gray-400 transition-all">
    </div>
    <select name="status" onchange="this.form.submit()"
            class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 bg-white text-gray-600 transition-all">
        <option value="">All statuses</option>
        <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
        <option value="trial"     {{ request('status') === 'trial'     ? 'selected' : '' }}>Trial</option>
        <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Expired</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
    @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('superadmin.tenants.index') }}"
           class="px-4 py-2.5 text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors whitespace-nowrap">
            Clear
        </a>
    @endif
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50/60 border-b border-gray-100">
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Organization</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Subdomain</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Theme</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Pages</th>
                <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($tenants as $tenant)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white font-bold text-sm flex items-center justify-center flex-shrink-0 shadow-sm">
                                {{ strtoupper(substr($tenant->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $tenant->name }}</p>
                                <p class="text-xs text-gray-400">{{ $tenant->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <a href="http://{{ $tenant->subdomain }}.{{ config('app.base_domain') }}/admin" target="_blank"
                           class="inline-flex items-center gap-1 text-xs font-mono text-gray-500 hover:text-emerald-600 transition-colors group">
                            {{ $tenant->subdomain }}
                            <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </td>
                    <td class="px-5 py-4 hidden sm:table-cell">
                        <span class="text-xs text-gray-400">{{ $tenant->theme?->name ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $statusClass = match($tenant->subscription_status) {
                                'active'    => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                                'trial'     => 'bg-blue-100 text-blue-700 ring-blue-200',
                                'expired'   => 'bg-red-100 text-red-600 ring-red-200',
                                'cancelled' => 'bg-gray-100 text-gray-500 ring-gray-200',
                                default     => 'bg-gray-100 text-gray-500 ring-gray-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                            {{ ucfirst($tenant->subscription_status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell">
                        <span class="text-sm font-semibold text-gray-600">{{ $tenant->pages_count }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-1.5">
                            {{-- Toggle subscription --}}
                            <form method="POST" action="{{ route('superadmin.tenants.toggle-subscription', $tenant) }}">
                                @csrf
                                <button type="submit"
                                    title="{{ $tenant->subscription_status === 'active' ? 'Deactivate' : 'Activate' }}"
                                    class="p-1.5 rounded-lg text-xs font-semibold transition-colors
                                        {{ $tenant->subscription_status === 'active'
                                            ? 'text-red-400 hover:bg-red-50 hover:text-red-600'
                                            : 'text-emerald-500 hover:bg-emerald-50 hover:text-emerald-700' }}">
                                    @if($tenant->subscription_status === 'active')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </button>
                            </form>
                            <a href="{{ route('superadmin.tenants.edit', $tenant) }}"
                               class="p-1.5 rounded-lg text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <button
                                @click="$dispatch('open-delete', { name: '{{ addslashes($tenant->name) }}', action: '{{ route('superadmin.tenants.destroy', $tenant) }}' })"
                                class="p-1.5 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <p class="text-sm text-gray-400 mb-3">No tenants found.</p>
                        <a href="{{ route('superadmin.tenants.create') }}"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                            + Create your first tenant
                        </a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($tenants->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">
            {{ $tenants->links() }}
        </div>
    @endif
</div>

<p class="text-xs text-gray-400 mt-3">{{ $tenants->total() }} tenant{{ $tenants->total() !== 1 ? 's' : '' }} total</p>

@endsection
