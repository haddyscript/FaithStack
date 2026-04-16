@extends('superadmin.layouts.app')

@section('title', 'Create Tenant')
@section('heading', 'Create Tenant')
@section('breadcrumbs')
    <x-breadcrumb :items="[['label'=>'Platform','url'=>route('superadmin.dashboard')],['label'=>'Tenants','url'=>route('superadmin.tenants.index')],['label'=>'New Tenant']]" />
@endsection

@section('header-actions')
    <a href="{{ route('superadmin.tenants.index') }}"
       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Back
    </a>
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

<form method="POST" action="{{ route('superadmin.tenants.store') }}" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    @csrf

    {{-- Main --}}
    <div class="xl:col-span-2 space-y-6">

        {{-- Organization --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-5">Organization</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Organization Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all"
                           placeholder="First Baptist Church">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Subdomain <span class="text-red-400">*</span></label>
                    <div class="flex items-stretch border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500/30 focus-within:border-emerald-400 transition-all">
                        <input type="text" name="subdomain" value="{{ old('subdomain') }}" required
                               id="subdomain-input"
                               class="flex-1 px-4 py-2.5 text-sm font-mono focus:outline-none bg-white"
                               placeholder="church1">
                        <span class="inline-flex items-center px-3 bg-gray-50 text-gray-400 text-xs border-l border-gray-200 select-none">.{{ config('app.base_domain') }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all"
                           placeholder="admin@church.com">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all"
                           placeholder="+1 (555) 000-0000">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Address</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all"
                           placeholder="123 Main St, City, State">
                </div>
            </div>
        </div>

        {{-- Admin user --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-5">Admin Account</h3>
            <p class="text-xs text-gray-400 mb-4">This user will be able to log in to the tenant's admin panel.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Full Name <span class="text-red-400">*</span></label>
                    <input type="text" name="admin_name" value="{{ old('admin_name') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all"
                           placeholder="Pastor John Doe">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Login Email <span class="text-red-400">*</span></label>
                    <input type="email" name="admin_email" value="{{ old('admin_email') }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all"
                           placeholder="pastor@church.com">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Password <span class="text-red-400">*</span></label>
                    <input type="password" name="admin_password" required minlength="8"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all"
                           placeholder="Min. 8 characters">
                </div>
            </div>
        </div>

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
                        <option value="trial"     {{ old('subscription_status', 'trial') === 'trial'     ? 'selected' : '' }}>Trial</option>
                        <option value="active"    {{ old('subscription_status') === 'active'    ? 'selected' : '' }}>Active</option>
                        <option value="expired"   {{ old('subscription_status') === 'expired'   ? 'selected' : '' }}>Expired</option>
                        <option value="cancelled" {{ old('subscription_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Expires At</label>
                    <input type="date" name="subscription_ends_at" value="{{ old('subscription_ends_at') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all text-gray-700">
                    <p class="text-xs text-gray-400 mt-1.5">Leave empty for no expiry</p>
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
                            <option value="{{ $theme->id }}" {{ old('theme_id') == $theme->id ? 'selected' : '' }}>
                                {{ $theme->name }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3.5 rounded-2xl text-sm shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Create Tenant
        </button>
    </div>

</form>

<script>
// Auto-generate subdomain from name
document.querySelector('[name=name]').addEventListener('input', function () {
    const slug = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-');
    document.getElementById('subdomain-input').value = slug;
});
</script>

@endsection
