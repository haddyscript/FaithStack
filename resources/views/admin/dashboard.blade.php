@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 p-6 md:p-8 mb-8 text-white shadow-lg">
    <div class="relative z-10">
        <p class="text-indigo-200 text-sm font-medium mb-1">Welcome back,</p>
        <h2 class="text-2xl md:text-3xl font-bold mb-1">{{ $tenant->name }}</h2>
        <p class="text-indigo-200 text-sm">
            @if($tenant->theme)
                Active theme: <span class="font-medium text-white">{{ $tenant->theme->name }}</span>
            @else
                No theme selected yet. <a href="{{ route('admin.themes.index') }}" class="underline text-white font-medium">Choose a theme →</a>
            @endif
        </p>
    </div>
    {{-- Decorative circles --}}
    <div class="absolute -right-8 -top-8 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute -right-4 -bottom-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute right-24 top-4 w-16 h-16 rounded-full bg-white/10 pointer-events-none"></div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <x-admin.stat-card
        label="Total Pages"
        :value="$stats['pages']"
        icon="pages"
        color="indigo"
    />
    <x-admin.stat-card
        label="Published"
        :value="$stats['published']"
        icon="check"
        color="green"
    />
    <x-admin.stat-card
        label="Donations"
        :value="$stats['donations']"
        icon="heart"
        color="rose"
    />
    <x-admin.stat-card
        label="Revenue"
        :value="$stats['revenue']"
        icon="currency"
        color="amber"
        prefix="$"
        :animated="false"
    />
</div>

{{-- Lower grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Quick Actions --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

            <a href="{{ route('admin.pages.create') }}"
               class="group flex items-center gap-3 p-4 rounded-xl border border-dashed border-indigo-200 bg-indigo-50/50 hover:bg-indigo-50 hover:border-indigo-300 transition-all duration-150">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">New Page</p>
                    <p class="text-xs text-gray-500">Create & publish content</p>
                </div>
            </a>

            <a href="{{ route('admin.navigation.index') }}"
               class="group flex items-center gap-3 p-4 rounded-xl border border-dashed border-blue-200 bg-blue-50/50 hover:bg-blue-50 hover:border-blue-300 transition-all duration-150">
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Navigation</p>
                    <p class="text-xs text-gray-500">Manage menu items</p>
                </div>
            </a>

            <a href="{{ route('admin.themes.index') }}"
               class="group flex items-center gap-3 p-4 rounded-xl border border-dashed border-purple-200 bg-purple-50/50 hover:bg-purple-50 hover:border-purple-300 transition-all duration-150">
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Change Theme</p>
                    <p class="text-xs text-gray-500">10+ professional themes</p>
                </div>
            </a>

            <a href="{{ route('admin.settings') }}"
               class="group flex items-center gap-3 p-4 rounded-xl border border-dashed border-gray-200 bg-gray-50/50 hover:bg-gray-50 hover:border-gray-300 transition-all duration-150">
                <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Settings</p>
                    <p class="text-xs text-gray-500">Site info & branding</p>
                </div>
            </a>

            <a href="{{ route('admin.donations.index') }}"
               class="group flex items-center gap-3 p-4 rounded-xl border border-dashed border-rose-200 bg-rose-50/50 hover:bg-rose-50 hover:border-rose-300 transition-all duration-150">
                <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Donations</p>
                    <p class="text-xs text-gray-500">View & manage giving</p>
                </div>
            </a>

            <a href="{{ route('home') }}" target="_blank"
               class="group flex items-center gap-3 p-4 rounded-xl border border-dashed border-teal-200 bg-teal-50/50 hover:bg-teal-50 hover:border-teal-300 transition-all duration-150">
                <div class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">View Site</p>
                    <p class="text-xs text-gray-500">Open public website ↗</p>
                </div>
            </a>

        </div>
    </div>

    {{-- Subscription & Info --}}
    <div class="flex flex-col gap-6">

        {{-- Subscription status --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Subscription</h3>

            <div class="flex items-center gap-3 mb-4">
                @php
                    $statusColor = match($tenant->subscription_status) {
                        'active' => 'green',
                        'trial'  => 'blue',
                        default  => 'yellow',
                    };
                @endphp
                <x-admin.badge :color="$statusColor" :dot="true">
                    {{ ucfirst($tenant->subscription_status) }}
                </x-admin.badge>
            </div>

            @if($tenant->subscription_ends_at)
                <p class="text-xs text-gray-500 mb-1">Expires</p>
                <p class="text-sm font-semibold text-gray-700">{{ $tenant->subscription_ends_at->format('M d, Y') }}</p>
                @if($tenant->subscription_ends_at->diffInDays(now()) < 7 && $tenant->subscription_ends_at->isFuture())
                    <div class="mt-3">
                        <x-admin.alert type="warning" :dismissible="false">
                            Subscription expires in {{ $tenant->subscription_ends_at->diffForHumans() }}.
                        </x-admin.alert>
                    </div>
                @endif
            @else
                <p class="text-xs text-gray-400 italic">No expiry date set</p>
            @endif
        </div>

        {{-- Site info --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex-1">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Site Info</h3>
            <dl class="space-y-3 text-sm">
                @if($tenant->email)
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wider">Email</dt>
                        <dd class="text-gray-700 font-medium truncate">{{ $tenant->email }}</dd>
                    </div>
                @endif
                @if($tenant->phone)
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wider">Phone</dt>
                        <dd class="text-gray-700 font-medium">{{ $tenant->phone }}</dd>
                    </div>
                @endif
                @if($tenant->address)
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wider">Address</dt>
                        <dd class="text-gray-700 font-medium">{{ $tenant->address }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-xs text-gray-400 uppercase tracking-wider">Pages</dt>
                    <dd class="text-gray-700 font-medium">{{ $stats['pages'] }} total · {{ $stats['published'] }} published</dd>
                </div>
            </dl>
        </div>
    </div>
</div>

@endsection
