@extends('admin.layouts.app')

@section('title', 'Pages')
@section('heading', 'Pages')
@section('breadcrumbs')
    <x-breadcrumb :items="[['label'=>'Dashboard','url'=>route('admin.dashboard')],['label'=>'Pages']]" />
@endsection

@section('header-actions')
    <a href="{{ route('admin.pages.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-colors duration-150">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Page
    </a>
@endsection

@section('content')

{{-- Delete confirmation modal --}}
<x-admin.modal id="delete-page" title="Delete Page" size="sm">
    <p>Are you sure you want to delete <strong x-text="$store.deletePage.title" class="text-gray-900"></strong>? This action cannot be undone.</p>
    <x-slot name="footer">
        <button @click="$dispatch('close-modal', { id: 'delete-page' })"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
            Cancel
        </button>
        <form :action="$store.deletePage.action" method="POST" x-ref="deleteForm">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors">
                Delete Page
            </button>
        </form>
    </x-slot>
</x-admin.modal>

{{-- Alpine store for delete modal --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('deletePage', { title: '', action: '' });
});
</script>

@if($pages->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-400 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-gray-900 mb-1">No pages yet</h3>
        <p class="text-sm text-gray-500 mb-5">Create your first page to get your site up and running.</p>
        <a href="{{ route('admin.pages.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Create First Page
        </a>
    </div>
@else
    {{-- Search / filter bar --}}
    <div x-data="{ search: '', filter: 'all' }" class="space-y-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input x-model="search" type="text" placeholder="Search pages…"
                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 bg-white placeholder-gray-400 transition-all">
            </div>
            <div class="flex gap-2">
                <button @click="filter = 'all'"
                    :class="filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all">All</button>
                <button @click="filter = 'published'"
                    :class="filter === 'published' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-green-300'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all">Published</button>
                <button @click="filter = 'draft'"
                    :class="filter === 'draft' ? 'bg-gray-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-gray-400'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all">Drafts</button>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/60">
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">URL</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Sections</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pages as $page)
                        <tr
                            x-show="
                                (filter === 'all' || (filter === 'published' && {{ $page->is_published ? 'true' : 'false' }}) || (filter === 'draft' && {{ !$page->is_published ? 'true' : 'false' }}))
                                && (search === '' || '{{ strtolower($page->title) }}'.includes(search.toLowerCase()) || '{{ strtolower($page->slug) }}'.includes(search.toLowerCase()))
                            "
                            class="hover:bg-gray-50/80 transition-colors group"
                        >
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-400 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $page->title }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 hidden sm:table-cell">
                                <a href="{{ route('page.show', $page->slug) }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-gray-400 hover:text-indigo-600 font-mono text-xs transition-colors group/link">
                                    /{{ $page->slug }}
                                    <svg class="w-3 h-3 opacity-0 group-hover/link:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                @if($page->is_published)
                                    <x-admin.badge color="green" :dot="true">Published</x-admin.badge>
                                @else
                                    <x-admin.badge color="gray" :dot="true">Draft</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold">
                                    {{ count($page->getSections()) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.pages.edit', $page) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <button
                                        @click="
                                            $store.deletePage.title = '{{ addslashes($page->title) }}';
                                            $store.deletePage.action = '{{ route('admin.pages.destroy', $page) }}';
                                            $dispatch('open-modal', { id: 'delete-page' });
                                        "
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Empty search state --}}
            <div x-show="{{ $pages->count() > 0 ? 'true' : 'false' }}"
                 class="hidden py-8 text-center text-sm text-gray-400">
                No pages match your search.
            </div>
        </div>

        {{-- Count summary --}}
        <p class="text-xs text-gray-400">
            Showing {{ $pages->count() }} page{{ $pages->count() !== 1 ? 's' : '' }}
        </p>
    </div>
@endif
@endsection
