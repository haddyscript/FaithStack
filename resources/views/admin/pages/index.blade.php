@extends('admin.layouts.app')

@section('title', 'Pages')
@section('heading', 'Pages')
@section('breadcrumbs')
    <x-breadcrumb :items="[['label'=>'Dashboard','url'=>route('admin.dashboard')],['label'=>'Pages']]" />
@endsection

@section('header-actions')
    <a href="{{ route('admin.pages.create') }}"
       class="inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all active:scale-95"
       style="background: var(--adm-pri, #6366f1);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Page
    </a>
@endsection

@section('content')

{{-- ── Alpine store ──────────────────────────────────────────────────────── --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('deletePage', { title: '', action: '' });
});
</script>

@php
/* Pre-build section-type summary per page for PHP → JS handoff */
$sectionIcons = [
    'hero'       => ['color' => '#8b5cf6', 'label' => 'Hero'],
    'text'       => ['color' => '#3b82f6', 'label' => 'Text'],
    'image_text' => ['color' => '#10b981', 'label' => 'Image+Text'],
    'gallery'    => ['color' => '#f59e0b', 'label' => 'Gallery'],
    'cta'        => ['color' => '#ef4444', 'label' => 'CTA'],
    'footer'     => ['color' => '#6b7280', 'label' => 'Footer'],
    'features'   => ['color' => '#0ea5e9', 'label' => 'Features'],
    'contact'    => ['color' => '#ec4899', 'label' => 'Contact'],
];
@endphp

<div
    x-data="{
        search: '',
        filter: 'all',
        sortBy: 'updated',
        sortDir: 'desc',
        selected: [],
        quickViewPage: null,
        quickViewOpen: false,

        /* Pages data for Alpine — injected from PHP */
        pages: {{ Js::from($pages->map(fn($p) => [
            'id'          => $p->id,
            'title'       => $p->title,
            'slug'        => $p->slug,
            'is_published'=> $p->is_published,
            'sections'    => $p->getSections(),
            'section_count'=> count($p->getSections()),
            'updated_at'  => $p->updated_at->toISOString(),
            'created_at'  => $p->created_at->toISOString(),
            'edit_url'    => route('admin.pages.edit', $p),
            'delete_url'  => route('admin.pages.destroy', $p),
            'public_url'  => route('page.show', $p->slug),
            'toggle_url'  => route('admin.pages.update', $p),
        ])) }},

        get filtered() {
            let list = this.pages.filter(p => {
                let matchFilter = this.filter === 'all'
                    || (this.filter === 'published' && p.is_published)
                    || (this.filter === 'draft' && !p.is_published);
                let q = this.search.toLowerCase();
                let matchSearch = !q
                    || p.title.toLowerCase().includes(q)
                    || p.slug.toLowerCase().includes(q);
                return matchFilter && matchSearch;
            });
            list.sort((a, b) => {
                let aVal = this.sortBy === 'title' ? a.title : (this.sortBy === 'sections' ? a.section_count : a[this.sortBy + '_at']);
                let bVal = this.sortBy === 'title' ? b.title : (this.sortBy === 'sections' ? b.section_count : b[this.sortBy + '_at']);
                if (this.sortBy === 'title') return this.sortDir === 'asc' ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
                return this.sortDir === 'asc' ? (aVal > bVal ? 1 : -1) : (aVal < bVal ? 1 : -1);
            });
            return list;
        },

        get allSelected() { return this.filtered.length > 0 && this.filtered.every(p => this.selected.includes(p.id)); },
        toggleAll() { this.allSelected ? this.selected = [] : this.selected = this.filtered.map(p => p.id); },
        toggleSort(col) { this.sortDir = this.sortBy === col && this.sortDir === 'asc' ? 'desc' : 'asc'; this.sortBy = col; },

        openQuickView(page) { this.quickViewPage = page; this.quickViewOpen = true; },

        toggleStatus(page) {
            let csrf = document.querySelector('meta[name=csrf-token]').getAttribute('content');
            let body = new URLSearchParams({ _method: 'PUT', _token: csrf, title: page.title, slug: page.slug, is_published: page.is_published ? '0' : '1' });
            fetch(page.toggle_url, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString() })
                .then(r => { if (r.ok || r.redirected) { let idx = this.pages.findIndex(p => p.id === page.id); if (idx >= 0) this.pages[idx].is_published = !page.is_published; } });
        },

        bulkDelete() {
            if (!this.selected.length || !confirm('Delete ' + this.selected.length + ' page(s)? This cannot be undone.')) return;
            let csrf = document.querySelector('meta[name=csrf-token]').getAttribute('content');
            let ids  = [...this.selected];
            Promise.all(ids.map(id => {
                let p    = this.pages.find(p => p.id === id);
                let body = new URLSearchParams({ _method: 'DELETE', _token: csrf });
                return fetch(p.delete_url, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString() })
                    .then(r => { if (r.ok || r.redirected) this.pages = this.pages.filter(p => p.id !== id); });
            })).then(() => { this.selected = []; });
        },

        bulkPublish(state) {
            let csrf = document.querySelector('meta[name=csrf-token]').getAttribute('content');
            this.selected.forEach(id => {
                let p    = this.pages.find(p => p.id === id);
                let body = new URLSearchParams({ _method: 'PUT', _token: csrf, title: p.title, slug: p.slug, is_published: state ? '1' : '0' });
                fetch(p.toggle_url, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString() })
                    .then(r => { if (r.ok || r.redirected) { let idx = this.pages.findIndex(x => x.id === id); if (idx >= 0) this.pages[idx].is_published = state; } });
            });
            this.selected = [];
        },

        sectionColor(type) {
            let map = { hero: '#8b5cf6', text: '#3b82f6', image_text: '#10b981', gallery: '#f59e0b', cta: '#ef4444', footer: '#6b7280', features: '#0ea5e9', contact: '#ec4899' };
            return map[type] || '#94a3b8';
        },
        sectionLabel(type) {
            let map = { hero: 'Hero', text: 'Text', image_text: 'Img+Text', gallery: 'Gallery', cta: 'CTA', footer: 'Footer', features: 'Features', contact: 'Contact' };
            return map[type] || type;
        },
        formatDate(iso) {
            let d = new Date(iso);
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        },
        initKeyboard() {
            document.addEventListener('keydown', e => {
                if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                    document.getElementById('page-search').focus();
                }
                if (e.key === 'Escape') { this.quickViewOpen = false; document.getElementById('page-search').blur(); }
            });
        }
    }"
    x-init="initKeyboard()"
>

{{-- ── Delete confirmation modal --}}
<x-admin.modal id="delete-page" title="Delete Page" size="sm">
    <p>Are you sure you want to delete <strong x-text="$store.deletePage.title" class="text-gray-900"></strong>? This action cannot be undone.</p>
    <x-slot name="footer">
        <button @click="$dispatch('close-modal', { id: 'delete-page' })"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
            Cancel
        </button>
        <form :action="$store.deletePage.action" method="POST">
            @csrf @method('DELETE')
            <button type="submit"
                class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors">
                Delete Page
            </button>
        </form>
    </x-slot>
</x-admin.modal>

{{-- ── Quick View slide-over ─────────────────────────────────────────────── --}}
<div x-show="quickViewOpen" x-cloak class="fixed inset-0 z-50 flex justify-end"
     @keydown.escape.window="quickViewOpen = false">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="quickViewOpen = false"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    {{-- Panel --}}
    <div class="relative w-full max-w-md bg-white h-full shadow-2xl flex flex-col overflow-hidden"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

        <template x-if="quickViewPage">
            <div class="flex flex-col h-full">
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-slate-100 flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold rounded-full px-2 py-0.5"
                                  :class="quickViewPage.is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'">
                                <span class="w-1.5 h-1.5 rounded-full inline-block" :class="quickViewPage.is_published ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                <span x-text="quickViewPage.is_published ? 'Published' : 'Draft'"></span>
                            </span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900 truncate" x-text="quickViewPage.title"></h2>
                        <a :href="quickViewPage.public_url" target="_blank"
                           class="text-xs text-slate-400 hover:text-indigo-600 font-mono transition-colors inline-flex items-center gap-1">
                            <span x-text="'/' + quickViewPage.slug"></span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    <button @click="quickViewOpen = false" class="p-2 rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700 flex-shrink-0 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto px-6 py-5 space-y-6">

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 mb-1">Sections</p>
                            <p class="text-2xl font-bold text-slate-800" x-text="quickViewPage.sections.length"></p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-400 mb-1">Last Updated</p>
                            <p class="text-sm font-semibold text-slate-800" x-text="formatDate(quickViewPage.updated_at)"></p>
                        </div>
                    </div>

                    {{-- Sections breakdown --}}
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Sections</p>
                        <template x-if="quickViewPage.sections.length === 0">
                            <p class="text-sm text-slate-400 italic">No sections on this page.</p>
                        </template>
                        <div class="space-y-2">
                            <template x-for="(sec, i) in quickViewPage.sections" :key="i">
                                <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 bg-white">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white text-xs font-bold"
                                         :style="'background:' + sectionColor(sec.type)">
                                        <span x-text="(i+1)"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-700" x-text="sectionLabel(sec.type)"></p>
                                        <p class="text-xs text-slate-400 truncate" x-text="sec.title || sec.heading || sec.button_text || '—'"></p>
                                    </div>
                                    <span class="ml-auto text-[10px] font-mono text-slate-300" x-text="sec.type"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- SEO check --}}
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Content Check</p>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs"
                                 :class="quickViewPage.title.length >= 10 ? 'text-emerald-600' : 'text-amber-500'">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                     x-show="quickViewPage.title.length >= 10"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                     x-show="quickViewPage.title.length < 10"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                Page title length
                                <span class="ml-auto font-mono" x-text="quickViewPage.title.length + ' chars'"></span>
                            </div>
                            <div class="flex items-center gap-2 text-xs"
                                 :class="quickViewPage.sections.length > 0 ? 'text-emerald-600' : 'text-amber-500'">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                     x-show="quickViewPage.sections.length > 0"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                     x-show="quickViewPage.sections.length === 0"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                Has sections
                            </div>
                            <div class="flex items-center gap-2 text-xs"
                                 :class="quickViewPage.sections.some(s => s.type === 'hero') ? 'text-emerald-600' : 'text-slate-400'">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                     x-show="quickViewPage.sections.some(s => s.type === 'hero')"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <svg class="w-3.5 h-3.5 shrink-0 opacity-30" fill="currentColor" viewBox="0 0 20 20"
                                     x-show="!quickViewPage.sections.some(s => s.type === 'hero')"><circle cx="10" cy="10" r="8"/></svg>
                                Has hero section
                            </div>
                            <div class="flex items-center gap-2 text-xs"
                                 :class="quickViewPage.sections.some(s => s.type === 'cta') ? 'text-emerald-600' : 'text-slate-400'">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                     x-show="quickViewPage.sections.some(s => s.type === 'cta')"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <svg class="w-3.5 h-3.5 shrink-0 opacity-30" fill="currentColor" viewBox="0 0 20 20"
                                     x-show="!quickViewPage.sections.some(s => s.type === 'cta')"><circle cx="10" cy="10" r="8"/></svg>
                                Has CTA section
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer actions --}}
                <div class="border-t border-slate-100 px-6 py-4 flex gap-3">
                    <a :href="quickViewPage.edit_url"
                       class="flex-1 flex items-center justify-center gap-2 text-sm font-semibold text-white py-2.5 rounded-xl transition-all"
                       style="background: var(--adm-pri, #6366f1);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Page
                    </a>
                    <a :href="quickViewPage.public_url" target="_blank"
                       class="px-4 flex items-center gap-1.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        View
                    </a>
                </div>
            </div>
        </template>
    </div>
</div>

{{-- ── Main content ──────────────────────────────────────────────────────── --}}

@if($pages->isEmpty())
{{-- Empty state --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-16 text-center">
    <div class="w-20 h-20 rounded-2xl bg-indigo-50 flex items-center justify-center mx-auto mb-5">
        <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
    </div>
    <h3 class="text-lg font-bold text-slate-800 mb-2">No pages yet</h3>
    <p class="text-sm text-slate-500 mb-6 max-w-sm mx-auto">Pages are the building blocks of your site. Create your first page to start building your public presence.</p>
    <a href="{{ route('admin.pages.create') }}"
       class="inline-flex items-center gap-2 text-white text-sm font-semibold px-6 py-3 rounded-xl shadow-md transition-all active:scale-95"
       style="background: var(--adm-pri, #6366f1);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Create First Page
    </a>
</div>

@else
<div class="space-y-4">

    {{-- ── Toolbar ───────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row gap-3">

        {{-- Search --}}
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input id="page-search" x-model="search" type="text" placeholder="Search pages… (press / to focus)"
                class="w-full pl-10 pr-10 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:border-transparent bg-white placeholder-slate-400 transition-all"
                style="focus-ring-color: var(--adm-pri, #6366f1);"
                @focus="$el.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                @blur="$el.style.boxShadow=''">
            <kbd x-show="!search"
                 class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 bg-slate-100 rounded px-1.5 py-0.5 pointer-events-none">/</kbd>
            <button x-show="search" @click="search = ''"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Filters --}}
        <div class="flex items-center gap-2">
            @foreach(['all' => 'All', 'published' => 'Published', 'draft' => 'Drafts'] as $val => $label)
            <button @click="filter = '{{ $val }}'"
                    :class="filter === '{{ $val }}' ? 'text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300'"
                    :style="filter === '{{ $val }}' ? 'background: var(--adm-pri, #6366f1)' : ''"
                    class="px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all">{{ $label }}</button>
            @endforeach
        </div>

        {{-- Sort --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-sm font-medium bg-white border border-slate-200 hover:border-slate-300 text-slate-600 transition-all">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M6 12h12M10 17h4"/></svg>
                Sort
                <svg class="w-3 h-3 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="open" @click.outside="open = false" x-cloak
                 class="absolute right-0 top-full mt-1.5 w-44 bg-white rounded-xl border border-slate-200 shadow-lg py-1 z-20"
                 x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                @foreach(['updated' => 'Last Updated', 'created' => 'Date Created', 'title' => 'Title A–Z', 'sections' => 'Section Count'] as $col => $lbl)
                <button @click="toggleSort('{{ $col }}'); open = false"
                        class="flex items-center justify-between w-full px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors"
                        :class="sortBy === '{{ $col }}' ? 'font-semibold text-indigo-600' : ''">
                    {{ $lbl }}
                    <svg x-show="sortBy === '{{ $col }}'" class="w-3.5 h-3.5"
                         :class="sortDir === 'asc' ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Bulk action bar ───────────────────────────────────────────────── --}}
    <div x-show="selected.length > 0" x-cloak
         class="flex items-center gap-3 bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3"
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <span class="text-sm font-semibold text-indigo-700" x-text="selected.length + ' page(s) selected'"></span>
        <div class="flex gap-2 ml-auto">
            <button @click="bulkPublish(true)"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Publish All
            </button>
            <button @click="bulkPublish(false)"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Unpublish All
            </button>
            <button @click="bulkDelete()"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete
            </button>
            <button @click="selected = []" class="text-xs text-slate-400 hover:text-slate-600 px-2 transition-colors">Deselect</button>
        </div>
    </div>

    {{-- ── Table ─────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="w-10 px-4 py-3.5">
                        <input type="checkbox" @change="toggleAll()" :checked="allSelected"
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 cursor-pointer">
                    </th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <button @click="toggleSort('title')" class="flex items-center gap-1 hover:text-slate-800 transition-colors">
                            Page
                            <svg class="w-3 h-3 transition-transform" :class="sortBy==='title' && sortDir==='asc' ? 'rotate-180' : ''" :style="sortBy==='title' ? 'opacity:1' : 'opacity:0.3'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                    </th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">
                        <button @click="toggleSort('sections')" class="flex items-center gap-1 hover:text-slate-800 transition-colors">
                            Sections
                            <svg class="w-3 h-3 transition-transform" :class="sortBy==='sections' && sortDir==='asc' ? 'rotate-180' : ''" :style="sortBy==='sections' ? 'opacity:1' : 'opacity:0.3'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                    </th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden xl:table-cell">
                        <button @click="toggleSort('updated')" class="flex items-center gap-1 hover:text-slate-800 transition-colors">
                            Updated
                            <svg class="w-3 h-3 transition-transform" :class="sortBy==='updated' && sortDir==='asc' ? 'rotate-180' : ''" :style="sortBy==='updated' ? 'opacity:1' : 'opacity:0.3'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                    </th>
                    <th class="px-4 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <template x-for="page in filtered" :key="page.id">
                    <tr class="hover:bg-slate-50/70 transition-colors group"
                        :class="selected.includes(page.id) ? 'bg-indigo-50/40' : ''">

                        {{-- Checkbox --}}
                        <td class="px-4 py-4">
                            <input type="checkbox" :value="page.id"
                                   :checked="selected.includes(page.id)"
                                   @change="selected.includes(page.id) ? selected = selected.filter(i => i !== page.id) : selected.push(page.id)"
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 cursor-pointer">
                        </td>

                        {{-- Thumbnail + Title + URL --}}
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                {{-- Generated thumbnail --}}
                                <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center font-bold text-base text-white shadow-sm relative overflow-hidden"
                                     :style="'background: linear-gradient(135deg, var(--adm-pri, #6366f1), var(--adm-acc, #a78bfa))'">
                                    <span x-text="page.title.charAt(0).toUpperCase()"></span>
                                    <div class="absolute bottom-0 inset-x-0 h-1.5 opacity-40"
                                         :style="'background:' + (page.is_published ? '#34d399' : '#94a3b8')"></div>
                                </div>
                                <div class="min-w-0">
                                    <button @click="openQuickView(page)"
                                            class="text-sm font-semibold text-slate-800 hover:text-indigo-600 transition-colors text-left truncate block max-w-[200px]"
                                            x-text="page.title"></button>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="text-xs text-slate-400 font-mono truncate max-w-[140px]" x-text="'/' + page.slug"></span>
                                        <a :href="page.public_url" target="_blank" class="opacity-0 group-hover:opacity-100 transition-opacity text-slate-400 hover:text-indigo-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </div>
                                {{-- Content check warning --}}
                                <template x-if="page.sections.length === 0">
                                    <span class="hidden sm:flex items-center gap-1 text-[10px] font-semibold text-amber-600 bg-amber-50 border border-amber-200 rounded-full px-2 py-0.5">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                        Empty
                                    </span>
                                </template>
                            </div>
                        </td>

                        {{-- Status toggle --}}
                        <td class="px-4 py-3.5">
                            <button @click="toggleStatus(page)" title="Click to toggle"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-full px-3 py-1 transition-all hover:scale-105 active:scale-95 cursor-pointer"
                                    :class="page.is_published
                                        ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100'
                                        : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200'">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                      :class="page.is_published ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                <span x-text="page.is_published ? 'Published' : 'Draft'"></span>
                            </button>
                        </td>

                        {{-- Sections --}}
                        <td class="px-4 py-3.5 hidden lg:table-cell">
                            <template x-if="page.sections.length === 0">
                                <span class="text-xs text-slate-300 italic">None</span>
                            </template>
                            <template x-if="page.sections.length > 0">
                                <div class="flex items-center gap-1 flex-wrap max-w-[180px]">
                                    <template x-for="(sec, i) in page.sections.slice(0, 5)" :key="i">
                                        <span class="text-[10px] font-semibold text-white rounded-md px-1.5 py-0.5"
                                              :style="'background:' + sectionColor(sec.type)"
                                              :title="sectionLabel(sec.type)"
                                              x-text="sectionLabel(sec.type).charAt(0)"></span>
                                    </template>
                                    <span x-show="page.sections.length > 5"
                                          class="text-[10px] text-slate-400 font-medium"
                                          x-text="'+' + (page.sections.length - 5)"></span>
                                    <span class="text-xs text-slate-400 ml-1" x-text="page.sections.length + ' total'"></span>
                                </div>
                            </template>
                        </td>

                        {{-- Updated --}}
                        <td class="px-4 py-3.5 hidden xl:table-cell">
                            <span class="text-xs text-slate-400" x-text="formatDate(page.updated_at)"></span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3.5">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="openQuickView(page)"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 opacity-0 group-hover:opacity-100 transition-all" title="Quick view">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </button>
                                <a :href="page.edit_url"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <button
                                    @click="$store.deletePage.title = page.title; $store.deletePage.action = page.delete_url; $dispatch('open-modal', { id: 'delete-page' })"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>

                {{-- No results state --}}
                <tr x-show="filtered.length === 0">
                    <td colspan="6" class="py-12 text-center">
                        <svg class="w-8 h-8 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z"/></svg>
                        <p class="text-sm font-semibold text-slate-400" x-text="search ? 'No pages match &quot;' + search + '&quot;' : 'No pages in this filter'"></p>
                        <button @click="search = ''; filter = 'all'" class="text-xs text-indigo-500 hover:text-indigo-700 mt-1 transition-colors">Clear filters</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ── Summary ───────────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between text-xs text-slate-400 px-1">
        <span x-text="filtered.length + ' of {{ $pages->count() }} page(s)'"></span>
        <span>{{ $pages->where('is_published', true)->count() }} published · {{ $pages->where('is_published', false)->count() }} draft</span>
    </div>

</div>
@endif

</div>{{-- end x-data --}}

@endsection
