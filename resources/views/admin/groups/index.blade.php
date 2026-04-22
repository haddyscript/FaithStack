@extends('admin.layouts.app')

@section('title', 'Groups & Tags')
@section('heading', 'Groups & Tags')

@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Members',   'url' => route('admin.members.index')],
        ['label' => 'Groups & Tags'],
    ]"/>
@endsection

@section('content')

<div x-data="{
    groupModal: false,
    tagModal: false,
    editGroup: null,
    editTag: null,
    openCreateGroup() { this.editGroup = null; this.groupModal = true; },
    openEditGroup(g)  { this.editGroup = g;    this.groupModal = true; },
    openCreateTag()   { this.editTag = null;   this.tagModal   = true; },
    openEditTag(t)    { this.editTag = t;      this.tagModal   = true; },
}" class="space-y-6">

    {{-- ══ GROUPS ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-slate-800">Groups</h2>
                <p class="text-xs text-slate-400 mt-0.5">Organize members into ministries, teams, or small groups.</p>
            </div>
            <button @click="openCreateGroup()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white adm-btn-primary shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Group
            </button>
        </div>

        @if($groups->isEmpty())
            <div class="p-10 text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-400">No groups yet. Create your first group to organize members.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide">Group</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide hidden sm:table-cell">Description</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide">Members</th>
                            <th class="w-28 px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($groups as $group)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white font-bold text-xs"
                                         style="background-color: {{ $group->color }}">
                                        {{ strtoupper(substr($group->name, 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $group->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 hidden sm:table-cell">
                                {{ $group->description ?: '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.members.index', ['group_id' => $group->id]) }}"
                                   class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                    {{ $group->members_count }}
                                    <span class="text-xs font-normal text-slate-400">member{{ $group->members_count !== 1 ? 's' : '' }}</span>
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-1 justify-end">
                                    <button @click="openEditGroup({{ json_encode(['id' => $group->id, 'name' => $group->name, 'description' => $group->description, 'color' => $group->color]) }})"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.groups.destroy', $group) }}"
                                          onsubmit="return confirm('Delete group \'{{ addslashes($group->name) }}\'? Members will not be deleted.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
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
        @endif
    </div>

    {{-- ══ TAGS ═════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-slate-800">Tags</h2>
                <p class="text-xs text-slate-400 mt-0.5">Lightweight labels for quick segmentation and filtering.</p>
            </div>
            <button @click="openCreateTag()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white adm-btn-primary shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Tag
            </button>
        </div>

        @if($tags->isEmpty())
            <div class="p-10 text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <p class="text-sm text-slate-400">No tags yet. Create tags to label and filter members quickly.</p>
            </div>
        @else
            <div class="p-5">
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($tags as $tag)
                    <div class="flex items-center gap-1 group">
                        <span class="inline-flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-full font-medium border"
                              style="border-color: {{ $tag->color }}55; color: {{ $tag->color }}; background-color: {{ $tag->color }}15">
                            {{ $tag->name }}
                            <span class="text-xs opacity-70">({{ $tag->members_count }})</span>
                        </span>
                        <button @click="openEditTag({{ json_encode(['id' => $tag->id, 'name' => $tag->name, 'color' => $tag->color]) }})"
                                class="p-1 text-slate-300 hover:text-indigo-600 rounded transition-colors opacity-0 group-hover:opacity-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}"
                              onsubmit="return confirm('Delete tag \'{{ addslashes($tag->name) }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1 text-slate-300 hover:text-red-500 rounded transition-colors opacity-0 group-hover:opacity-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- ══ GROUP MODAL ═════════════════════════════════════════════════════════ --}}
    <div x-show="groupModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
         @click.self="groupModal = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100">

            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800" x-text="editGroup ? 'Edit Group' : 'New Group'"></h3>
                <button @click="groupModal = false" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Create form --}}
            <form x-show="!editGroup" method="POST" action="{{ route('admin.groups.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Group Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50"
                           placeholder="e.g. Youth Ministry">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Description</label>
                    <textarea name="description" rows="2"
                              class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50 resize-none"
                              placeholder="Optional description…"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" value="#6366f1"
                               class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                        <span class="text-xs text-slate-400">Pick a color for this group's badge</span>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 py-2.5 text-sm font-semibold text-white rounded-xl adm-btn-primary">Create Group</button>
                    <button type="button" @click="groupModal = false" class="flex-1 py-2.5 text-sm text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                </div>
            </form>

            {{-- Edit form (dynamic action) --}}
            <template x-if="editGroup">
                <form method="POST" :action="`{{ url('admin/groups') }}/${editGroup.id}`" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Group Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" :value="editGroup.name" required
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Description</label>
                        <textarea name="description" rows="2"
                                  class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50 resize-none"
                                  x-text="editGroup.description"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color" :value="editGroup.color"
                                   class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                            <span class="text-xs text-slate-400">Pick a color for this group's badge</span>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 py-2.5 text-sm font-semibold text-white rounded-xl adm-btn-primary">Save Changes</button>
                        <button type="button" @click="groupModal = false" class="flex-1 py-2.5 text-sm text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    {{-- ══ TAG MODAL ════════════════════════════════════════════════════════════ --}}
    <div x-show="tagModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
         @click.self="tagModal = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100">

            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800" x-text="editTag ? 'Edit Tag' : 'New Tag'"></h3>
                <button @click="tagModal = false" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Create tag form --}}
            <form x-show="!editTag" method="POST" action="{{ route('admin.tags.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tag Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50"
                           placeholder="e.g. Volunteer">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" value="#8b5cf6"
                               class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                        <span class="text-xs text-slate-400">Pick a color for this tag</span>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 py-2.5 text-sm font-semibold text-white rounded-xl adm-btn-primary">Create Tag</button>
                    <button type="button" @click="tagModal = false" class="flex-1 py-2.5 text-sm text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                </div>
            </form>

            {{-- Edit tag form --}}
            <template x-if="editTag">
                <form method="POST" :action="`{{ url('admin/tags') }}/${editTag.id}`" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tag Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" :value="editTag.name" required
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color" :value="editTag.color"
                                   class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                            <span class="text-xs text-slate-400">Pick a color for this tag</span>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 py-2.5 text-sm font-semibold text-white rounded-xl adm-btn-primary">Save Changes</button>
                        <button type="button" @click="tagModal = false" class="flex-1 py-2.5 text-sm text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
