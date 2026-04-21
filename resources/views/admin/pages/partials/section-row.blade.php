@php
    $type = $section['type'] ?? 'text';
    $typeMeta = [
        'hero'       => ['icon' => '🏠', 'label' => 'Hero',          'badge' => 'bg-indigo-100 text-indigo-700'],
        'text'       => ['icon' => '📝', 'label' => 'Text',          'badge' => 'bg-sky-100 text-sky-700'],
        'image_text' => ['icon' => '🖼', 'label' => 'Image + Text',  'badge' => 'bg-violet-100 text-violet-700'],
        'gallery'    => ['icon' => '🎨', 'label' => 'Gallery',       'badge' => 'bg-rose-100 text-rose-700'],
        'cta'        => ['icon' => '📣', 'label' => 'Call to Action','badge' => 'bg-amber-100 text-amber-700'],
    ][$type] ?? ['icon' => '📄', 'label' => ucfirst($type), 'badge' => 'bg-gray-100 text-gray-600'];

    $previewText = $section['title'] ?? $section['heading'] ?? '';
    $fi = 'w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all';
    $fl = 'block text-xs font-medium text-gray-600 mb-1';
@endphp

<div data-section data-index="{{ $index }}"
     class="flex border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow"
     x-data="{ open: true }">

    {{-- Drag handle --}}
    <button type="button" tabindex="-1"
            class="drag-handle flex items-center px-2.5 bg-gray-50 border-r border-gray-200 cursor-grab active:cursor-grabbing hover:bg-indigo-50 hover:text-indigo-400 text-gray-300 transition-colors touch-none"
            title="Drag to reorder">
        <svg class="w-3.5 h-3.5 pointer-events-none" fill="currentColor" viewBox="0 0 16 16">
            <circle cx="5"  cy="3"  r="1.3"/>
            <circle cx="11" cy="3"  r="1.3"/>
            <circle cx="5"  cy="8"  r="1.3"/>
            <circle cx="11" cy="8"  r="1.3"/>
            <circle cx="5"  cy="13" r="1.3"/>
            <circle cx="11" cy="13" r="1.3"/>
        </svg>
    </button>

    <div class="flex-1 min-w-0">

        {{-- Card header (click to collapse) --}}
        <div @click="open = !open"
             class="flex items-center gap-2.5 px-4 py-2.5 bg-gray-50/80 border-b border-gray-100 cursor-pointer hover:bg-gray-100/60 transition-colors select-none">
            <span class="text-base leading-none flex-shrink-0">{{ $typeMeta['icon'] }}</span>
            <span class="text-xs font-semibold text-gray-700 flex-shrink-0">{{ $typeMeta['label'] }}</span>
            <span data-preview-label
                  class="text-xs text-gray-400 italic truncate flex-1 min-w-0">{{ $previewText }}</span>
            <div class="flex items-center gap-2 ml-auto flex-shrink-0">
                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide {{ $typeMeta['badge'] }}">
                    {{ str_replace('_', '+', $type) }}
                </span>
                <svg :class="open ? 'rotate-180' : ''"
                     class="w-4 h-4 text-gray-400 transition-transform duration-200"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        {{-- Fields --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-0.5"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="p-4 space-y-3">

            <input type="hidden" name="sections[{{ $index }}][type]" value="{{ $type }}">

            @if($type === 'hero')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="{{ $fl }}">Title</label>
                        <input type="text" name="sections[{{ $index }}][title]"
                               value="{{ $section['title'] ?? '' }}"
                               placeholder="Welcome to our community"
                               data-preview-src oninput="updatePreview(this)"
                               class="{{ $fi }}">
                    </div>
                    <div>
                        <label class="{{ $fl }}">Subtitle</label>
                        <input type="text" name="sections[{{ $index }}][subtitle]"
                               value="{{ $section['subtitle'] ?? '' }}"
                               placeholder="A place of hope and belonging"
                               class="{{ $fi }}">
                    </div>
                    <div>
                        <label class="{{ $fl }}">Button Text</label>
                        <input type="text" name="sections[{{ $index }}][button_text]"
                               value="{{ $section['button_text'] ?? '' }}"
                               placeholder="Learn More"
                               class="{{ $fi }}">
                    </div>
                    <div>
                        <label class="{{ $fl }}">Button URL</label>
                        <input type="text" name="sections[{{ $index }}][button_url]"
                               value="{{ $section['button_url'] ?? '' }}"
                               placeholder="/about"
                               class="{{ $fi }}">
                    </div>
                </div>

            @elseif($type === 'text')
                <div>
                    <label class="{{ $fl }}">Heading</label>
                    <input type="text" name="sections[{{ $index }}][heading]"
                           value="{{ $section['heading'] ?? '' }}"
                           placeholder="Section heading"
                           data-preview-src oninput="updatePreview(this)"
                           class="{{ $fi }}">
                </div>
                <div>
                    <label class="{{ $fl }}">Content</label>
                    <textarea name="sections[{{ $index }}][content]" rows="4"
                              placeholder="Your content here..."
                              class="{{ $fi }} resize-none">{{ $section['content'] ?? '' }}</textarea>
                </div>

            @elseif($type === 'image_text')
                <div>
                    <label class="{{ $fl }}">Heading</label>
                    <input type="text" name="sections[{{ $index }}][heading]"
                           value="{{ $section['heading'] ?? '' }}"
                           placeholder="About Us"
                           data-preview-src oninput="updatePreview(this)"
                           class="{{ $fi }}">
                </div>
                <div>
                    <label class="{{ $fl }}">Body Text</label>
                    <textarea name="sections[{{ $index }}][text]" rows="3"
                              placeholder="Describe your organization…"
                              class="{{ $fi }} resize-none">{{ $section['text'] ?? '' }}</textarea>
                </div>
                <div>
                    <label class="{{ $fl }}">Image URL</label>
                    <input type="url" name="sections[{{ $index }}][image]"
                           value="{{ $section['image'] ?? '' }}"
                           placeholder="https://example.com/image.jpg"
                           class="{{ $fi }}">
                </div>

            @elseif($type === 'gallery')
                <div>
                    <label class="{{ $fl }}">Heading</label>
                    <input type="text" name="sections[{{ $index }}][heading]"
                           value="{{ $section['heading'] ?? '' }}"
                           placeholder="Our Gallery"
                           data-preview-src oninput="updatePreview(this)"
                           class="{{ $fi }}">
                </div>
                <div>
                    <label class="{{ $fl }}">Image URLs
                        <span class="font-normal text-gray-400">(one per line)</span>
                    </label>
                    <textarea name="sections[{{ $index }}][images]" rows="4"
                              placeholder="https://example.com/photo1.jpg&#10;https://example.com/photo2.jpg"
                              class="{{ $fi }} font-mono text-xs resize-none">{{ is_array($section['images'] ?? null) ? implode("\n", $section['images']) : ($section['images'] ?? '') }}</textarea>
                </div>

            @elseif($type === 'cta')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="{{ $fl }}">Heading</label>
                        <input type="text" name="sections[{{ $index }}][heading]"
                               value="{{ $section['heading'] ?? '' }}"
                               placeholder="Ready to get started?"
                               data-preview-src oninput="updatePreview(this)"
                               class="{{ $fi }}">
                    </div>
                    <div>
                        <label class="{{ $fl }}">Sub-text</label>
                        <input type="text" name="sections[{{ $index }}][subtext]"
                               value="{{ $section['subtext'] ?? '' }}"
                               placeholder="Join us this Sunday"
                               class="{{ $fi }}">
                    </div>
                    <div>
                        <label class="{{ $fl }}">Button Text</label>
                        <input type="text" name="sections[{{ $index }}][button_text]"
                               value="{{ $section['button_text'] ?? '' }}"
                               placeholder="Get Involved"
                               class="{{ $fi }}">
                    </div>
                    <div>
                        <label class="{{ $fl }}">Button URL</label>
                        <input type="text" name="sections[{{ $index }}][button_url]"
                               value="{{ $section['button_url'] ?? '' }}"
                               placeholder="/contact"
                               class="{{ $fi }}">
                    </div>
                </div>
            @endif

            <div class="flex justify-end pt-2 border-t border-gray-100 mt-1">
                <button type="button" onclick="removeSection(this)"
                        class="inline-flex items-center gap-1.5 text-xs text-red-400 hover:text-red-600 font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Remove section
                </button>
            </div>

        </div>{{-- /fields --}}
    </div>
</div>
