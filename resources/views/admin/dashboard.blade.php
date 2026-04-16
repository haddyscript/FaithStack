@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-5 border">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Pages</p>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['pages'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Published</p>
        <p class="text-3xl font-bold text-green-600">{{ $stats['published'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Donations</p>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['donations'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border">
        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Revenue</p>
        <p class="text-3xl font-bold text-blue-600">${{ number_format($stats['revenue'], 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl shadow-sm p-5 border">
        <h3 class="font-semibold text-gray-700 mb-3">Quick Links</h3>
        <div class="space-y-2">
            <a href="{{ route('admin.pages.create') }}" class="block text-sm text-blue-600 hover:underline">+ Create new page</a>
            <a href="{{ route('admin.navigation.index') }}" class="block text-sm text-blue-600 hover:underline">Manage navigation</a>
            <a href="{{ route('admin.settings') }}" class="block text-sm text-blue-600 hover:underline">Site settings & theme</a>
            <a href="{{ route('home') }}" target="_blank" class="block text-sm text-blue-600 hover:underline">View public site ↗</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border">
        <h3 class="font-semibold text-gray-700 mb-3">Subscription</h3>
        <p class="text-sm text-gray-600">
            Status:
            <span class="font-medium {{ $tenant->subscription_status === 'active' ? 'text-green-600' : 'text-yellow-600' }}">
                {{ ucfirst($tenant->subscription_status) }}
            </span>
        </p>
        @if($tenant->subscription_ends_at)
            <p class="text-sm text-gray-500 mt-1">
                Expires: {{ $tenant->subscription_ends_at->format('M d, Y') }}
            </p>
        @endif
    </div>
</div>
@endsection
