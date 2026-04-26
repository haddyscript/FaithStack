@extends('admin.layouts.app')

@section('title', 'Events')
@section('heading', 'Events')

@section('header-actions')
<a href="{{ route('admin.events.create') }}"
   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white adm-btn-primary shadow-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
    </svg>
    New Event
</a>
@endsection

@section('content')

{{-- ── Stats bar ─────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('admin.events.index') }}"
       class="bg-white rounded-xl border border-slate-100 shadow-sm px-4 py-3 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $totalCount }}</p>
            <p class="text-xs text-slate-500">Total</p>
        </div>
    </a>
    <a href="{{ route('admin.events.index', ['filter' => 'upcoming']) }}"
       class="bg-white rounded-xl border border-slate-100 shadow-sm px-4 py-3 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $upcomingCount }}</p>
            <p class="text-xs text-slate-500">Upcoming</p>
        </div>
    </a>
    <a href="{{ route('admin.events.index', ['filter' => 'published']) }}"
       class="bg-white rounded-xl border border-slate-100 shadow-sm px-4 py-3 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $publishedCount }}</p>
            <p class="text-xs text-slate-500">Published</p>
        </div>
    </a>
    <a href="{{ route('admin.events.index', ['filter' => 'draft']) }}"
       class="bg-white rounded-xl border border-slate-100 shadow-sm px-4 py-3 flex items-center gap-3 hover:shadow-md transition-shadow">
        <div class="w-9 h-9 rounded-lg bg-slate-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $draftCount }}</p>
            <p class="text-xs text-slate-500">Draft</p>
        </div>
    </a>
</div>

{{-- ── Main card ─────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('admin.events.index') }}"
          class="p-4 border-b border-slate-100 flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-48">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search events…"
                   class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent">
        </div>
        <select name="filter"
                class="text-sm border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent"
                onchange="this.form.submit()">
            <option value="">All Events</option>
            <option value="upcoming"  {{ request('filter') === 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
            <option value="past"      {{ request('filter') === 'past'      ? 'selected' : '' }}>Past</option>
            <option value="published" {{ request('filter') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft"     {{ request('filter') === 'draft'     ? 'selected' : '' }}>Draft</option>
        </select>
        @if(request('search') || request('filter'))
            <a href="{{ route('admin.events.index') }}"
               class="text-sm text-slate-500 hover:text-slate-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Clear
            </a>
        @endif
    </form>

    @if($events->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center px-6">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-slate-700 font-semibold text-sm mb-1">No events found</p>
            <p class="text-slate-400 text-xs mb-5">Create your first event to get started.</p>
            <a href="{{ route('admin.events.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white adm-btn-primary shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                New Event
            </a>
        </div>
    @else
        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Event</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden md:table-cell">Date</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">Location</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($events as $event)
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        {{-- Title + image --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($event->image_url)
                                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                                         class="w-10 h-10 rounded-lg object-cover flex-shrink-0 border border-slate-100">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <a href="{{ route('admin.events.show', $event) }}"
                                       class="font-semibold text-slate-800 hover:text-indigo-600 transition-colors truncate block">
                                        {{ $event->title }}
                                    </a>
                                    @if($event->description)
                                        <p class="text-xs text-slate-400 truncate max-w-xs">{{ Str::limit($event->description, 60) }}</p>
                                    @endif
                                    {{-- Mobile: date fallback --}}
                                    <p class="text-xs text-slate-400 md:hidden mt-0.5">{{ $event->start_date->format('M j, Y') }}</p>
                                </div>
                            </div>
                        </td>
                        {{-- Date --}}
                        <td class="px-4 py-3.5 hidden md:table-cell">
                            <div class="text-xs">
                                <p class="font-medium text-slate-700">{{ $event->start_date->format('M j, Y') }}</p>
                                <p class="text-slate-400">{{ $event->start_date->format('g:i A') }}</p>
                                @if($event->isUpcoming())
                                    <span class="inline-flex items-center gap-1 text-emerald-600 font-medium mt-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                        Upcoming
                                    </span>
                                @else
                                    <span class="text-slate-400">Past</span>
                                @endif
                            </div>
                        </td>
                        {{-- Location --}}
                        <td class="px-4 py-3.5 hidden lg:table-cell">
                            @if($event->location)
                                <div class="flex items-center gap-1.5 text-xs text-slate-600">
                                    @if($event->isOnline())
                                        <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    @endif
                                    <span class="truncate max-w-[160px]">{{ $event->location }}</span>
                                </div>
                            @else
                                <span class="text-xs text-slate-300">—</span>
                            @endif
                        </td>
                        {{-- Status --}}
                        <td class="px-4 py-3.5">
                            @if($event->is_published)
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-slate-50 text-slate-500 border border-slate-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                    Draft
                                </span>
                            @endif
                        </td>
                        {{-- Actions --}}
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-1 justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.events.edit', $event) }}"
                                   class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($event->is_published)
                                    <a href="{{ route('events.show', $event) }}" target="_blank"
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                       title="View public page">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                      onsubmit="return confirm('Delete this event?')"
                                      class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($events->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $events->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
