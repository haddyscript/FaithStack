@php $isEdit = isset($event) && $event->exists; @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ── Main form ─────────────────────────────────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Event Details --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Event Details</h3>
            </div>
            <div class="p-5 space-y-4">

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Title <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                           placeholder="e.g. Sunday Morning Service"
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 @error('title') border-red-300 @enderror">
                    @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Description</label>
                    <textarea name="description" rows="4"
                              placeholder="Tell people what this event is about…"
                              class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 resize-none @error('description') border-red-300 @enderror">{{ old('description', $event->description) }}</textarea>
                    @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- Date & Time --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Date & Time</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Start Date & Time <span class="text-red-400">*</span>
                    </label>
                    <input type="datetime-local" name="start_date" required
                           value="{{ old('start_date', $event->start_date?->format('Y-m-d\TH:i')) }}"
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 @error('start_date') border-red-300 @enderror">
                    @error('start_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">End Date & Time</label>
                    <input type="datetime-local" name="end_date"
                           value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i')) }}"
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 @error('end_date') border-red-300 @enderror">
                    @error('end_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Location</h3>
            </div>
            <div class="p-5 space-y-4">

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2">Location Type</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="location_type" value="physical"
                                   {{ old('location_type', $event->location_type ?? 'physical') === 'physical' ? 'checked' : '' }}
                                   class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700">Physical</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="location_type" value="online"
                                   {{ old('location_type', $event->location_type) === 'online' ? 'checked' : '' }}
                                   class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700">Online</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Address / URL
                    </label>
                    <input type="text" name="location" value="{{ old('location', $event->location) }}"
                           placeholder="123 Church St or https://zoom.us/j/..."
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 @error('location') border-red-300 @enderror">
                    @error('location')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- Call to Action --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Call to Action <span class="text-slate-400 font-normal text-xs">(optional)</span></h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Button Label</label>
                    <input type="text" name="cta_text" value="{{ old('cta_text', $event->cta_text) }}"
                           placeholder="e.g. Register Now"
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 @error('cta_text') border-red-300 @enderror">
                    @error('cta_text')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Button URL</label>
                    <input type="url" name="cta_url" value="{{ old('cta_url', $event->cta_url) }}"
                           placeholder="https://…"
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 @error('cta_url') border-red-300 @enderror">
                    @error('cta_url')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

    </div>

    {{-- ── Right column ──────────────────────────────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Publish --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Visibility</h3>
            </div>
            <div class="p-5">
                <label class="flex items-start gap-3 cursor-pointer">
                    <div class="mt-0.5" x-data="{ checked: {{ old('is_published', $event->is_published ?? false) ? 'true' : 'false' }} }">
                        <input type="checkbox" name="is_published" value="1"
                               x-model="checked"
                               {{ old('is_published', $event->is_published) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div @click="checked = !checked"
                             class="relative w-10 h-5 rounded-full transition-colors cursor-pointer"
                             :class="checked ? 'bg-indigo-600' : 'bg-slate-200'">
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform"
                                 :class="checked ? 'translate-x-5' : 'translate-x-0'"></div>
                        </div>
                        <input type="hidden" name="is_published" :value="checked ? 1 : 0">
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Publish event</p>
                        <p class="text-xs text-slate-400 mt-0.5">Published events appear on your public events page.</p>
                    </div>
                </label>
            </div>
        </div>

        {{-- Banner Image --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Banner Image <span class="text-slate-400 font-normal text-xs">(optional)</span></h3>
            </div>
            <div class="p-5"
                 x-data="{ preview: '{{ $isEdit && $event->image_url ? $event->image_url : '' }}' }">

                @if($isEdit && $event->image_url)
                    <img :src="preview || '{{ $event->image_url }}'"
                         class="w-full h-32 object-cover rounded-xl border border-slate-100 mb-3" alt="Banner">
                @else
                    <div class="w-full h-32 rounded-xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center mb-3 overflow-hidden"
                         x-show="!preview">
                        <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-xs text-slate-400">No image uploaded</p>
                    </div>
                    <img :src="preview" x-show="preview"
                         class="w-full h-32 object-cover rounded-xl border border-slate-100 mb-3" alt="Preview">
                @endif

                <label class="block cursor-pointer">
                    <input type="file" name="image" accept="image/*" class="sr-only"
                           @change="preview = URL.createObjectURL($event.target.files[0])">
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 border border-slate-200 rounded-lg px-3 py-1.5 hover:bg-slate-50 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $isEdit ? 'Change image' : 'Upload image' }}
                    </span>
                </label>
                <p class="text-xs text-slate-400 mt-1.5">JPG, PNG or WebP · max 3MB</p>
                @error('image')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 py-2.5 text-sm font-semibold text-white rounded-xl adm-btn-primary shadow-sm">
                {{ $isEdit ? 'Save Changes' : 'Create Event' }}
            </button>
            <a href="{{ $isEdit ? route('admin.events.show', $event) : route('admin.events.index') }}"
               class="flex-1 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl text-center hover:bg-slate-50 transition-colors">
                Cancel
            </a>
        </div>

    </div>
</div>
