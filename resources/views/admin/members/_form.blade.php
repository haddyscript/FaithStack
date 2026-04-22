@php $isEdit = isset($member) && $member->exists; @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ── Main form ─────────────────────────────────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Personal Information --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Personal Information</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">First Name <span class="text-red-400">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" required
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 @error('first_name') border-red-300 @enderror"
                           placeholder="John">
                    @error('first_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Last Name <span class="text-red-400">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" required
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 @error('last_name') border-red-300 @enderror"
                           placeholder="Doe">
                    @error('last_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $member->email) }}"
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 @error('email') border-red-300 @enderror"
                           placeholder="john@example.com">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $member->phone) }}"
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50"
                           placeholder="+1 555 000 0000">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Birthday</label>
                    <input type="date" name="birthday" value="{{ old('birthday', $member->birthday?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status <span class="text-red-400">*</span></label>
                    <select name="status" required
                            class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50">
                        @foreach(\App\Models\Member::STATUSES as $key => $st)
                            <option value="{{ $key }}" {{ old('status', $member->status ?: 'visitor') === $key ? 'selected' : '' }}>
                                {{ $st['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Address</label>
                    <textarea name="address" rows="2"
                              class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50 resize-none"
                              placeholder="123 Main St, City, State ZIP">{{ old('address', $member->address) }}</textarea>
                </div>

            </div>
        </div>

        {{-- Custom Fields --}}
        @if($customFields->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Additional Information</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($customFields as $field)
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">{{ $field->label }}</label>
                    @php $val = old("custom_data.{$field->id}", $member->getCustomValue($field->id)); @endphp
                    @if($field->type === 'select')
                        <select name="custom_data[{{ $field->id }}]"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50">
                            <option value="">Select…</option>
                            @foreach($field->options ?? [] as $opt)
                                <option value="{{ $opt }}" {{ $val === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    @elseif($field->type === 'date')
                        <input type="date" name="custom_data[{{ $field->id }}]" value="{{ $val }}"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50">
                    @elseif($field->type === 'number')
                        <input type="number" name="custom_data[{{ $field->id }}]" value="{{ $val }}"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50">
                    @else
                        <input type="text" name="custom_data[{{ $field->id }}]" value="{{ $val }}"
                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent bg-slate-50">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- ── Right column ──────────────────────────────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Profile Photo --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Profile Photo</h3>
            </div>
            <div class="p-5" x-data="{ preview: '{{ $isEdit && $member->photo_url ? $member->photo_url : '' }}' }">
                @if($isEdit && $member->photo_url)
                <div class="mb-3">
                    <img :src="preview || '{{ $member->photo_url }}'"
                         class="w-20 h-20 rounded-xl object-cover border-2 border-slate-100" alt="Photo">
                </div>
                @else
                <div class="w-20 h-20 rounded-xl bg-slate-100 flex items-center justify-center mb-3" x-show="!preview">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
                <img :src="preview" class="w-20 h-20 rounded-xl object-cover border-2 border-slate-100 mb-3" x-show="preview">
                @endif
                <label class="block cursor-pointer">
                    <input type="file" name="photo" accept="image/*" class="sr-only"
                           @change="preview = URL.createObjectURL($event.target.files[0])">
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 border border-slate-200 rounded-lg px-3 py-1.5 hover:bg-slate-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $isEdit ? 'Change photo' : 'Upload photo' }}
                    </span>
                </label>
                <p class="text-xs text-slate-400 mt-1.5">JPG, PNG or GIF · max 2MB</p>
                @error('photo')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Groups --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Groups</h3>
            </div>
            <div class="p-5">
                @if($groups->isEmpty())
                    <p class="text-xs text-slate-400">No groups yet. <a href="{{ route('admin.groups.index') }}" class="text-indigo-600 hover:underline">Create one</a>.</p>
                @else
                    <div class="space-y-2">
                        @foreach($groups as $group)
                        <label class="flex items-center gap-2.5 cursor-pointer hover:bg-slate-50 rounded-lg p-1.5 -mx-1.5 transition-colors">
                            <input type="checkbox" name="groups[]" value="{{ $group->id }}"
                                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                   {{ in_array($group->id, old('groups', $isEdit ? $member->groups->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                            <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $group->color }}"></span>
                            <span class="text-sm text-slate-700">{{ $group->name }}</span>
                        </label>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Tags --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Tags</h3>
            </div>
            <div class="p-5">
                @if($tags->isEmpty())
                    <p class="text-xs text-slate-400">No tags yet. <a href="{{ route('admin.groups.index') }}" class="text-indigo-600 hover:underline">Create one</a>.</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                        @php $checked = in_array($tag->id, old('tags', $isEdit ? $member->tags->pluck('id')->toArray() : [])); @endphp
                        <label class="cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="sr-only peer" {{ $checked ? 'checked' : '' }}>
                            <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full font-medium border transition-all cursor-pointer
                                         peer-checked:text-white"
                                  :class="''"
                                  style="{{ $checked
                                    ? 'border-color: '.$tag->color.'; background-color: '.$tag->color.'; color: white;'
                                    : 'border-color: '.$tag->color.'55; color: '.$tag->color.'; background-color: '.$tag->color.'12;' }}">
                                {{ $tag->name }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Click to select multiple tags.</p>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <button type="submit" class="flex-1 py-2.5 text-sm font-semibold text-white rounded-xl adm-btn-primary shadow-sm">
                {{ $isEdit ? 'Save Changes' : 'Add Member' }}
            </button>
            <a href="{{ $isEdit ? route('admin.members.show', $member) : route('admin.members.index') }}"
               class="flex-1 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl text-center hover:bg-slate-50 transition-colors">
                Cancel
            </a>
        </div>

    </div>
</div>
