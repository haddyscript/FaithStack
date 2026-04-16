@php $type = $section['type'] ?? 'text'; @endphp

<div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
    <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 bg-gray-200 px-2 py-0.5 rounded">
            {{ str_replace('_', ' ', $type) }}
        </span>
        <button type="button" onclick="this.closest('div.border').remove()"
                class="text-xs text-red-400 hover:text-red-600">Remove</button>
    </div>

    <input type="hidden" name="sections[{{ $index }}][type]" value="{{ $type }}">

    <div class="space-y-3">
        @if($type === 'hero')
            <div>
                <label class="text-xs font-medium text-gray-600">Title</label>
                <input type="text" name="sections[{{ $index }}][title]"
                       value="{{ $section['title'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Subtitle</label>
                <input type="text" name="sections[{{ $index }}][subtitle]"
                       value="{{ $section['subtitle'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Button Text</label>
                <input type="text" name="sections[{{ $index }}][button_text]"
                       value="{{ $section['button_text'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Button URL</label>
                <input type="text" name="sections[{{ $index }}][button_url]"
                       value="{{ $section['button_url'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>

        @elseif($type === 'text')
            <div>
                <label class="text-xs font-medium text-gray-600">Heading</label>
                <input type="text" name="sections[{{ $index }}][heading]"
                       value="{{ $section['heading'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Content</label>
                <textarea name="sections[{{ $index }}][content]" rows="4"
                          class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">{{ $section['content'] ?? '' }}</textarea>
            </div>

        @elseif($type === 'image_text')
            <div>
                <label class="text-xs font-medium text-gray-600">Heading</label>
                <input type="text" name="sections[{{ $index }}][heading]"
                       value="{{ $section['heading'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Text</label>
                <textarea name="sections[{{ $index }}][text]" rows="3"
                          class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">{{ $section['text'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Image URL</label>
                <input type="text" name="sections[{{ $index }}][image]"
                       value="{{ $section['image'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>

        @elseif($type === 'gallery')
            <div>
                <label class="text-xs font-medium text-gray-600">Heading</label>
                <input type="text" name="sections[{{ $index }}][heading]"
                       value="{{ $section['heading'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Image URLs (one per line)</label>
                <textarea name="sections[{{ $index }}][images]" rows="4"
                          class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm font-mono">{{ is_array($section['images'] ?? null) ? implode("\n", $section['images']) : ($section['images'] ?? '') }}</textarea>
            </div>

        @elseif($type === 'cta')
            <div>
                <label class="text-xs font-medium text-gray-600">Heading</label>
                <input type="text" name="sections[{{ $index }}][heading]"
                       value="{{ $section['heading'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Sub-text</label>
                <input type="text" name="sections[{{ $index }}][subtext]"
                       value="{{ $section['subtext'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Button Text</label>
                <input type="text" name="sections[{{ $index }}][button_text]"
                       value="{{ $section['button_text'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600">Button URL</label>
                <input type="text" name="sections[{{ $index }}][button_url]"
                       value="{{ $section['button_url'] ?? '' }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>
        @endif
    </div>
</div>
