@extends('admin.layouts.app')

@section('title', $member->full_name)
@section('heading', $member->full_name)

@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Members',   'url' => route('admin.members.index')],
        ['label' => $member->full_name],
    ]"/>
@endsection

@section('header-actions')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.members.index') }}"
       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-medium text-slate-600 border border-slate-200 hover:bg-slate-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Members
    </a>
    <a href="{{ route('admin.members.edit', $member) }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white adm-btn-primary shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit Profile
    </a>
</div>
@endsection

@section('content')

@php
    $statusInfo    = \App\Models\Member::STATUSES[$member->status] ?? ['label' => $member->status, 'color' => '#475569', 'bg' => '#f1f5f9'];
    $notes         = $member->activities->where('type', 'note');
    $allActivities = $member->activities;
@endphp

<div x-data="{ tab: window.location.hash.replace('#','') || 'overview' }" class="space-y-5">

    {{-- ══ PROFILE HEADER ══════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        {{-- Cover banner + avatar wrapper (position:relative so avatar can escape bottom) --}}
        <div class="relative">

            {{-- Cover strip --}}
            <div class="h-28"
                 style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4f46e5 70%, #7c3aed 100%)">
                {{-- Dot mesh --}}
                <div class="absolute inset-x-0 top-0 h-28"
                     style="background-image: radial-gradient(circle, rgba(255,255,255,.07) 1px, transparent 1px); background-size: 20px 20px;"></div>
                {{-- Decorative blobs --}}
                <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full pointer-events-none"
                     style="background: radial-gradient(circle, rgba(139,92,246,.3), transparent 65%)"></div>
                <div class="absolute top-2 left-1/3 w-36 h-36 rounded-full pointer-events-none"
                     style="background: radial-gradient(circle, rgba(99,102,241,.18), transparent 65%)"></div>
            </div>

            {{-- Avatar — absolutely centred on the cover/content seam --}}
            <div class="absolute left-6 bottom-0 translate-y-1/2 z-10">
                <div class="w-20 h-20 rounded-2xl ring-4 ring-white flex items-center justify-center text-xl font-bold text-white shadow-lg"
                     style="background: linear-gradient(135deg, {{ $statusInfo['color'] }}cc, {{ $statusInfo['color'] }})">
                    @if($member->photo_url)
                        <img src="{{ $member->photo_url }}" class="w-full h-full object-cover rounded-2xl" alt="{{ $member->full_name }}">
                    @else
                        {{ $member->initials }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Profile content — pt-14 gives space for the half-avatar that hangs below --}}
        <div class="px-6 pt-14 pb-5">

            {{-- Name row --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2.5 mb-1.5">
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $member->full_name }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                              style="background-color: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['color'] }}">
                            {{ $statusInfo['label'] }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-500">
                        @if($member->email)
                            <a href="mailto:{{ $member->email }}" class="flex items-center gap-1.5 hover:text-indigo-600 transition-colors">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $member->email }}
                            </a>
                        @endif
                        @if($member->phone)
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $member->phone }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Member since {{ $member->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>

                {{-- Danger action --}}
                <div class="flex-shrink-0">
                    <form method="POST" action="{{ route('admin.members.destroy', $member) }}"
                          onsubmit="return confirm('Delete {{ addslashes($member->full_name) }}? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-red-600 border border-red-200 rounded-xl transition-all duration-150 hover:bg-red-600 hover:text-white hover:border-red-600 hover:shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete Member
                        </button>
                    </form>
                </div>
            </div>

            {{-- Groups & Tags chips --}}
            @if($member->groups->isNotEmpty() || $member->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 pt-3 border-t border-slate-50">
                @foreach($member->groups as $group)
                    <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-semibold text-white shadow-sm"
                          style="background-color: {{ $group->color }}">
                        <svg class="w-3 h-3 opacity-75" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $group->name }}
                    </span>
                @endforeach
                @foreach($member->tags as $tag)
                    <span class="inline-flex items-center text-xs px-3 py-1.5 rounded-full font-semibold border"
                          style="border-color: {{ $tag->color }}; color: {{ $tag->color }}; background-color: {{ $tag->color }}1a">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- ── Tab Navigation ───────────────────────────────────────────────────── --}}
    <div class="flex gap-1 bg-white rounded-2xl border border-slate-100 shadow-sm p-1.5">
        <button @click="tab = 'overview'; history.replaceState(null, '', '#overview')"
                :class="tab === 'overview' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                class="flex-1 sm:flex-none px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-150 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Overview
        </button>
        <button @click="tab = 'activity'; history.replaceState(null, '', '#activity')"
                :class="tab === 'activity' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                class="flex-1 sm:flex-none px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-150 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Activity
            @if($allActivities->count())
                <span class="text-xs px-1.5 py-0.5 rounded-full font-bold"
                      :class="tab === 'activity' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
                    {{ $allActivities->count() }}
                </span>
            @endif
        </button>
        <button @click="tab = 'notes'; history.replaceState(null, '', '#notes')"
                :class="tab === 'notes' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                class="flex-1 sm:flex-none px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-150 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Notes
            @if($notes->count())
                <span class="text-xs px-1.5 py-0.5 rounded-full font-bold"
                      :class="tab === 'notes' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
                    {{ $notes->count() }}
                </span>
            @endif
        </button>
    </div>

    {{-- ══ TAB: OVERVIEW ══════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'overview'" x-cloak class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left / main column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Personal Information --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800">Personal Information</h3>
                    </div>
                    <a href="{{ route('admin.members.edit', $member) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 px-2.5 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-0 divide-y sm:divide-y-0 sm:divide-x-0">

                        {{-- Email --}}
                        <div class="flex items-start gap-3.5 py-4 sm:py-0 sm:pb-5">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-0.5">Email</p>
                                @if($member->email)
                                    <a href="mailto:{{ $member->email }}" class="text-sm font-medium text-slate-800 hover:text-indigo-600 transition-colors truncate block">{{ $member->email }}</a>
                                @else
                                    <p class="text-sm text-slate-300">Not provided</p>
                                @endif
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="flex items-start gap-3.5 py-4 sm:py-0 sm:pb-5 sm:pl-5">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-0.5">Phone</p>
                                <p class="text-sm font-medium text-slate-800">{{ $member->phone ?: '—' }}</p>
                            </div>
                        </div>

                        {{-- Birthday --}}
                        <div class="flex items-start gap-3.5 py-4 sm:py-0 sm:pt-5 sm:border-t sm:border-slate-50">
                            <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6l3-3 3 3M9 6h6M3 9h18"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-0.5">Birthday</p>
                                <p class="text-sm font-medium text-slate-800">{{ $member->birthday ? $member->birthday->format('F j, Y') : '—' }}</p>
                                @if($member->birthday)
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $member->birthday->age }} years old</p>
                                @endif
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="flex items-start gap-3.5 py-4 sm:py-0 sm:pt-5 sm:border-t sm:border-slate-50 sm:pl-5">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                                 style="background-color: {{ $statusInfo['bg'] }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                     style="color: {{ $statusInfo['color'] }}"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Status</p>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                      style="background-color: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['color'] }}">
                                    {{ $statusInfo['label'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Address (full width if present) --}}
                        @if($member->address)
                        <div class="sm:col-span-2 flex items-start gap-3.5 py-4 sm:py-0 sm:pt-5 sm:border-t sm:border-slate-50 sm:col-span-2">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-0.5">Address</p>
                                <p class="text-sm font-medium text-slate-800">{{ $member->address }}</p>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Custom Fields --}}
            @if($customFields->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 7a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800">Additional Information</h3>
                    </div>
                    <a href="{{ route('admin.members.edit', $member) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 px-2.5 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach($customFields as $field)
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">{{ $field->label }}</p>
                        @php $val = $member->getCustomValue($field->id); @endphp
                        @if($val !== null && $val !== '')
                            <p class="text-sm font-medium text-slate-800">
                                @if($field->type === 'date')
                                    {{ \Carbon\Carbon::parse($val)->format('F j, Y') }}
                                @else
                                    {{ $val }}
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-slate-300">—</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Right / sidebar column --}}
        <div class="space-y-5">

            {{-- Quick Stats --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">Quick Stats</h3>
                </div>
                <div class="p-5 grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-slate-800">{{ $allActivities->count() }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Activities</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-slate-800">{{ $notes->count() }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Notes</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-slate-800">{{ $member->groups->count() }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Groups</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-slate-800">{{ $member->tags->count() }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Tags</p>
                    </div>
                </div>
            </div>

            {{-- Groups --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" x-data="{ editing: false }">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800 text-sm">Groups</h3>
                    </div>
                    <button @click="editing = !editing"
                            :class="editing ? 'text-slate-500 hover:text-slate-700' : 'text-indigo-600 hover:text-indigo-800'"
                            class="text-xs font-semibold px-2 py-1 rounded-lg hover:bg-slate-50 transition-colors">
                        <span x-show="!editing">Edit</span>
                        <span x-show="editing" x-cloak>Done</span>
                    </button>
                </div>
                <div class="p-5">
                    <div x-show="!editing">
                        @if($member->groups->isEmpty())
                            <p class="text-sm text-slate-400 mb-3">Not in any group yet.</p>
                        @else
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($member->groups as $group)
                                    <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-semibold text-white shadow-sm transition-all"
                                          style="background-color: {{ $group->color }}">
                                        <svg class="w-3 h-3 opacity-75" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $group->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if($member->groups->isEmpty())
                            <button @click="editing = true"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 px-2.5 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Assign groups
                            </button>
                        @endif
                    </div>
                    <div x-show="editing" x-cloak>
                        <form method="POST" action="{{ route('admin.members.sync-groups', $member) }}">
                            @csrf
                            <div class="space-y-1.5 mb-4">
                                @foreach($groups as $group)
                                <label class="flex items-center gap-2.5 cursor-pointer hover:bg-slate-50 rounded-xl px-3 py-2 -mx-3 transition-colors">
                                    <input type="checkbox" name="groups[]" value="{{ $group->id }}"
                                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                           {{ $member->groups->contains($group->id) ? 'checked' : '' }}>
                                    <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $group->color }}"></span>
                                    <span class="text-sm font-medium text-slate-700">{{ $group->name }}</span>
                                </label>
                                @endforeach
                            </div>
                            @if($groups->isEmpty())
                                <p class="text-xs text-slate-400 mb-3">No groups yet. <a href="{{ route('admin.groups.index') }}" class="text-indigo-600 hover:underline">Create one</a>.</p>
                            @endif
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2 text-xs font-semibold text-white rounded-xl adm-btn-primary">Save</button>
                                <button type="button" @click="editing = false" class="flex-1 py-2 text-xs text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Tags --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-violet-50 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">Tags</h3>
                </div>
                <div class="p-5">
                    @if($tags->isEmpty())
                        <p class="text-sm text-slate-400">No tags yet. <a href="{{ route('admin.groups.index') }}" class="text-indigo-600 hover:underline font-medium">Create one</a>.</p>
                    @else
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach($tags as $tag)
                                @php $hasTag = $member->tags->contains($tag->id); @endphp
                                <form method="POST" action="{{ route('admin.members.toggle-tag', [$member, $tag]) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-semibold border transition-all duration-150 hover:shadow-sm"
                                            style="{{ $hasTag
                                                ? 'border-color: '.$tag->color.'; color: white; background-color: '.$tag->color.';'
                                                : 'border-color: '.$tag->color.'55; color: '.$tag->color.'; background-color: '.$tag->color.'0f;' }}"
                                            title="{{ $hasTag ? 'Remove tag' : 'Add tag' }}">
                                        @if($hasTag)
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                        {{ $tag->name }}
                                    </button>
                                </form>
                            @endforeach
                        </div>
                        <p class="text-xs text-slate-400">Click to toggle tags on or off.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ══ TAB: ACTIVITY ══════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'activity'" x-cloak>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-800">Activity Timeline</h3>
                </div>
                <button @click="tab = 'notes'; history.replaceState(null, '', '#notes')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 border border-indigo-200 rounded-xl hover:bg-indigo-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Note
                </button>
            </div>
            <div class="p-6">
                @if($allActivities->isEmpty())
                    <div class="text-center py-10">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-500">No activity recorded yet</p>
                        <p class="text-xs text-slate-400 mt-1">Activity is recorded automatically as you make changes.</p>
                    </div>
                @else
                    <div class="relative">
                        <div class="absolute left-[18px] top-3 bottom-3 w-px bg-gradient-to-b from-slate-200 via-slate-100 to-transparent"></div>
                        <div class="space-y-5">
                            @foreach($allActivities as $activity)
                            @php
                                $icon = match($activity->type) {
                                    'note'          => ['path' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'bg' => 'bg-slate-100', 'color' => 'text-slate-500', 'ring' => 'ring-slate-50'],
                                    'status_change' => ['path' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'bg' => 'bg-blue-100', 'color' => 'text-blue-600', 'ring' => 'ring-blue-50'],
                                    'attendance'    => ['path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'bg' => 'bg-emerald-100', 'color' => 'text-emerald-600', 'ring' => 'ring-emerald-50'],
                                    default         => ['path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-indigo-100', 'color' => 'text-indigo-600', 'ring' => 'ring-indigo-50'],
                                };
                            @endphp
                            <div class="flex gap-4 relative">
                                <div class="w-9 h-9 rounded-full {{ $icon['bg'] }} {{ $icon['color'] }} flex items-center justify-center flex-shrink-0 z-10 ring-4 {{ $icon['ring'] }} shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon['path'] }}"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 bg-slate-50/60 rounded-xl px-4 py-3 border border-slate-100">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-slate-700 leading-relaxed">{{ $activity->content }}</p>
                                            <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1.5">
                                                <span class="font-medium text-slate-500">{{ $activity->user?->name ?? 'System' }}</span>
                                                <span>·</span>
                                                <span>{{ $activity->created_at->diffForHumans() }}</span>
                                            </p>
                                        </div>
                                        @if($activity->type === 'note')
                                        <form method="POST" action="{{ route('admin.members.activities.destroy', $activity) }}"
                                              onsubmit="return confirm('Delete this note?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="p-1.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors flex-shrink-0">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ TAB: NOTES ═════════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'notes'" x-cloak class="space-y-5">

        {{-- Add note form --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="font-bold text-slate-800">Add a Note</h3>
            </div>
            <form method="POST" action="{{ route('admin.members.notes.store', $member) }}" class="p-6">
                @csrf
                <textarea name="content" rows="3" required maxlength="2000"
                          placeholder="Write a note about {{ $member->first_name }}…"
                          class="w-full text-sm border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent resize-none bg-slate-50 placeholder-slate-400 transition-colors">{{ old('content') }}</textarea>
                <div class="flex items-center justify-between mt-3">
                    <p class="text-xs text-slate-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Private to your team
                    </p>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-xl adm-btn-primary shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Save Note
                    </button>
                </div>
            </form>
        </div>

        {{-- Notes list --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="font-bold text-slate-800">Notes
                    @if($notes->count())
                        <span class="ml-1.5 text-xs font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ $notes->count() }}</span>
                    @endif
                </h3>
            </div>
            @if($notes->isEmpty())
                <div class="p-10 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-slate-500">No notes yet</p>
                    <p class="text-xs text-slate-400 mt-1">Add the first note using the form above.</p>
                </div>
            @else
                <div class="divide-y divide-slate-50">
                    @foreach($notes as $note)
                    <div class="px-6 py-5 group hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3.5 flex-1 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 mt-0.5 shadow-sm">
                                    {{ strtoupper(substr($note->user?->name ?? 'S', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 mb-2">
                                        <span class="text-sm font-semibold text-slate-800">{{ $note->user?->name ?? 'System' }}</span>
                                        <span class="text-xs text-slate-400">{{ $note->created_at->format('M d, Y \a\t g:i A') }}</span>
                                        <span class="text-xs text-slate-300">·</span>
                                        <span class="text-xs text-slate-400">{{ $note->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $note->content }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.members.activities.destroy', $note) }}"
                                  onsubmit="return confirm('Delete this note?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all opacity-0 group-hover:opacity-100 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
