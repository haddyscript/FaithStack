@extends('superadmin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Overview')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    @php
    $cards = [
        ['label' => 'Total Tenants',   'value' => $stats['total_tenants'],  'color' => 'from-slate-600 to-slate-700',   'icon' => 'building'],
        ['label' => 'Active',          'value' => $stats['active'],         'color' => 'from-emerald-500 to-emerald-600','icon' => 'check'],
        ['label' => 'On Trial',        'value' => $stats['trial'],          'color' => 'from-blue-500 to-blue-600',     'icon' => 'clock'],
        ['label' => 'Platform Revenue','value' => '$' . number_format($stats['total_revenue'], 0), 'color' => 'from-amber-500 to-amber-600', 'icon' => 'currency'],
    ];
    @endphp

    @foreach($cards as $card)
        <div class="relative overflow-hidden rounded-2xl bg-white border border-gray-100 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ $card['label'] }}</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $card['value'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $card['color'] }} flex items-center justify-center text-white shadow-sm">
                    @if($card['icon'] === 'building')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    @elseif($card['icon'] === 'check')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($card['icon'] === 'clock')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Lower grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Recent tenants --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
            <h3 class="text-sm font-semibold text-gray-900">Recent Tenants</h3>
            <a href="{{ route('superadmin.tenants.index') }}"
               class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">View all →</a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/60">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tenant</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Subdomain</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentTenants as $tenant)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $tenant->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $tenant->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 hidden sm:table-cell">
                            <span class="text-xs font-mono text-gray-500">{{ $tenant->subdomain }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            @php
                                $color = match($tenant->subscription_status) {
                                    'active'    => 'bg-emerald-100 text-emerald-700',
                                    'trial'     => 'bg-blue-100 text-blue-700',
                                    'expired'   => 'bg-red-100 text-red-600',
                                    default     => 'bg-gray-100 text-gray-500',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                {{ ucfirst($tenant->subscription_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <a href="{{ route('superadmin.tenants.edit', $tenant) }}"
                               class="text-xs font-semibold text-gray-400 hover:text-emerald-600 transition-colors">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400">No tenants yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Quick actions --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-2.5">
                <a href="{{ route('superadmin.tenants.create') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 transition-colors group">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-emerald-800">Create Tenant</p>
                        <p class="text-xs text-emerald-600">Onboard new organization</p>
                    </div>
                </a>

                <a href="{{ route('superadmin.tenants.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-100 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-gray-200 text-gray-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">All Tenants</p>
                        <p class="text-xs text-gray-400">{{ $stats['total_tenants'] }} organizations</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Platform stats --}}
        <div class="bg-gray-950 rounded-2xl p-6 text-white">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Platform Stats</h3>
            <dl class="space-y-3">
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-400">Total pages</dt>
                    <dd class="font-bold text-white">{{ number_format($stats['total_pages']) }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-400">Total donations</dt>
                    <dd class="font-bold text-white">{{ number_format($stats['total_donations']) }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-400">Expired / Cancelled</dt>
                    <dd class="font-bold text-red-400">{{ $stats['expired'] }}</dd>
                </div>
            </dl>
        </div>
    </div>

</div>

@endsection
