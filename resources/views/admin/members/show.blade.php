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
<a href="{{ route('admin.members.edit', $member) }}"
   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white adm-btn-primary shadow-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
    </svg>
    Edit
</a>
@endsection

@section('content')

@php
    $statusInfo   = \App\Models\Member::STATUSES[$member->status] ?? ['label' => $member->status, 'color' => '#475569', 'bg' => '#f1f5f9'];
    $notes        = $member->activities->where('type', 'note');
    $allActivities = $member->activities;
@endphp

<div x-data="{ tab: window.location.hash.replace('#','') || 'overview' }" class="space-y-5">

    {{-- ── Profile Header Card ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        {{-- Top gradient strip --}}
        <div class="h-20 bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 relative">
            <div class="absolute inset-0 opacity-20"
                 style="background-image: radial-gradient(circle, rgba(255,255,255,.15) 1px, transparent 1px); background-size: 20px 20px;"></div>
        </div>

        <div class="px-6 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-10 mb-4">
                {{-- Avatar --}}
                <div class="w-20 h-20 rounded-2xl ring-4 ring-white flex-shrink-0 flex items-center justify-center text-2xl font-bold text-white shadow-lg"
                     style="background: linear-gradient(135deg, {{ $statusInfo['color'] }}cc, {{ $statusInfo['color'] }})">
                    @if($member->photo_url)
                        <img src="{{ $member->photo_url }}" class="w-full h-full object-cover rounded-2xl" alt="{{ $member->full_name }}">
                    @else
                        {{ $member->initials }}
                    @endif
                </div>

                {{-- Name + status --}}
                <div class="flex-1 min-w-0 pt-2 sm:pt-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h2 class="text-xl font-bold text-slate-800">{{ $member->full_name }}</h2>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                              style="background-color: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['color'] }}">
                            {{ $statusInfo['label'] }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-3 text-sm text-slate-500">
                        @if($member->email)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $member->email }}
                            </span>
                        @endif
                        @if($member->phone)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $member->phone }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Member since {{ $member->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <form method="POST" action="{{ route('admin.members.destroy', $member) }}"
                          onsubmit="return confirm('Delete {{ addslashes($member->full_name) }}? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            {{-- Groups & Tags preview --}}
            @if($member->groups->isNotEmpty() || $member->tags->isNotEmpty())
            <div class="flex flex-wrap gap-1.5 mt-1">
                @foreach($member->groups as $group)
                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full font-medium text-white"
                          style="background-color: {{ $group->color }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $group->name }}
                    </span>
                @endforeach
                @foreach($member->tags as $tag)
                    <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full font-medium border"
                          style="border-color: {{ $tag->color }}; color: {{ $tag->color }}; background-color: {{ $tag->color }}18">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- ── Tab Navigation ───────────────────────────────────────────────────── --}}
    <div class="flex gap-1 bg-white rounded-xl border border-slate-100 shadow-sm p-1.5">
        <button @click="tab = 'overview'; history.replaceState(null, '', '#overview')"
                :class="tab === 'overview' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                class="flex-1 sm:flex-none px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-150">
            Overview
        </button>
        <button @click="tab = 'activity'; history.replaceState(null, '', '#activity')"
                :class="tab === 'activity' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                class="flex-1 sm:flex-none px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-150 flex items-center justify-center gap-2">
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
                class="flex-1 sm:flex-none px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-150 flex items-center justify-center gap-2">
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

        {{-- Left column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Personal Information --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-5 py-4 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">Personal Information</h3>
                    <a href="{{ route('admin.members.edit', $member) }}" class="text-xs text-indigo-600 hover:underline font-medium">Edit</a>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">First Name</p>
                        <p class="text-sm text-slate-800 font-medium">{{ $member->first_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Last Name</p>
                        <p class="text-sm text-slate-800 font-medium">{{ $member->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Email</p>
                        <p class="text-sm text-slate-800">{{ $member->email ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Phone</p>
                        <p class="text-sm text-slate-800">{{ $member->phone ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Birthday</p>
                        <p class="text-sm text-slate-800">{{ $member->birthday ? $member->birthday->format('F j, Y') : '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Status</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                              style="background-color: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['color'] }}">
                            {{ $statusInfo['label'] }}
                        </span>
                    </div>
                    @if($member->address)
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Address</p>
                        <p class="text-sm text-slate-800">{{ $member->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Custom Fields --}}
            @if($customFields->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-5 py-4 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">Additional Information</h3>
                    <a href="{{ route('admin.members.edit', $member) }}" class="text-xs text-indigo-600 hover:underline font-medium">Edit</a>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($customFields as $field)
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">{{ $field->label }}</p>
                        <p class="text-sm text-slate-800">
                            @php $val = $member->getCustomValue($field->id); @endphp
                            @if($val !== null && $val !== '')
                                @if($field->type === 'date')
                                    {{ \Carbon\Carbon::parse($val)->format('F j, Y') }}
                                @else
                                    {{ $val }}
                                @endif
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Right column --}}
        <div class="space-y-5">

            {{-- Groups --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-5 py-4 border-b border-slate-50">
                    <h3 class="font-bold text-slate-800 text-sm">Groups</h3>
                </div>
                <div class="p-5" x-data="{ editing: false }">
                    <div x-show="!editing">
                        @if($member->groups->isEmpty())
                            <p class="text-sm text-slate-400 mb-3">Not in any group yet.</p>
                        @else
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                @foreach($member->groups as $group)
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full font-medium text-white"
                                          style="background-color: {{ $group->color }}">
                                        {{ $group->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        <button @click="editing = true" class="text-xs text-indigo-600 hover:underline font-medium">
                            {{ $member->groups->isEmpty() ? 'Assign groups' : 'Edit groups' }}
                        </button>
                    </div>
                    <div x-show="editing" x-cloak>
                        <form method="POST" action="{{ route('admin.members.sync-groups', $member) }}">
                            @csrf
                            <div class="space-y-2 mb-3">
                                @foreach($groups as $group)
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 rounded-lg p-1.5 -mx-1.5">
                                    <input type="checkbox" name="groups[]" value="{{ $group->id }}"
                                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                           {{ $member->groups->contains($group->id) ? 'checked' : '' }}>
                                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $group->color }}"></span>
                                    <span class="text-sm text-slate-700">{{ $group->name }}</span>
                                </label>
                                @endforeach
                            </div>
                            @if($groups->isEmpty())
                                <p class="text-xs text-slate-400 mb-2">No groups yet. <a href="{{ route('admin.groups.index') }}" class="text-indigo-600 hover:underline">Create one</a>.</p>
                            @endif
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-1.5 text-xs font-semibold text-white rounded-lg adm-btn-primary">Save</button>
                                <button type="button" @click="editing = false" class="flex-1 py-1.5 text-xs text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Tags --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-5 py-4 border-b border-slate-50">
                    <h3 class="font-bold text-slate-800 text-sm">Tags</h3>
                </div>
                <div class="p-5">
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        @foreach($tags as $tag)
                            @php $hasTag = $member->tags->contains($tag->id); @endphp
                            <form method="POST" action="{{ route('admin.members.toggle-tag', [$member, $tag]) }}">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center text-xs px-2.5 py-1 rounded-full font-medium border transition-all"
                                        style="{{ $hasTag
                                            ? 'border-color: '.$tag->color.'; color: white; background-color: '.$tag->color.';'
                                            : 'border-color: '.$tag->color.'55; color: '.$tag->color.'; background-color: '.$tag->color.'12;' }}"
                                        title="{{ $hasTag ? 'Remove tag' : 'Add tag' }}">
                                    @if($hasTag)
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                    {{ $tag->name }}
                                </button>
                            </form>
                        @endforeach
                        @if($tags->isEmpty())
                            <p class="text-xs text-slate-400">No tags yet. <a href="{{ route('admin.groups.index') }}" class="text-indigo-600 hover:underline">Create one</a>.</p>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400">Click a tag to toggle it on or off.</p>
                </div>
            </div>

        </div>
    </div>

    {{-- ══ TAB: ACTIVITY ══════════════════════════════════════════════════════ --}}
    <div x-show="tab === 'activity'" x-cloak>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-sm">Activity Timeline</h3>
                <button @click="tab = 'notes'; history.replaceState(null, '', '#notes')"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Note
                </button>
            </div>
            <div class="p-5">
                @if($allActivities->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-sm text-slate-400">No activity recorded yet.</p>
                    </div>
                @else
                    <div class="relative">
                        <div class="absolute left-5 top-0 bottom-0 w-px bg-slate-100"></div>
                        <div class="space-y-4">
                            @foreach($allActivities as $activity)
                            @php
                                $icon   = match($activity->type) {
                                    'note'          => ['path' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'bg' => 'bg-slate-100', 'color' => 'text-slate-500'],
                                    'status_change' => ['path' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'bg' => 'bg-blue-100', 'color' => 'text-blue-600'],
                                    'attendance'    => ['path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'bg' => 'bg-emerald-100', 'color' => 'text-emerald-600'],
                                    default         => ['path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-indigo-100', 'color' => 'text-indigo-600'],
                                };
                            @endphp
                            <div class="flex gap-4 pl-0 relative">
                                <div class="w-10 h-10 rounded-full {{ $icon['bg'] }} {{ $icon['color'] }} flex items-center justify-center flex-shrink-0 z-10 ring-4 ring-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon['path'] }}"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 pt-1.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="text-sm text-slate-700">{{ $activity->content }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">
                                                {{ $activity->user?->name ?? 'System' }} · {{ $activity->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        @if($activity->type === 'note')
                                        <form method="POST" action="{{ route('admin.members.activities.destroy', $activity) }}"
                                              onsubmit="return confirm('Delete this note?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors flex-shrink-0">
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
    <div x-show="tab === 'notes'" x-cloak class="space-y-4">

        {{-- Add note form --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Add a Note</h3>
            </div>
            <form method="POST" action="{{ route('admin.members.notes.store', $member) }}" class="p-5">
                @csrf
                <textarea name="content" rows="3" required maxlength="2000"
                          placeholder="Write a note about this member…"
                          class="w-full text-sm border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent resize-none bg-slate-50 placeholder-slate-400">{{ old('content') }}</textarea>
                <div class="flex items-center justify-between mt-3">
                    <p class="text-xs text-slate-400">Notes are private to your team.</p>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-xl adm-btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Save Note
                    </button>
                </div>
            </form>
        </div>

        {{-- Notes list --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Notes ({{ $notes->count() }})</h3>
            </div>
            @if($notes->isEmpty())
                <div class="p-8 text-center">
                    <p class="text-sm text-slate-400">No notes yet. Add the first one above.</p>
                </div>
            @else
                <div class="divide-y divide-slate-50">
                    @foreach($notes as $note)
                    <div class="px-5 py-4 group">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5">
                                    {{ strtoupper(substr($note->user?->name ?? 'S', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-semibold text-slate-700">{{ $note->user?->name ?? 'System' }}</span>
                                        <span class="text-xs text-slate-400">{{ $note->created_at->format('M d, Y \a\t g:i A') }}</span>
                                        <span class="text-xs text-slate-400">· {{ $note->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $note->content }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.members.activities.destroy', $note) }}"
                                  onsubmit="return confirm('Delete this note?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors opacity-0 group-hover:opacity-100 flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
