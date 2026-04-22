@extends('admin.layouts.app')

@section('title', 'Custom Fields')
@section('heading', 'Custom Fields')

@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Members',   'url' => route('admin.members.index')],
        ['label' => 'Custom Fields'],
    ]"/>
@endsection

@section('content')

<div x-data="{
    modal: false,
    editField: null,
    openCreate() { this.editField = null; this.modal = true; },
    openEdit(f)  { this.editField = f;    this.modal = true; },
}" class="space-y-6">

    {{-- ── Header info ────────────────────────────────────────────────────── --}}
    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5 flex gap-4">
        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 7a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-indigo-800 text-sm mb-0.5">What are Custom Fields?</h3>
            <p class="text-sm text-indigo-700/80">Add custom fields to capture organization-specific information for each member — like Baptism Date, Volunteer Status, or Spiritual Gift. Values are stored per member profile.</p>
        </div>
    </div>

    {{-- ── Fields list ─────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-slate-800">Custom Fields</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ $fields->count() }} field{{ $fields->count() !== 1 ? 's' : '' }} defined</p>
            </div>
            <button @click="openCreate()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white adm-btn-primary shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Field
            </button>
        </div>

        @if($fields->isEmpty())
            <div class="p-12 text-center">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-700 mb-1">No custom fields yet</h3>
                <p class="text-sm text-slate-400 mb-4">Add fields to capture organization-specific information about your members.</p>
                <button @click="openCreate()"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-xl adm-btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add First Field
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide">Label</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide">Type</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide hidden sm:table-cell">Options</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600 text-xs uppercase tracking-wide hidden md:table-cell">Order</th>
                            <th class="w-24 px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($fields as $field)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-slate-800">{{ $field->label }}</p>
                                <p class="text-xs text-slate-400 font-mono">{{ $field->name }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                @php
                                    $typeColors = [
                                        'text'   => ['bg' => '#eff6ff', 'color' => '#2563eb', 'label' => 'Text'],
                                        'number' => ['bg' => '#f0fdf4', 'color' => '#16a34a', 'label' => 'Number'],
                                        'date'   => ['bg' => '#fdf4ff', 'color' => '#9333ea', 'label' => 'Date'],
                                        'select' => ['bg' => '#fff7ed', 'color' => '#ea580c', 'label' => 'Select'],
                                    ];
                                    $tc = $typeColors[$field->type] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => $field->type];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                      style="background-color: {{ $tc['bg'] }}; color: {{ $tc['color'] }}">
                                    {{ $tc['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 hidden sm:table-cell">
                                @if($field->type === 'select' && $field->options)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($field->options, 0, 3) as $opt)
                                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $opt }}</span>
                                        @endforeach
                                        @if(count($field->options) > 3)
                                            <span class="text-xs text-slate-400">+{{ count($field->options) - 3 }} more</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-400 hidden md:table-cell text-xs">
                                #{{ $field->sort_order }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-1 justify-end">
                                    <button @click="openEdit({{ json_encode(['id' => $field->id, 'label' => $field->label, 'type' => $field->type, 'options' => $field->options ? implode("\n", $field->options) : '']) }})"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.member-fields.destroy', $field) }}"
                                          onsubmit="return confirm('Delete field \'{{ addslashes($field->label) }}\'? Existing values will be lost.')">
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

    {{-- ══ FIELD MODAL ══════════════════════════════════════════════════════════ --}}
    <div x-show="modal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
         @click.self="modal = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100">

            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800" x-text="editField ? 'Edit Custom Field' : 'New Custom Field'"></h3>
                <button @click="modal = false" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Create form --}}
            <form x-show="!editField" method="POST" action="{{ route('admin.member-fields.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Field Label <span class="text-red-400">*</span></label>
                    <input type="text" name="label" required
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50"
                           placeholder="e.g. Baptism Date">
                    <p class="text-xs text-slate-400 mt-1">The label shown on the member profile.</p>
                </div>
                <div x-data="{ type: 'text' }">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Field Type <span class="text-red-400">*</span></label>
                    <select name="type" x-model="type"
                            class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="select">Select (dropdown)</option>
                    </select>
                    <div x-show="type === 'select'" x-cloak class="mt-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Options (one per line)</label>
                        <textarea name="options" rows="4"
                                  class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50 resize-none font-mono"
                                  placeholder="Option A&#10;Option B&#10;Option C"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 py-2.5 text-sm font-semibold text-white rounded-xl adm-btn-primary">Create Field</button>
                    <button type="button" @click="modal = false" class="flex-1 py-2.5 text-sm text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                </div>
            </form>

            {{-- Edit form --}}
            <template x-if="editField">
                <form method="POST" :action="`{{ url('admin/member-fields') }}/${editField.id}`" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Field Label <span class="text-red-400">*</span></label>
                        <input type="text" name="label" :value="editField.label" required
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50">
                    </div>
                    <div x-data="{ type: editField.type }">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Field Type <span class="text-red-400">*</span></label>
                        <select name="type" x-model="type"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50">
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>
                            <option value="select">Select (dropdown)</option>
                        </select>
                        <div x-show="type === 'select'" x-cloak class="mt-3">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Options (one per line)</label>
                            <textarea name="options" rows="4"
                                      class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50 resize-none font-mono"
                                      x-text="editField.options"></textarea>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 py-2.5 text-sm font-semibold text-white rounded-xl adm-btn-primary">Save Changes</button>
                        <button type="button" @click="modal = false" class="flex-1 py-2.5 text-sm text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
