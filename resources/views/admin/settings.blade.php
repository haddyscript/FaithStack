@extends('admin.layouts.app')

@section('title', 'Settings')
@section('heading', 'Site Settings')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- General info --}}
        <div class="bg-white rounded-xl border shadow-sm p-6 space-y-4">
            <h3 class="font-semibold text-gray-700">General</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                <input type="email" name="email" value="{{ old('email', $tenant->email) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('address', $tenant->address) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                @if($tenant->logo)
                    <img src="{{ Storage::url($tenant->logo) }}" alt="Logo" class="h-12 mb-2 rounded">
                @endif
                <input type="file" name="logo" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
        </div>

        {{-- Theme --}}
        <div class="bg-white rounded-xl border shadow-sm p-6 space-y-4">
            <h3 class="font-semibold text-gray-700">Theme</h3>

            @foreach($themes as $theme)
                @php $selected = old('theme_id', $tenant->theme_id) == $theme->id; @endphp
                <label class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition
                              {{ $selected ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                    <input type="radio" name="theme_id" value="{{ $theme->id }}"
                           {{ $selected ? 'checked' : '' }} class="mt-0.5">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $theme->name }}</p>
                        <div class="flex gap-1 mt-1">
                            <span class="inline-block w-4 h-4 rounded-full border"
                                  style="background: {{ $theme->config['primary_color'] ?? '#ccc' }}"></span>
                            <span class="inline-block w-4 h-4 rounded-full border"
                                  style="background: {{ $theme->config['secondary_color'] ?? '#ccc' }}"></span>
                            <span class="text-xs text-gray-400 ml-1">{{ $theme->config['font'] ?? '' }}</span>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg text-sm transition">
            Save Settings
        </button>
    </div>
</form>
@endsection
