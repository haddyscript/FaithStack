@extends('admin.layouts.app')

@section('title', 'Navigation')
@section('heading', 'Navigation Menu')

@section('content')

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

@if(session('success'))
<div class="mb-5 flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm font-medium">
    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ── Menu Builder ────────────────────────────────────────────────── --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Menu Items</h3>
                    <p class="text-xs text-slate-400">Drag to reorder · drag right to nest</p>
                </div>
            </div>
            <span class="text-xs bg-slate-100 text-slate-500 rounded-full px-2.5 py-1 font-medium" id="item-count">
                {{ $navItems->count() }} {{ Str::plural('item', $navItems->count()) }}
            </span>
        </div>

        {{-- Sortable list --}}
        <div class="flex-1 p-4" id="sortable-wrapper">
            @if($navItems->isEmpty())
            <div id="empty-state" class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-500">No menu items yet</p>
                <p class="text-xs text-slate-400 mt-1">Add your first item using the form →</p>
            </div>
            @endif

            <ul id="nav-sortable" class="space-y-1.5 min-h-[2rem]">
                @foreach($navItems->sortBy('order') as $item)
                @php $isChild = !empty($item->parent_id); @endphp
                <li class="nav-item group relative"
                    data-id="{{ $item->id }}"
                    data-name="{{ $item->name }}"
                    data-url="{{ $item->url }}"
                    data-parent="{{ $item->parent_id ?? '' }}"
                    style="{{ $isChild ? 'margin-left:2.5rem;' : '' }}">

                    {{-- View mode --}}
                    <div class="nav-view flex items-center gap-3 px-3 py-2.5 rounded-xl border border-slate-200 bg-white
                                hover:border-slate-300 hover:shadow-sm transition-all duration-150 group-hover:bg-slate-50/50"
                         id="view-{{ $item->id }}">

                        {{-- Drag handle --}}
                        <span class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-slate-500 flex-shrink-0 transition-colors" title="Drag to reorder">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8.5 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 4.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 4.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm7-9a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 4.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 4.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                        </span>

                        @if($isChild)
                        <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0 -ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17l9.293-9.293"/></svg>
                        @endif

                        {{-- Label + URL --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $item->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $item->url }}</p>
                        </div>

                        {{-- Type badge --}}
                        @if(str_starts_with($item->url, 'http'))
                        <span class="hidden sm:inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-100 rounded-full px-2 py-0.5">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            External
                        </span>
                        @else
                        <span class="hidden sm:inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-full px-2 py-0.5">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                            Internal
                        </span>
                        @endif

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                            <button type="button" onclick="openEdit({{ $item->id }})"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('admin.navigation.destroy', $item) }}"
                                  onsubmit="return confirm('Remove \'{{ addslashes($item->name) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Remove">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Edit mode (hidden by default) --}}
                    <form method="POST" action="{{ route('admin.navigation.update', $item) }}"
                          class="nav-edit hidden mt-1" id="edit-{{ $item->id }}">
                        @csrf @method('PUT')
                        <div class="rounded-xl border-2 border-indigo-300 bg-indigo-50/40 p-3 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 uppercase tracking-wide mb-1">Label</label>
                                    <input type="text" name="name" value="{{ $item->name }}" required
                                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 uppercase tracking-wide mb-1">URL</label>
                                    <input type="text" name="url" value="{{ $item->url }}" required
                                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1">
                                    <label class="block text-[11px] font-semibold text-slate-600 uppercase tracking-wide mb-1">Parent item</label>
                                    <select name="parent_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                        <option value="">— Top level —</option>
                                        @foreach($navItems as $other)
                                            @if($other->id !== $item->id && empty($other->parent_id))
                                            <option value="{{ $other->id }}" {{ $item->parent_id == $other->id ? 'selected' : '' }}>{{ $other->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 pt-5">
                                    <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                        Save
                                    </button>
                                    <button type="button" onclick="closeEdit({{ $item->id }})"
                                            class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-semibold rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Save order footer --}}
        <div class="border-t border-slate-100 px-6 py-3 flex items-center justify-between bg-slate-50/60">
            <p class="text-xs text-slate-400" id="order-status">Drag items to reorder</p>
            <button type="button" id="save-order-btn" onclick="saveOrder()" disabled
                    class="hidden items-center gap-1.5 text-xs font-semibold px-3.5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Save Order
            </button>
        </div>
    </div>

    {{-- ── Add Item Form ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-800">Add Menu Item</h3>
                <p class="text-xs text-slate-400">Appended to end of menu</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.navigation.store') }}" class="px-6 py-5 space-y-4"
              x-data="{
                  linkType: 'internal',
                  url: '',
                  setPage(slug) { this.url = '/' + slug; }
              }">
            @csrf

            {{-- Label --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Label <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="e.g. About Us"
                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent hover:border-slate-300 transition-all">
            </div>

            {{-- Link type toggle --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Link Type</label>
                <div class="flex rounded-xl border border-slate-200 overflow-hidden">
                    <label class="flex-1 text-center">
                        <input type="radio" x-model="linkType" value="internal" class="sr-only">
                        <span class="block py-2 text-xs font-semibold cursor-pointer transition-all"
                              :class="linkType === 'internal' ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:bg-slate-50'">
                            Internal Page
                        </span>
                    </label>
                    <label class="flex-1 border-l border-slate-200 text-center">
                        <input type="radio" x-model="linkType" value="external" class="sr-only">
                        <span class="block py-2 text-xs font-semibold cursor-pointer transition-all"
                              :class="linkType === 'external' ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:bg-slate-50'">
                            External URL
                        </span>
                    </label>
                </div>
            </div>

            {{-- Internal: page selector --}}
            <div x-show="linkType === 'internal'" x-cloak>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Page</label>
                @if($pages->isNotEmpty())
                <select @change="setPage($event.target.value)"
                        class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    <option value="">— Select a page —</option>
                    @foreach($pages as $page)
                    <option value="{{ $page->slug }}">{{ $page->title }}</option>
                    @endforeach
                    <option value="">— Custom path —</option>
                </select>
                @else
                <p class="text-xs text-slate-400 bg-slate-50 rounded-xl px-3.5 py-3 border border-slate-200">No pages found. You can still type a custom path below.</p>
                @endif
            </div>

            {{-- URL field --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">
                    <span x-text="linkType === 'internal' ? 'Path (auto-filled above)' : 'Full URL'"></span>
                    <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                    </span>
                    <input type="text" name="url" x-model="url" required
                           :placeholder="linkType === 'internal' ? '/about' : 'https://example.com'"
                           class="w-full border border-slate-200 rounded-xl pl-9 pr-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent hover:border-slate-300 transition-all">
                </div>
            </div>

            {{-- Parent item --}}
            @if($navItems->isNotEmpty())
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Nest under (optional)</label>
                <select name="parent_id"
                        class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    <option value="">— Top level —</option>
                    @foreach($navItems->whereNull('parent_id') as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white font-semibold px-5 py-3 rounded-xl text-sm transition-all shadow-sm shadow-indigo-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add to Menu
            </button>
        </form>

        {{-- Preview --}}
        @if($navItems->isNotEmpty())
        <div class="border-t border-slate-100 px-6 py-4 bg-slate-50/60">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide mb-2">Menu Preview</p>
            <div class="flex flex-wrap gap-2">
                @foreach($navItems->whereNull('parent_id')->sortBy('order') as $item)
                <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 bg-white border border-slate-200 rounded-lg px-2.5 py-1 shadow-sm">
                    {{ $item->name }}
                    @if($navItems->where('parent_id', $item->id)->isNotEmpty())
                    <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    @endif
                </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.sortable-ghost {
    opacity: 0.35;
    background: #e0e7ff !important;
    border-color: #6366f1 !important;
    border-style: dashed !important;
}
.sortable-drag {
    opacity: 0.95;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    transform: rotate(1deg);
}
.drag-handle { touch-action: none; }
</style>

<script>
// ── Edit mode helpers ────────────────────────────────────────────────────
function openEdit(id) {
    document.getElementById('view-' + id).classList.add('hidden');
    document.getElementById('edit-' + id).classList.remove('hidden');
}
function closeEdit(id) {
    document.getElementById('edit-' + id).classList.add('hidden');
    document.getElementById('view-' + id).classList.remove('hidden');
}

// ── SortableJS setup ─────────────────────────────────────────────────────
var orderChanged = false;

var sortable = Sortable.create(document.getElementById('nav-sortable'), {
    handle: '.drag-handle',
    animation: 180,
    ghostClass: 'sortable-ghost',
    dragClass: 'sortable-drag',
    onEnd: function (evt) {
        var item = evt.item;
        var nextSibling = item.nextElementSibling;
        var prevSibling = item.previousElementSibling;

        // Detect nesting: if dragged more than 60px right of a previous sibling, nest it
        var itemRect = item.getBoundingClientRect();
        var listRect = document.getElementById('nav-sortable').getBoundingClientRect();
        var offsetX  = itemRect.left - listRect.left;

        if (offsetX > 60 && prevSibling) {
            var parentId = prevSibling.dataset.id;
            item.dataset.parent = parentId;
            item.style.marginLeft = '2.5rem';
        } else if (offsetX <= 20) {
            item.dataset.parent = '';
            item.style.marginLeft = '0';
        }

        markOrderChanged();
    },
});

function markOrderChanged() {
    orderChanged = true;
    var btn = document.getElementById('save-order-btn');
    btn.classList.remove('hidden');
    btn.classList.add('flex');
    btn.disabled = false;
    document.getElementById('order-status').textContent = 'Unsaved changes…';
    document.getElementById('order-status').classList.add('text-amber-500');
}

function saveOrder() {
    var els = document.querySelectorAll('#nav-sortable .nav-item');
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var btn = document.getElementById('save-order-btn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Saving…';

    var requests = Array.from(els).map(function (el, index) {
        var id = el.dataset.id;
        var body = new URLSearchParams({
            _method:   'PUT',
            _token:    csrf,
            name:      el.dataset.name,
            url:       el.dataset.url,
            order:     index,
            parent_id: el.dataset.parent || '',
        });
        return fetch('/admin/navigation/' + id, {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body:    body.toString(),
        });
    });

    Promise.all(requests)
        .then(function () {
            orderChanged = false;
            var status = document.getElementById('order-status');
            status.textContent = 'Order saved ✓';
            status.classList.remove('text-amber-500');
            status.classList.add('text-emerald-600');
            btn.classList.remove('flex');
            btn.classList.add('hidden');
            document.getElementById('item-count').textContent =
                els.length + ' ' + (els.length === 1 ? 'item' : 'items');
            setTimeout(function () {
                status.textContent = 'Drag items to reorder';
                status.classList.remove('text-emerald-600');
            }, 2500);
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = 'Retry';
            document.getElementById('order-status').textContent = 'Save failed — try again.';
        });
}

// Warn on page leave if unsaved
window.addEventListener('beforeunload', function (e) {
    if (orderChanged) { e.preventDefault(); e.returnValue = ''; }
});
</script>

@endsection
