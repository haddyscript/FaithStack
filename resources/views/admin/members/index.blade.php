@extends('admin.layouts.app')

@section('title', 'Members')
@section('heading', 'Members')

@section('header-actions')
<a href="{{ route('admin.members.create') }}"
   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white adm-btn-primary shadow-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
    </svg>
    Add Member
</a>
@endsection

@section('content')

@php
    $statuses = \App\Models\Member::STATUSES;
    $allIds   = $members->pluck('id')->toArray();
@endphp

{{-- ── Stats bar ─────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    @foreach($statuses as $key => $st)
    @php
        $count = \App\Models\Member::forTenant($tenant->id)->where('status', $key)->count();
    @endphp
    <a href="{{ route('admin.members.index', ['status' => $key]) }}"
       class="bg-white rounded-xl border border-slate-100 shadow-sm px-4 py-3 flex items-center gap-3 hover:shadow-md transition-shadow group">
        <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $st['color'] }}"></div>
        <div class="min-w-0">
            <p class="text-xl font-bold text-slate-800">{{ $count }}</p>
            <p class="text-xs text-slate-500 truncate">{{ $st['label'] }}</p>
        </div>
    </a>
    @endforeach
</div>

{{-- ── Main card ─────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden"
     x-data="{
         selected: [],
         allIds: {{ json_encode($allIds) }},
         bulkAction: '',
         bulkGroupId: '',
         bulkTagId: '',
         showDeleteConfirm: false,
         showGroupModal: false,
         showTagModal: false,
         toggleAll(checked) { this.selected = checked ? [...this.allIds] : []; },
         isSelected(id) { return this.selected.includes(id); },
         toggle(id) {
             const idx = this.selected.indexOf(id);
             idx === -1 ? this.selected.push(id) : this.selected.splice(idx, 1);
         },
         submitBulk(action, extra = {}) {
             this.bulkAction = action;
             Object.assign(this, extra);
             this.$nextTick(() => document.getElementById('bulk-form').submit());
         }
     }">

    {{-- Hidden bulk form --}}
    <form id="bulk-form" method="POST" action="{{ route('admin.members.bulk') }}">
        @csrf
        <input type="hidden" name="action" x-bind:value="bulkAction">
        <input type="hidden" name="group_id" x-bind:value="bulkGroupId">
        <input type="hidden" name="tag_id" x-bind:value="bulkTagId">
        <template x-for="id in selected" :key="id">
            <input type="hidden" name="member_ids[]" :value="id">
        </template>
    </form>

    {{-- ── Search & Filter bar ──────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('admin.members.index') }}" class="p-4 border-b border-slate-100 flex flex-wrap gap-3 items-center">
        {{-- Search --}}
        <div class="relative flex-1 min-w-48">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search name, email, phone…"
                   class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50">
        </div>

        {{-- Status filter --}}
        <select name="status" class="text-sm border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">All Statuses</option>
            @foreach($statuses as $key => $st)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $st['label'] }}</option>
            @endforeach
        </select>

        {{-- Group filter --}}
        <select name="group_id" class="text-sm border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">All Groups</option>
            @foreach($groups as $group)
                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
            @endforeach
        </select>

        {{-- Tag filter --}}
        <select name="tag_id" class="text-sm border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">All Tags</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white rounded-xl adm-btn-primary">Filter</button>

        @if(request()->hasAny(['search', 'status', 'group_id', 'tag_id']))
            <a href="{{ route('admin.members.index') }}" class="text-sm text-slate-500 hover:text-slate-800 px-2 py-2">Clear</a>
        @endif

        {{-- Right side actions --}}
        <div class="ml-auto flex items-center gap-2">
            <a href="{{ route('admin.member-fields.index') }}"
               class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-indigo-600 border border-slate-200 rounded-xl px-3 py-2 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 7a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Custom Fields
            </a>
            <a href="{{ route('admin.groups.index') }}"
               class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-indigo-600 border border-slate-200 rounded-xl px-3 py-2 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Groups & Tags
            </a>
        </div>
    </form>

    {{-- ── Bulk action bar ──────────────────────────────────────────────── --}}
    <div x-show="selected.length > 0" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="px-4 py-3 bg-indigo-50 border-b border-indigo-100 flex items-center gap-3 flex-wrap">
        <span class="text-sm font-semibold text-indigo-700" x-text="selected.length + ' member(s) selected'"></span>
        <div class="flex items-center gap-2 ml-auto flex-wrap">
            {{-- Assign Group --}}
            <div x-show="showGroupModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl p-6 w-80" @click.stop>
                    <h3 class="font-bold text-slate-800 mb-3">Assign to Group</h3>
                    <select x-model="bulkGroupId" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Select a group…</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <button @click="showGroupModal = false" class="flex-1 px-4 py-2 text-sm text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                        <button @click="if(bulkGroupId) { submitBulk('assign_group') } " class="flex-1 px-4 py-2 text-sm font-semibold text-white rounded-xl adm-btn-primary">Apply</button>
                    </div>
                </div>
            </div>

            {{-- Add Tag --}}
            <div x-show="showTagModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl p-6 w-80" @click.stop>
                    <h3 class="font-bold text-slate-800 mb-3">Add Tag</h3>
                    <select x-model="bulkTagId" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Select a tag…</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <button @click="showTagModal = false" class="flex-1 px-4 py-2 text-sm text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                        <button @click="if(bulkTagId) { submitBulk('add_tag') }" class="flex-1 px-4 py-2 text-sm font-semibold text-white rounded-xl adm-btn-primary">Apply</button>
                    </div>
                </div>
            </div>

            {{-- Delete Confirm --}}
            <div x-show="showDeleteConfirm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl p-6 w-80">
                    <h3 class="font-bold text-slate-800 mb-1">Delete Members?</h3>
                    <p class="text-sm text-slate-500 mb-4" x-text="'This will permanently delete ' + selected.length + ' member(s). This cannot be undone.'"></p>
                    <div class="flex gap-2">
                        <button @click="showDeleteConfirm = false" class="flex-1 px-4 py-2 text-sm text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                        <button @click="submitBulk('delete')" class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700">Delete</button>
                    </div>
                </div>
            </div>

            <button @click="showGroupModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-white border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Assign Group
            </button>
            <button @click="showTagModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-violet-700 bg-white border border-violet-200 rounded-lg hover:bg-violet-50 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Add Tag
            </button>
            <button @click="submitBulk('prepare_message')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-50 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Prepare Message
            </button>
            <button @click="showDeleteConfirm = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete
            </button>
            <button @click="selected = []" class="text-xs text-slate-500 hover:text-slate-800 px-2 py-1.5">✕ Clear</button>
        </div>
    </div>

    {{-- ── Table ────────────────────────────────────────────────────────── --}}
    @if($members->isEmpty())
        <div class="p-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-slate-700 mb-1">No members found</h3>
            <p class="text-sm text-slate-400 mb-4">
                @if(request()->hasAny(['search', 'status', 'group_id', 'tag_id']))
                    Try adjusting your search or filters.
                @else
                    Start building your member directory.
                @endif
            </p>
            @unless(request()->hasAny(['search', 'status', 'group_id', 'tag_id']))
                <a href="{{ route('admin.members.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-xl adm-btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add First Member
                </a>
            @endunless
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="w-10 px-4 py-3">
                            <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                   @change="toggleAll($event.target.checked)"
                                   :checked="selected.length === allIds.length && allIds.length > 0">
                        </th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide">Member</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide hidden md:table-cell">Contact</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide">Status</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide hidden lg:table-cell">Groups & Tags</th>
                        <th class="text-left px-4 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide hidden xl:table-cell">Joined</th>
                        <th class="w-24 px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($members as $member)
                    @php
                        $statusInfo = \App\Models\Member::STATUSES[$member->status] ?? ['label' => $member->status, 'color' => '#475569', 'bg' => '#f1f5f9'];
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors" :class="isSelected({{ $member->id }}) ? 'bg-indigo-50/40' : ''">
                        <td class="px-4 py-3">
                            <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                   :checked="isSelected({{ $member->id }})"
                                   @change="toggle({{ $member->id }})">
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.members.show', $member) }}" class="flex items-center gap-3 group">
                                <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold text-white"
                                     style="background: linear-gradient(135deg, {{ $statusInfo['color'] }}cc, {{ $statusInfo['color'] }})">
                                    {{ $member->initials }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $member->full_name }}</p>
                                    @if($member->email)
                                        <p class="text-xs text-slate-400 truncate md:hidden">{{ $member->email }}</p>
                                    @endif
                                </div>
                            </a>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            @if($member->email)
                                <p class="text-slate-600 text-xs">{{ $member->email }}</p>
                            @endif
                            @if($member->phone)
                                <p class="text-slate-400 text-xs">{{ $member->phone }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                  style="background-color: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['color'] }}">
                                {{ $statusInfo['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <div class="flex flex-wrap gap-1">
                                @foreach($member->groups->take(2) as $group)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full font-medium text-white"
                                          style="background-color: {{ $group->color }}">
                                        {{ $group->name }}
                                    </span>
                                @endforeach
                                @if($member->groups->count() > 2)
                                    <span class="text-xs text-slate-400">+{{ $member->groups->count() - 2 }}</span>
                                @endif
                                @foreach($member->tags->take(2) as $tag)
                                    <span class="inline-flex items-center text-xs px-2 py-0.5 rounded-full font-medium border"
                                          style="border-color: {{ $tag->color }}; color: {{ $tag->color }}; background-color: {{ $tag->color }}18">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                                @if($member->tags->count() > 2)
                                    <span class="text-xs text-slate-400">+{{ $member->tags->count() - 2 }}</span>
                                @endif
                                @if($member->groups->isEmpty() && $member->tags->isEmpty())
                                    <span class="text-xs text-slate-300">—</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-400 hidden xl:table-cell">
                            {{ $member->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1 justify-end">
                                <a href="{{ route('admin.members.show', $member) }}"
                                   class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.members.edit', $member) }}"
                                   class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.members.destroy', $member) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($member->full_name) }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">
                {{ $members->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
