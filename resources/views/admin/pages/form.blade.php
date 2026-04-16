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
                Preview
            </a>
        @endif
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
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Page Sections</h3>
                    <span class="text-xs text-gray-400">Drag to reorder (coming soon)</span>
                </div>

                <div id="sections-container" class="space-y-3">
                    @php $sections = old('sections', $page->getSections()); @endphp
                    @foreach($sections as $i => $section)
                        @include('admin.pages.partials.section-row', ['section' => $section, 'index' => $i])
                    @endforeach
                </div>

                {{-- Empty state --}}
                <div id="sections-empty" class="{{ count($sections) > 0 ? 'hidden' : '' }} py-8 text-center">
                    <div class="w-12 h-12 rounded-xl bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <p class="text-sm text-gray-400">No sections yet. Add one below.</p>
                </div>

                {{-- Add section --}}
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Add Section</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach([
                            'hero'       => ['label' => 'Hero',       'icon' => '🏠'],
                            'text'       => ['label' => 'Text',       'icon' => '📝'],
                            'image_text' => ['label' => 'Image + Text','icon' => '🖼'],
                            'gallery'    => ['label' => 'Gallery',    'icon' => '🎨'],
                            'cta'        => ['label' => 'Call to Action','icon' => '📣'],
                        ] as $type => $meta)
                            <button type="button"
                                    onclick="addSection('{{ $type }}')"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold border border-gray-200 rounded-xl text-gray-600 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all duration-150">
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

<script>
let sectionIndex = {{ count($sections) }};

// Auto-slug from title on new pages
@if(!$page->exists)
document.getElementById('page-title').addEventListener('input', function() {
    const slug = this.value
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-');
    document.getElementById('page-slug').value = slug;
});
@endif

function updateEmptyState() {
    const container = document.getElementById('sections-container');
    const empty = document.getElementById('sections-empty');
    if (container.children.length === 0) {
        empty.classList.remove('hidden');
    } else {
        empty.classList.add('hidden');
    }
}

const sectionTemplates = {
    hero: `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1 block">Title</label>
                <input type="text" name="sections[INDEX][title]" placeholder="Welcome to our community"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1 block">Subtitle</label>
                <input type="text" name="sections[INDEX][subtitle]" placeholder="A place of hope and belonging"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1 block">Button Text</label>
                <input type="text" name="sections[INDEX][button_text]" placeholder="Learn More"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1 block">Button URL</label>
                <input type="text" name="sections[INDEX][button_url]" placeholder="/about"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
            </div>
        </div>`,

    text: `
        <div>
            <label class="text-xs font-medium text-gray-600 mb-1 block">Heading</label>
            <input type="text" name="sections[INDEX][heading]" placeholder="Section heading"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600 mb-1 block">Content</label>
            <textarea name="sections[INDEX][content]" rows="4" placeholder="Your content here..."
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 resize-none"></textarea>
        </div>`,

    image_text: `
        <div>
            <label class="text-xs font-medium text-gray-600 mb-1 block">Heading</label>
            <input type="text" name="sections[INDEX][heading]" placeholder="About Us"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600 mb-1 block">Text</label>
            <textarea name="sections[INDEX][text]" rows="3" placeholder="Describe your organization..."
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 resize-none"></textarea>
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600 mb-1 block">Image URL</label>
            <input type="url" name="sections[INDEX][image]" placeholder="https://example.com/image.jpg"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
        </div>`,

    gallery: `
        <div>
            <label class="text-xs font-medium text-gray-600 mb-1 block">Heading</label>
            <input type="text" name="sections[INDEX][heading]" placeholder="Our Gallery"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600 mb-1 block">Image URLs <span class="text-gray-400">(one per line)</span></label>
            <textarea name="sections[INDEX][images]" rows="4" placeholder="https://example.com/photo1.jpg&#10;https://example.com/photo2.jpg"
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 resize-none"></textarea>
        </div>`,

    cta: `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1 block">Heading</label>
                <input type="text" name="sections[INDEX][heading]" placeholder="Ready to get started?"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1 block">Sub-text</label>
                <input type="text" name="sections[INDEX][subtext]" placeholder="Join us this Sunday"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1 block">Button Text</label>
                <input type="text" name="sections[INDEX][button_text]" placeholder="Get Involved"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1 block">Button URL</label>
                <input type="text" name="sections[INDEX][button_url]" placeholder="/contact"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
            </div>
        </div>`
};

const sectionLabels = {
    hero: '🏠 Hero',
    text: '📝 Text',
    image_text: '🖼 Image + Text',
    gallery: '🎨 Gallery',
    cta: '📣 Call to Action',
};

// Footer toolbar helpers
function wrapSelection(openTag, closeTag) {
    const el = document.getElementById('footer-content-input');
    if (!el) return;
    const start = el.selectionStart, end = el.selectionEnd;
    const selected = el.value.substring(start, end);
    const replacement = openTag + (selected || 'text') + closeTag;
    el.setRangeText(replacement, start, end, 'end');
    el.dispatchEvent(new Event('input'));
    el.focus();
}

function insertTag(id, openTag, closeTag) {
    const el = document.getElementById(id);
    if (!el) return;
    const pos = el.selectionStart;
    const replacement = openTag + closeTag;
    el.setRangeText(replacement, pos, pos, 'end');
    el.selectionStart = el.selectionEnd = pos + openTag.length;
    el.dispatchEvent(new Event('input'));
    el.focus();
}

function addSection(type) {
    const container = document.getElementById('sections-container');
    const idx = sectionIndex++;
    const label = sectionLabels[type] || type;
    const html = `
        <div class="border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm" x-data="{ collapsed: false }">
            <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 cursor-pointer" @click="collapsed = !collapsed">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-gray-500 tracking-wider">${label}</span>
                    <svg :class="collapsed ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <button type="button" @click.stop="$event.currentTarget.closest('[x-data]').remove(); updateEmptyState()"
                    class="text-xs font-medium text-red-400 hover:text-red-600 transition-colors px-2 py-0.5 rounded hover:bg-red-50">
                    Remove
                </button>
            </div>
            <div x-show="!collapsed" x-transition class="p-4">
                <input type="hidden" name="sections[${idx}][type]" value="${type}">
                <div class="space-y-3">
                    ${sectionTemplates[type].replace(/INDEX/g, idx)}
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    updateEmptyState();
}
</script>
@endsection
