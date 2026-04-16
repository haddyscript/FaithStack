@extends('admin.layouts.app')

@section('title', $page->exists ? 'Edit Page' : 'New Page')
@section('heading', $page->exists ? 'Edit: ' . $page->title : 'New Page')

@section('content')
<form method="POST"
      action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
      id="page-form">
    @csrf
    @if($page->exists) @method('PUT') @endif

    {{-- Page meta --}}
    <div class="bg-white rounded-xl border shadow-sm p-6 mb-6">
        <h3 class="font-semibold text-gray-700 mb-4">Page Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                    <span class="px-3 py-2 bg-gray-50 text-gray-400 text-sm border-r">/</span>
                    <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" required
                           class="flex-1 px-3 py-2 text-sm focus:outline-none">
                </div>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <input type="checkbox" name="is_published" id="is_published" value="1"
                   {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                   class="rounded">
            <label for="is_published" class="text-sm font-medium text-gray-700">Published</label>
        </div>
    </div>

    {{-- Sections builder --}}
    <div class="bg-white rounded-xl border shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-700">Page Sections</h3>
        </div>

        <div id="sections-container" class="space-y-4">
            @php $sections = old('sections', $page->getSections()); @endphp

            @foreach($sections as $i => $section)
                @include('admin.pages.partials.section-row', ['section' => $section, 'index' => $i])
            @endforeach
        </div>

        {{-- Add section buttons --}}
        <div class="mt-4 pt-4 border-t">
            <p class="text-xs text-gray-500 mb-2 font-medium uppercase tracking-wide">Add Section</p>
            <div class="flex flex-wrap gap-2">
                @foreach(['hero', 'text', 'image_text', 'gallery', 'cta'] as $type)
                    <button type="button"
                            onclick="addSection('{{ $type }}')"
                            class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        + {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg text-sm transition">
            {{ $page->exists ? 'Update Page' : 'Create Page' }}
        </button>
        <a href="{{ route('admin.pages.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
    </div>
</form>

<script>
let sectionIndex = {{ count($sections) }};

const sectionTemplates = {
    hero: `
        <div>
            <label class="text-xs font-medium text-gray-600">Title</label>
            <input type="text" name="sections[INDEX][title]" placeholder="Welcome to our church"
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600">Subtitle</label>
            <input type="text" name="sections[INDEX][subtitle]" placeholder="A community of faith..."
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600">Button Text</label>
            <input type="text" name="sections[INDEX][button_text]" placeholder="Learn More"
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600">Button URL</label>
            <input type="text" name="sections[INDEX][button_url]" placeholder="/about"
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>`,

    text: `
        <div>
            <label class="text-xs font-medium text-gray-600">Heading</label>
            <input type="text" name="sections[INDEX][heading]"
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600">Content</label>
            <textarea name="sections[INDEX][content]" rows="4"
                      class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm"></textarea>
        </div>`,

    image_text: `
        <div>
            <label class="text-xs font-medium text-gray-600">Heading</label>
            <input type="text" name="sections[INDEX][heading]"
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600">Text</label>
            <textarea name="sections[INDEX][text]" rows="3"
                      class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm"></textarea>
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600">Image URL</label>
            <input type="text" name="sections[INDEX][image]" placeholder="https://..."
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>`,

    gallery: `
        <div>
            <label class="text-xs font-medium text-gray-600">Heading</label>
            <input type="text" name="sections[INDEX][heading]"
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600">Image URLs (one per line)</label>
            <textarea name="sections[INDEX][images]" rows="4" placeholder="https://image1.jpg&#10;https://image2.jpg"
                      class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm font-mono"></textarea>
        </div>`,

    cta: `
        <div>
            <label class="text-xs font-medium text-gray-600">Heading</label>
            <input type="text" name="sections[INDEX][heading]"
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600">Sub-text</label>
            <input type="text" name="sections[INDEX][subtext]"
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600">Button Text</label>
            <input type="text" name="sections[INDEX][button_text]"
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>
        <div>
            <label class="text-xs font-medium text-gray-600">Button URL</label>
            <input type="text" name="sections[INDEX][button_url]"
                   class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
        </div>`
};

function addSection(type) {
    const container = document.getElementById('sections-container');
    const idx = sectionIndex++;
    const html = `
        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 bg-gray-200 px-2 py-0.5 rounded">${type.replace('_', ' ')}</span>
                <button type="button" onclick="this.closest('div.border').remove()" class="text-xs text-red-400 hover:text-red-600">Remove</button>
            </div>
            <input type="hidden" name="sections[${idx}][type]" value="${type}">
            <div class="space-y-3">
                ${sectionTemplates[type].replace(/INDEX/g, idx)}
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
}
</script>
@endsection
