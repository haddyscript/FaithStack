@extends('admin.layouts.app')

@section('title', 'Pages')
@section('heading', 'Pages')

@section('header-actions')
    <a href="{{ route('admin.pages.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        + New Page
    </a>
@endsection

@section('content')
@if($pages->isEmpty())
    <div class="bg-white rounded-xl border p-8 text-center text-gray-500">
        No pages yet. <a href="{{ route('admin.pages.create') }}" class="text-blue-600 hover:underline">Create your first page.</a>
    </div>
@else
    <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Slug</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Sections</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($pages as $page)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $page->title }}</td>
                        <td class="px-5 py-3 text-gray-500">/{{ $page->slug }}</td>
                        <td class="px-5 py-3">
                            @if($page->is_published)
                                <span class="inline-block bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded-full">Published</span>
                            @else
                                <span class="inline-block bg-gray-100 text-gray-500 text-xs font-medium px-2 py-0.5 rounded-full">Draft</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ count($page->getSections()) }}</td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" class="inline"
                                  onsubmit="return confirm('Delete this page?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
