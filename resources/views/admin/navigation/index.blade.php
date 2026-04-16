@extends('admin.layouts.app')

@section('title', 'Navigation')
@section('heading', 'Navigation Menu')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Existing items --}}
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b bg-gray-50">
            <h3 class="font-semibold text-gray-700">Current Menu Items</h3>
        </div>
        @if($navItems->isEmpty())
            <p class="px-5 py-4 text-sm text-gray-400">No items yet.</p>
        @else
            <ul class="divide-y">
                @foreach($navItems as $item)
                    <li class="px-5 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $item->name }}</p>
                            <p class="text-xs text-gray-400">{{ $item->url }} &middot; order: {{ $item->order }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.navigation.destroy', $item) }}"
                              onsubmit="return confirm('Remove this item?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-600">Remove</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Add new item --}}
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <h3 class="font-semibold text-gray-700 mb-4">Add Menu Item</h3>
        <form method="POST" action="{{ route('admin.navigation.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                <input type="text" name="url" value="{{ old('url') }}" required placeholder="/about"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg text-sm transition">
                Add Item
            </button>
        </form>
    </div>
</div>
@endsection
