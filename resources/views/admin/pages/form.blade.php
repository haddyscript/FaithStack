@extends('admin.layouts.app')

@section('title', $page->exists ? 'Edit Page' : 'New Page')
@section('heading', $page->exists ? 'Edit Page' : 'New Page')
@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label'=>'Dashboard','url'=>route('admin.dashboard')],
        ['label'=>'Pages','url'=>route('admin.pages.index')],
        ['label'=> $page->exists ? $page->title : 'New Page'],
    ]" />
@endsection

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.pages.index') }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
        @if($page->exists && $page->is_published)
            <a href="{{ route('page.show', $page->slug) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium text-teal-600 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Live Site
            </a>
        @endif
        <button type="button" id="preview-toggle-btn" onclick="togglePreview()"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span id="preview-toggle-label">Preview</span>
        </button>
    </div>
@endsection

@section('content')

@if($errors->any())
    <div class="mb-6">
        <x-admin.alert type="error" title="Please fix the following errors:">
            <ul class="mt-1 list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-admin.alert>
    </div>
@endif

<form method="POST"
      action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
      id="page-form"
      class="space-y-6">
    @csrf
    @if($page->exists) @method('PUT') @endif

    {{-- Two column layout: main + sidebar --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Main column --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Page Details --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-5">Page Details</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Page Title <span class="text-red-400">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $page->title) }}" required
                               id="page-title"
                               placeholder="e.g. About Us, Services, Contact"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all placeholder-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">URL Slug <span class="text-red-400">*</span></label>
                        <div class="flex items-stretch border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500/30 focus-within:border-indigo-400 transition-all">
                            <span class="inline-flex items-center px-3.5 bg-gray-50 text-gray-400 text-sm border-r border-gray-200 select-none">/</span>
                            <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" required
                                   id="page-slug"
                                   placeholder="about-us"
                                   class="flex-1 px-3.5 py-2.5 text-sm font-mono focus:outline-none bg-white placeholder-gray-300">
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5">Lowercase letters, numbers, and hyphens only</p>
                    </div>
                </div>
            </div>

            {{-- Sections Builder --}}
            @php $sections = old('sections', $page->getSections()); @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/40">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Page Sections</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Drag to reorder · Click to expand</p>
                    </div>
                    <span id="sections-count-badge"
                          class="{{ count($sections) > 0 ? '' : 'hidden' }} px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold">
                        {{ count($sections) }} section{{ count($sections) !== 1 ? 's' : '' }}
                    </span>
                </div>

                {{-- Canvas --}}
                <div class="p-4">
                    <div id="sections-container" class="space-y-2.5">
                        @foreach($sections as $i => $section)
                            @include('admin.pages.partials.section-row', ['section' => $section, 'index' => $i])
                        @endforeach
                    </div>

                    {{-- Empty state --}}
                    <div id="sections-empty"
                         class="{{ count($sections) > 0 ? 'hidden' : '' }} py-12 text-center rounded-xl border-2 border-dashed border-gray-200 mt-1">
                        <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-400">No sections yet</p>
                        <p class="text-xs text-gray-300 mt-1">Click a section type below to add your first block</p>
                    </div>
                </div>

                {{-- Add section toolbar --}}
                <div class="px-4 pb-4 pt-2 border-t border-gray-100 bg-gray-50/30">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2.5">Add Section</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach([
                            'hero'       => ['label' => 'Hero',           'icon' => '🏠'],
                            'text'       => ['label' => 'Text',           'icon' => '📝'],
                            'image_text' => ['label' => 'Image + Text',   'icon' => '🖼'],
                            'gallery'    => ['label' => 'Gallery',        'icon' => '🎨'],
                            'cta'        => ['label' => 'Call to Action', 'icon' => '📣'],
                        ] as $type => $meta)
                            <button type="button"
                                    onclick="addSection('{{ $type }}')"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold border border-gray-200 rounded-xl text-gray-600 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all duration-150 active:scale-95">
                                <span>{{ $meta['icon'] }}</span>
                                {{ $meta['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Footer Configuration --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{
                     open: {{ old('footer_enabled', $page->getFooterEnabled()) ? 'true' : 'false' }},
                     enabled: {{ old('footer_enabled', $page->getFooterEnabled()) ? 'true' : 'false' }},
                     content: @js(old('footer_content', $page->getFooterContent())),
                     showPreview: false
                 }">

                {{-- Panel header --}}
                <div class="flex items-center justify-between px-6 py-4 cursor-pointer select-none border-b border-gray-100"
                     :class="open ? 'bg-teal-50/60' : 'bg-white hover:bg-gray-50'"
                     @click="open = !open">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                             :class="enabled ? 'bg-teal-100 text-teal-600' : 'bg-gray-100 text-gray-400'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Footer Configuration</p>
                            <p class="text-xs text-gray-400" x-text="enabled ? 'Footer enabled for this page' : 'No footer on this page'"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span x-show="enabled" class="px-2 py-0.5 rounded-full bg-teal-100 text-teal-700 text-[10px] font-bold uppercase tracking-wider">On</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Panel body --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="p-6 space-y-5">

                        {{-- Info banner --}}
                        <div class="flex items-start gap-2.5 px-4 py-3 rounded-xl bg-amber-50 border border-amber-100">
                            <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs text-amber-700 leading-relaxed">
                                This footer will <strong>only appear on this page</strong> — it won't affect other pages or your theme's global footer.
                            </p>
                        </div>

                        {{-- Enable toggle --}}
                        <label class="flex items-center gap-4 cursor-pointer group">
                            <div class="relative flex-shrink-0">
                                <input type="hidden" name="footer_enabled" value="0">
                                <input type="checkbox" name="footer_enabled" id="footer_enabled" value="1"
                                       x-model="enabled"
                                       @change="if (enabled) open = true"
                                       {{ old('footer_enabled', $page->getFooterEnabled()) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-checked:bg-teal-500 rounded-full transition-colors duration-200"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200 peer-checked:translate-x-5"></div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Enable Footer</p>
                                <p class="text-xs text-gray-400">Show custom footer at the bottom of this page</p>
                            </div>
                        </label>

                        {{-- Content editor (shown only when enabled) --}}
                        <div x-show="enabled"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0">

                            {{-- Toolbar hint --}}
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">Footer Content</label>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-gray-400">Basic HTML supported</span>
                                    <button type="button" @click="showPreview = !showPreview"
                                            class="text-xs font-semibold px-2.5 py-1 rounded-lg transition-colors"
                                            :class="showPreview ? 'bg-teal-100 text-teal-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                                        <span x-text="showPreview ? 'Hide Preview' : 'Show Preview'"></span>
                                    </button>
                                </div>
                            </div>

                            {{-- Quick format bar --}}
                            <div class="flex items-center gap-1 mb-1.5 px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-t-xl border-b-0">
                                @foreach([
                                    ['<strong>B</strong>', '<strong>|</strong>', 'font-bold'],
                                    ['<em>I</em>',        '<em>|</em>',        'italic'],
                                    ['<u>U</u>',          '<u>|</u>',          ''],
                                ] as [$display, $wrap, $cls])
                                    <button type="button"
                                            onclick="wrapSelection(this.dataset.open, this.dataset.close)"
                                            data-open="{{ $wrap === '<strong>|</strong>' ? '<strong>' : ($wrap === '<em>|</em>' ? '<em>' : '<u>') }}"
                                            data-close="{{ $wrap === '<strong>|</strong>' ? '</strong>' : ($wrap === '<em>|</em>' ? '</em>' : '</u>') }}"
                                            class="px-2 py-1 text-xs {{ $cls }} text-gray-600 hover:bg-white hover:shadow-sm rounded-lg transition-all">{!! $display !!}</button>
                                @endforeach
                                <div class="w-px h-4 bg-gray-200 mx-1"></div>
                                <button type="button" onclick="insertTag('footer-content-input', '<a href=&quot;&quot;>', '</a>')"
                                        class="px-2 py-1 text-xs text-gray-600 hover:bg-white hover:shadow-sm rounded-lg transition-all">Link</button>
                                <button type="button" onclick="insertTag('footer-content-input', '<p>', '</p>')"
                                        class="px-2 py-1 text-xs text-gray-600 hover:bg-white hover:shadow-sm rounded-lg transition-all">Para</button>
                                <button type="button" onclick="insertTag('footer-content-input', '<ul>\n  <li>', '</li>\n</ul>')"
                                        class="px-2 py-1 text-xs text-gray-600 hover:bg-white hover:shadow-sm rounded-lg transition-all">List</button>
                                <button type="button" onclick="insertTag('footer-content-input', '<hr>', '')"
                                        class="px-2 py-1 text-xs text-gray-600 hover:bg-white hover:shadow-sm rounded-lg transition-all">HR</button>
                            </div>

                            <textarea name="footer_content"
                                      id="footer-content-input"
                                      rows="5"
                                      x-model="content"
                                      placeholder="<p>© {{ date('Y') }} Your Organization. All rights reserved.</p>&#10;<p>123 Main St · (555) 000-0000 · <a href=&quot;mailto:info@org.com&quot;>info@org.com</a></p>"
                                      class="w-full border border-gray-200 rounded-b-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 transition-all resize-y leading-relaxed">{{ old('footer_content', $page->getFooterContent()) }}</textarea>

                            <p class="text-[11px] text-gray-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Allowed: &lt;p&gt; &lt;strong&gt; &lt;em&gt; &lt;a&gt; &lt;ul&gt; &lt;li&gt; &lt;h2–h5&gt; &lt;br&gt; &lt;hr&gt;
                            </p>

                            {{-- Live preview --}}
                            <div x-show="showPreview"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="mt-4">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Preview</p>
                                <div class="rounded-xl border border-gray-200 overflow-hidden">
                                    {{-- Fake page bottom --}}
                                    <div class="h-8 bg-gradient-to-b from-gray-50 to-gray-100 border-b border-gray-200 flex items-center px-4">
                                        <div class="flex gap-1">
                                            <div class="h-1.5 w-12 rounded bg-gray-300"></div>
                                            <div class="h-1.5 w-8 rounded bg-gray-200"></div>
                                        </div>
                                    </div>
                                    {{-- Rendered footer --}}
                                    <div class="bg-gray-900 text-gray-300 px-6 py-5 text-sm leading-relaxed
                                                [&_a]:text-teal-400 [&_a]:underline [&_a:hover]:text-teal-300
                                                [&_strong]:text-white [&_h2]:text-white [&_h2]:text-base [&_h2]:font-bold [&_h2]:mb-2
                                                [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1
                                                [&_hr]:border-white/10 [&_hr]:my-3
                                                [&_p]:mb-2 [&_p:last-child]:mb-0"
                                         x-html="content || '<p class=\'text-gray-600 text-xs italic\'>Start typing to see preview…</p>'">
                                    </div>
                                    {{-- Mock powered-by bar --}}
                                    <div class="bg-gray-950 px-6 py-2 flex justify-between text-[10px] text-gray-600 border-t border-white/5">
                                        <span>© {{ date('Y') }} — <span class="text-gray-500">{{ $tenant->name ?? 'Your Organization' }}</span></span>
                                        <span>Powered by FaithStack</span>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- /enabled --}}

                    </div>
                </div>{{-- /panel body --}}

            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Publish --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-5">Publish</h3>

                <label class="flex items-center gap-3 cursor-pointer mb-5 group">
                    <div class="relative flex-shrink-0">
                        <input type="checkbox" name="is_published" id="is_published" value="1"
                               {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-checked:bg-indigo-600 rounded-full transition-colors duration-200"></div>
                        <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 group-hover:text-gray-900">Published</p>
                        <p class="text-xs text-gray-400">Visible on your public site</p>
                    </div>
                </label>

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl text-sm shadow-sm transition-colors duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $page->exists ? 'Save Changes' : 'Create Page' }}
                </button>
            </div>

            {{-- Tips --}}
            <div class="bg-indigo-50 rounded-2xl border border-indigo-100 p-5">
                <h3 class="text-xs font-semibold text-indigo-700 uppercase tracking-wider mb-3">Tips</h3>
                <ul class="space-y-2 text-xs text-indigo-600">
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Start with a <strong>Hero</strong> section for your main message.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        End with a <strong>CTA</strong> to encourage action.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Set a page to <strong>Draft</strong> to hide it without deleting.
                    </li>
                </ul>
            </div>
        </div>

    </div>
</form>

{{-- ══════════════════════════════════════════════════
     LIVE PREVIEW PANEL
     Fixed right drawer, slides in on toggle
     ══════════════════════════════════════════════════ --}}
<div id="preview-panel"
     style="position:fixed;top:0;right:0;bottom:0;width:520px;z-index:50;
            display:flex;flex-direction:column;background:#f8fafc;
            border-left:1px solid #e2e8f0;box-shadow:-8px 0 40px rgba(0,0,0,.1);
            transform:translateX(100%);transition:transform .3s cubic-bezier(.4,0,.2,1)">

    {{-- Panel header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;
                padding:12px 16px;background:white;border-bottom:1px solid #e2e8f0;flex-shrink:0">
        <div style="display:flex;align-items:center;gap:8px">
            <span id="preview-pulse"
                  style="display:inline-block;width:8px;height:8px;border-radius:50%;
                         background:#10b981;box-shadow:0 0 0 0 rgba(16,185,129,.4);
                         animation:pulse-ring 2s ease-out infinite"></span>
            <span style="font-size:.8125rem;font-weight:600;color:#1e293b">Live Preview</span>
        </div>

        {{-- Device switcher --}}
        <div style="display:flex;align-items:center;gap:8px">
            <div style="display:flex;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;font-size:.75rem">
                <button type="button" id="btn-device-desktop" onclick="setDevice('desktop')"
                        style="padding:5px 10px;background:#4f46e5;color:white;border:none;cursor:pointer;
                               display:inline-flex;align-items:center;gap:4px;font-size:.75rem;font-weight:500">
                    <svg style="width:12px;height:12px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                    </svg>
                    Desktop
                </button>
                <button type="button" id="btn-device-mobile" onclick="setDevice('mobile')"
                        style="padding:5px 10px;background:white;color:#64748b;border:none;cursor:pointer;
                               display:inline-flex;align-items:center;gap:4px;font-size:.75rem;font-weight:500">
                    <svg style="width:12px;height:12px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="5" y="2" width="14" height="20" rx="2"/><circle cx="12" cy="17" r="1" fill="currentColor"/>
                    </svg>
                    Mobile
                </button>
            </div>
            <button type="button" onclick="togglePreview()"
                    style="padding:6px;border-radius:8px;border:none;background:transparent;cursor:pointer;color:#94a3b8"
                    onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Browser address bar --}}
    <div style="padding:8px 12px;background:#f1f5f9;border-bottom:1px solid #e2e8f0;flex-shrink:0">
        <div style="display:flex;align-items:center;gap:8px;background:white;
                    border:1px solid #e2e8f0;border-radius:8px;padding:6px 10px">
            <div style="display:flex;gap:5px;flex-shrink:0">
                <span style="width:10px;height:10px;border-radius:50%;background:#fc5c65;display:inline-block"></span>
                <span style="width:10px;height:10px;border-radius:50%;background:#ffd32a;display:inline-block"></span>
                <span style="width:10px;height:10px;border-radius:50%;background:#05c46b;display:inline-block"></span>
            </div>
            <svg style="width:12px;height:12px;color:#94a3b8;flex-shrink:0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <span id="preview-url-bar"
                  style="font-size:.7rem;color:#64748b;font-family:monospace;truncate;flex:1;overflow:hidden;white-space:nowrap;text-overflow:ellipsis">
                {{ request()->getHost() }}/{{ $page->slug ?? '…' }}
            </span>
        </div>
    </div>

    {{-- Loading bar --}}
    <div id="preview-loading"
         style="height:3px;background:linear-gradient(90deg,#4f46e5,#7c3aed,#4f46e5);
                background-size:200% 100%;animation:preview-shimmer 1.2s linear infinite;
                display:none;flex-shrink:0"></div>

    {{-- Viewport wrapper (for mobile scale) --}}
    <div style="flex:1;overflow:hidden;position:relative;background:#e5e7eb" id="preview-viewport-wrap">
        {{-- Desktop: full-width iframe --}}
        <iframe id="preview-iframe-desktop"
                style="width:100%;height:100%;border:none;display:block;background:white"
                srcdoc=""></iframe>

        {{-- Mobile: phone-frame iframe (hidden until device=mobile) --}}
        <div id="preview-mobile-wrap"
             style="display:none;align-items:flex-start;justify-content:center;overflow-y:auto;height:100%;padding:20px">
            <div style="width:390px;border-radius:44px;overflow:hidden;flex-shrink:0;
                        box-shadow:0 0 0 10px #1e293b, 0 0 0 12px #334155, 0 24px 60px rgba(0,0,0,.4);
                        position:relative">
                {{-- Phone notch --}}
                <div style="position:absolute;top:0;left:50%;transform:translateX(-50%);
                            width:120px;height:28px;background:#1e293b;border-radius:0 0 18px 18px;z-index:2"></div>
                <iframe id="preview-iframe-mobile"
                        style="width:390px;height:780px;border:none;display:block;background:white"
                        srcdoc=""></iframe>
            </div>
        </div>
    </div>

</div>

{{-- Overlay backdrop (mobile) --}}
<div id="preview-backdrop"
     onclick="togglePreview()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.3);z-index:49"></div>

<style>
.sortable-ghost  { opacity: .4; }
.sortable-drag   { box-shadow: 0 20px 40px rgba(0,0,0,.15); transform: rotate(0.5deg) scale(.99); }

@keyframes pulse-ring {
    0%   { box-shadow: 0 0 0 0   rgba(16,185,129,.5); }
    70%  { box-shadow: 0 0 0 8px rgba(16,185,129,0);  }
    100% { box-shadow: 0 0 0 0   rgba(16,185,129,0);  }
}
@keyframes preview-shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

</style>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
// ─── Constants ────────────────────────────────────────────────────────────────
let sectionIndex = {{ count($sections) }};

const FI = 'w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all';
const FL = 'block text-xs font-medium text-gray-600 mb-1';

const SECTION_META = {
    hero:       { icon: '🏠', label: 'Hero',          badge: 'bg-indigo-100 text-indigo-700' },
    text:       { icon: '📝', label: 'Text',          badge: 'bg-sky-100 text-sky-700' },
    image_text: { icon: '🖼', label: 'Image + Text',  badge: 'bg-violet-100 text-violet-700' },
    gallery:    { icon: '🎨', label: 'Gallery',       badge: 'bg-rose-100 text-rose-700' },
    cta:        { icon: '📣', label: 'Call to Action','badge': 'bg-amber-100 text-amber-700' },
};

// ─── Init ─────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {

    // Auto-slug on new pages
    @if(!$page->exists)
    document.getElementById('page-title').addEventListener('input', function () {
        document.getElementById('page-slug').value = this.value
            .toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-');
    });
    @endif

    // Drag-and-drop reordering
    Sortable.create(document.getElementById('sections-container'), {
        handle:     '.drag-handle',
        animation:  180,
        easing:     'cubic-bezier(0.25, 1, 0.5, 1)',
        ghostClass: 'sortable-ghost',
        dragClass:  'sortable-drag',
        onEnd:      reindexSections,
    });

    syncUI();
});

// ─── State helpers ────────────────────────────────────────────────────────────
function syncUI() {
    const count = document.querySelectorAll('#sections-container [data-section]').length;
    document.getElementById('sections-empty').classList.toggle('hidden', count > 0);

    const badge = document.getElementById('sections-count-badge');
    if (count > 0) {
        badge.textContent = `${count} section${count !== 1 ? 's' : ''}`;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

// Called by SortableJS after every drag — fixes all name="sections[n][…]"
// attributes to match the new visual order so the form submits correctly.
function reindexSections() {
    document.querySelectorAll('#sections-container [data-section]').forEach((card, i) => {
        card.dataset.index = i;
        card.querySelectorAll('[name^="sections["]').forEach(el => {
            el.name = el.name.replace(/sections\[\d+\]/, `sections[${i}]`);
        });
    });
    sectionIndex = document.querySelectorAll('#sections-container [data-section]').length;
}

function removeSection(btn) {
    btn.closest('[data-section]').remove();
    reindexSections();
    syncUI();
}

// Updates the grey preview text in the card header as the user types.
function updatePreview(input) {
    const label = input.closest('[data-section]')?.querySelector('[data-preview-label]');
    if (label) label.textContent = input.value;
}

// ─── Section field templates ──────────────────────────────────────────────────
function buildFields(type, idx, d) {
    d = d || {};
    const v = (key) => (d[key] || '').toString().replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    const input  = (name, placeholder, extra='') =>
        `<input type="text" name="sections[${idx}][${name}]" value="${v(name)}" placeholder="${placeholder}" ${extra} class="${FI}">`;
    const url    = (name, placeholder) =>
        `<input type="url" name="sections[${idx}][${name}]" value="${v(name)}" placeholder="${placeholder}" class="${FI}">`;
    const label  = (text) => `<label class="${FL}">${text}</label>`;
    const field  = (lbl, inner) => `<div>${label(lbl)}${inner}</div>`;
    const grid   = (cols) => `<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">${cols}</div>`;

    switch (type) {
        case 'hero': return grid(
            field('Title',       input('title',       'Welcome to our community', 'data-preview-src oninput="updatePreview(this)"')) +
            field('Subtitle',    input('subtitle',    'A place of hope and belonging')) +
            field('Button Text', input('button_text', 'Learn More')) +
            field('Button URL',  input('button_url',  '/about'))
        );
        case 'text': return (
            field('Heading', input('heading', 'Section heading', 'data-preview-src oninput="updatePreview(this)"')) +
            field('Content', `<textarea name="sections[${idx}][content]" rows="4" placeholder="Your content here…" class="${FI} resize-none">${v('content')}</textarea>`)
        );
        case 'image_text': return (
            field('Heading',   input('heading', 'About Us', 'data-preview-src oninput="updatePreview(this)"')) +
            field('Body Text', `<textarea name="sections[${idx}][text]" rows="3" placeholder="Describe your organization…" class="${FI} resize-none">${v('text')}</textarea>`) +
            field('Image URL', url('image', 'https://example.com/image.jpg'))
        );
        case 'gallery': return (
            field('Heading', input('heading', 'Our Gallery', 'data-preview-src oninput="updatePreview(this)"')) +
            field('Image URLs <span class="font-normal text-gray-400">(one per line)</span>',
                `<textarea name="sections[${idx}][images]" rows="4" placeholder="https://example.com/photo1.jpg&#10;https://example.com/photo2.jpg" class="${FI} font-mono text-xs resize-none">${v('images')}</textarea>`)
        );
        case 'cta': return grid(
            field('Heading',     input('heading',     'Ready to get started?', 'data-preview-src oninput="updatePreview(this)"')) +
            field('Sub-text',    input('subtext',     'Join us this Sunday')) +
            field('Button Text', input('button_text', 'Get Involved')) +
            field('Button URL',  input('button_url',  '/contact'))
        );
        default: return '';
    }
}

// ─── Card builder ─────────────────────────────────────────────────────────────
function buildSectionCard(type, idx, data) {
    const meta        = SECTION_META[type] || { icon: '📄', label: type, badge: 'bg-gray-100 text-gray-600' };
    const previewText = (data && (data.title || data.heading)) || '';
    const fields      = buildFields(type, idx, data);
    const badgeText   = type.replace('_', '+');

    return `
<div data-section data-index="${idx}"
     class="flex border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow"
     x-data="{ open: true }">

    <button type="button" tabindex="-1"
            class="drag-handle flex items-center px-2.5 bg-gray-50 border-r border-gray-200 cursor-grab active:cursor-grabbing hover:bg-indigo-50 hover:text-indigo-400 text-gray-300 transition-colors touch-none"
            title="Drag to reorder">
        <svg class="w-3.5 h-3.5 pointer-events-none" fill="currentColor" viewBox="0 0 16 16">
            <circle cx="5" cy="3" r="1.3"/><circle cx="11" cy="3" r="1.3"/>
            <circle cx="5" cy="8" r="1.3"/><circle cx="11" cy="8" r="1.3"/>
            <circle cx="5" cy="13" r="1.3"/><circle cx="11" cy="13" r="1.3"/>
        </svg>
    </button>

    <div class="flex-1 min-w-0">
        <div @click="open = !open"
             class="flex items-center gap-2.5 px-4 py-2.5 bg-gray-50/80 border-b border-gray-100 cursor-pointer hover:bg-gray-100/60 transition-colors select-none">
            <span class="text-base leading-none flex-shrink-0">${meta.icon}</span>
            <span class="text-xs font-semibold text-gray-700 flex-shrink-0">${meta.label}</span>
            <span data-preview-label class="text-xs text-gray-400 italic truncate flex-1 min-w-0">${previewText}</span>
            <div class="flex items-center gap-2 ml-auto flex-shrink-0">
                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide ${meta.badge}">${badgeText}</span>
                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-0.5"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="p-4 space-y-3">
            <input type="hidden" name="sections[${idx}][type]" value="${type}">
            ${fields}
            <div class="flex justify-end pt-2 border-t border-gray-100 mt-1">
                <button type="button" onclick="removeSection(this)"
                        class="inline-flex items-center gap-1.5 text-xs text-red-400 hover:text-red-600 font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Remove section
                </button>
            </div>
        </div>
    </div>
</div>`;
}

// ─── Public: called by "Add Section" buttons ──────────────────────────────────
function addSection(type, data) {
    const idx       = sectionIndex++;
    const container = document.getElementById('sections-container');
    container.insertAdjacentHTML('beforeend', buildSectionCard(type, idx, data || {}));

    const newCard = container.lastElementChild;
    // Boot Alpine on the dynamically inserted card
    if (typeof Alpine !== 'undefined' && Alpine.initTree) {
        Alpine.initTree(newCard);
    }

    syncUI();
    newCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// ─── Live Preview ─────────────────────────────────────────────────────────────

let _previewOpen   = false;
let _previewTimer  = null;
let _previewDevice = 'desktop';
let _previewCtrl   = null;   // AbortController for in-flight fetch

const PREVIEW_URL = '{{ route("admin.pages.preview") }}';
const CSRF_TOKEN  = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function togglePreview() {
    _previewOpen = !_previewOpen;
    const panel    = document.getElementById('preview-panel');
    const backdrop = document.getElementById('preview-backdrop');
    const btn      = document.getElementById('preview-toggle-btn');
    const label    = document.getElementById('preview-toggle-label');
    const mainEl   = document.querySelector('main.overflow-y-auto') || document.querySelector('main');

    panel.style.transform  = _previewOpen ? 'translateX(0)' : 'translateX(100%)';
    backdrop.style.display = _previewOpen && window.innerWidth < 1024 ? 'block' : 'none';

    if (mainEl) {
        mainEl.style.transition   = 'padding-right .3s cubic-bezier(.4,0,.2,1)';
        mainEl.style.paddingRight = _previewOpen ? '540px' : '';
    }

    if (btn) {
        btn.style.background  = _previewOpen ? '#4f46e5' : '';
        btn.style.color       = _previewOpen ? 'white'   : '';
        btn.style.borderColor = _previewOpen ? '#4f46e5' : '';
    }
    if (label) label.textContent = _previewOpen ? 'Close Preview' : 'Preview';

    if (_previewOpen) renderPreview();
}

function setDevice(device) {
    _previewDevice = device;
    const btnD      = document.getElementById('btn-device-desktop');
    const btnM      = document.getElementById('btn-device-mobile');
    const desktopIf = document.getElementById('preview-iframe-desktop');
    const mobileWrap= document.getElementById('preview-mobile-wrap');

    const on  = 'background:#4f46e5;color:white';
    const off = 'background:white;color:#64748b';

    if (device === 'mobile') {
        desktopIf.style.display   = 'none';
        mobileWrap.style.display  = 'flex';
        btnD.style.cssText       += ';' + off;
        btnM.style.cssText       += ';' + on;
    } else {
        desktopIf.style.display   = 'block';
        mobileWrap.style.display  = 'none';
        btnD.style.cssText       += ';' + on;
        btnM.style.cssText       += ';' + off;
    }
    renderPreview();
}

function schedulePreviewUpdate() {
    if (!_previewOpen) return;
    clearTimeout(_previewTimer);
    _previewTimer = setTimeout(renderPreview, 400);
}

function setPreviewLoading(on) {
    const bar = document.getElementById('preview-loading');
    if (bar) bar.style.display = on ? 'block' : 'none';
}

function setIframeSrcdoc(html) {
    const slug   = document.getElementById('page-slug')?.value || '';
    const urlBar = document.getElementById('preview-url-bar');
    if (urlBar) urlBar.textContent = '{{ request()->getHost() }}/' + (slug || '…');

    const id = _previewDevice === 'mobile' ? 'preview-iframe-mobile' : 'preview-iframe-desktop';
    const iframe = document.getElementById(id);
    if (iframe) iframe.srcdoc = html;
}

function renderPreview() {
    if (!_previewOpen) return;

    // Cancel any in-flight request
    if (_previewCtrl) _previewCtrl.abort();
    _previewCtrl = new AbortController();

    setPreviewLoading(true);

    const form = document.getElementById('page-form');
    const body = new FormData(form);
    body.set('_token', CSRF_TOKEN);

    fetch(PREVIEW_URL, {
        method:  'POST',
        body,
        signal:  _previewCtrl.signal,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.text())
    .then(html => {
        setPreviewLoading(false);
        setIframeSrcdoc(html);
    })
    .catch(err => {
        if (err.name !== 'AbortError') {
            setPreviewLoading(false);
            setIframeSrcdoc('<p style="font-family:sans-serif;padding:2rem;color:#ef4444">Preview failed. Check your console.</p>');
        }
    });
}

// ── Wire up live-update listeners ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('sections-container').addEventListener('input',  schedulePreviewUpdate);
    document.getElementById('sections-container').addEventListener('change', schedulePreviewUpdate);
    document.getElementById('page-title')?.addEventListener('input', schedulePreviewUpdate);
    document.getElementById('page-slug') ?.addEventListener('input', schedulePreviewUpdate);

    new MutationObserver(schedulePreviewUpdate)
        .observe(document.getElementById('sections-container'), { childList: true, subtree: false });
});

// ─── Footer toolbar helpers (unchanged) ──────────────────────────────────────
function wrapSelection(openTag, closeTag) {
    const el = document.getElementById('footer-content-input');
    if (!el) return;
    const s = el.selectionStart, e = el.selectionEnd;
    el.setRangeText(openTag + (el.value.substring(s, e) || 'text') + closeTag, s, e, 'end');
    el.dispatchEvent(new Event('input'));
    el.focus();
}

function insertTag(id, openTag, closeTag) {
    const el = document.getElementById(id);
    if (!el) return;
    const pos = el.selectionStart;
    el.setRangeText(openTag + closeTag, pos, pos, 'end');
    el.selectionStart = el.selectionEnd = pos + openTag.length;
    el.dispatchEvent(new Event('input'));
    el.focus();
}
</script>
@endsection
