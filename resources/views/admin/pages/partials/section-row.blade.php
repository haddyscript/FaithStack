@php
$type = $section['type'] ?? 'text';
$labels = [
    'hero'       => '🏠 Hero',
    'text'       => '📝 Text',
    'image_text' => '🖼 Image + Text',
    'gallery'    => '🎨 Gallery',
    'cta'        => '📣 Call to Action',
];
$label = $labels[$type] ?? ucfirst($type);
@endphp

<div class="border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm" x-data="{ collapsed: false }">
    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 cursor-pointer"
         @click="collapsed = !collapsed">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-500 tracking-wider">{{ $label }}</span>
            <svg :class="collapsed ? 'rotate-180' : ''"
                 class="w-4 h-4 text-gray-400 transition-transform duration-200"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <button type="button" @click.stop="this.closest('[x-data]').remove(); updateEmptyState()"
            class="text-xs font-medium text-red-400 hover:text-red-600 transition-colors px-2 py-0.5 rounded hover:bg-red-50">
            Remove
        </button>
    </div>

    {{-- Body (collapsible) --}}
    <div x-show="!collapsed" x-transition class="p-4">
        <input type="hidden" name="sections[{{ $index }}][type]" value="{{ $type }}">

        <div class="space-y-3">

            @if($type === 'hero')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-medium text-gray-600 mb-1 block">Title</label>
                        <input type="text" name="sections[{{ $index }}][title]"
                               value="{{ $section['title'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 mb-1 block">Subtitle</label>
                        <input type="text" name="sections[{{ $index }}][subtitle]"
                               value="{{ $section['subtitle'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 mb-1 block">Button Text</label>
                        <input type="text" name="sections[{{ $index }}][button_text]"
                               value="{{ $section['button_text'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 mb-1 block">Button URL</label>
                        <input type="text" name="sections[{{ $index }}][button_url]"
                               value="{{ $section['button_url'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                    </div>
                </div>

            @elseif($type === 'text')
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Heading</label>
                    <input type="text" name="sections[{{ $index }}][heading]"
                           value="{{ $section['heading'] ?? '' }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Content</label>
                    <textarea name="sections[{{ $index }}][content]" rows="4"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 resize-none">{{ $section['content'] ?? '' }}</textarea>
                </div>

            @elseif($type === 'image_text')
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Heading</label>
                    <input type="text" name="sections[{{ $index }}][heading]"
                           value="{{ $section['heading'] ?? '' }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Text</label>
                    <textarea name="sections[{{ $index }}][text]" rows="3"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 resize-none">{{ $section['text'] ?? '' }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Image URL</label>
                    <input type="url" name="sections[{{ $index }}][image]"
                           value="{{ $section['image'] ?? '' }}"
                           placeholder="https://example.com/image.jpg"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                </div>

            @elseif($type === 'gallery')
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Heading</label>
                    <input type="text" name="sections[{{ $index }}][heading]"
                           value="{{ $section['heading'] ?? '' }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Image URLs <span class="text-gray-400">(one per line)</span></label>
                    <textarea name="sections[{{ $index }}][images]" rows="4"
                              placeholder="https://example.com/photo1.jpg&#10;https://example.com/photo2.jpg"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 resize-none">{{ is_array($section['images'] ?? null) ? implode("\n", $section['images']) : ($section['images'] ?? '') }}</textarea>
                </div>

            @elseif($type === 'cta')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-medium text-gray-600 mb-1 block">Heading</label>
                        <input type="text" name="sections[{{ $index }}][heading]"
                               value="{{ $section['heading'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 mb-1 block">Sub-text</label>
                        <input type="text" name="sections[{{ $index }}][subtext]"
                               value="{{ $section['subtext'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 mb-1 block">Button Text</label>
                        <input type="text" name="sections[{{ $index }}][button_text]"
                               value="{{ $section['button_text'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 mb-1 block">Button URL</label>
                        <input type="text" name="sections[{{ $index }}][button_url]"
                               value="{{ $section['button_url'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400">
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
