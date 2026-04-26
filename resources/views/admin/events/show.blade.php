@extends('admin.layouts.app')

@section('title', $event->title)
@section('heading', $event->title)

@section('breadcrumbs')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Events',    'url' => route('admin.events.index')],
        ['label' => $event->title],
    ]"/>
@endsection

@section('header-actions')
    <a href="{{ route('admin.events.edit', $event) }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white adm-btn-primary shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit Event
    </a>
    @if($event->is_published)
        <a href="{{ route('events.show', $event) }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            View Live
        </a>
    @endif
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ── Main content ─────────────────────────────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Banner --}}
        @if($event->image_url)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                 class="w-full h-52 object-cover">
        </div>
        @endif

        {{-- Description --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Description</h3>
            </div>
            <div class="p-5">
                @if($event->description)
                    <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap">{{ $event->description }}</p>
                @else
                    <p class="text-sm text-slate-400 italic">No description provided.</p>
                @endif
            </div>
        </div>

        {{-- CTA --}}
        @if($event->cta_text && $event->cta_url)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Call to Action</h3>
            </div>
            <div class="p-5 flex items-center gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-700">{{ $event->cta_text }}</p>
                    <a href="{{ $event->cta_url }}" target="_blank"
                       class="text-xs text-indigo-600 hover:underline truncate block">{{ $event->cta_url }}</a>
                </div>
                <a href="{{ $event->cta_url }}" target="_blank"
                   class="flex-shrink-0 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-white adm-btn-primary shadow-sm">
                    {{ $event->cta_text }}
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
        </div>
        @endif

    </div>

    {{-- ── Sidebar ───────────────────────────────────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Status --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Status</h3>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex items-center gap-2">
                    @if($event->is_published)
                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Published
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold px-3 py-1.5 rounded-xl bg-slate-50 text-slate-500 border border-slate-100">
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                            Draft
                        </span>
                    @endif
                    @if($event->isUpcoming())
                        <span class="text-xs text-emerald-600 font-medium">· Upcoming</span>
                    @else
                        <span class="text-xs text-slate-400">· Past event</span>
                    @endif
                </div>
                <p class="text-xs text-slate-400">
                    Created {{ $event->created_at->diffForHumans() }}
                    @if($event->updated_at != $event->created_at)
                        · Updated {{ $event->updated_at->diffForHumans() }}
                    @endif
                </p>
            </div>
        </div>

        {{-- Date & Time --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Date & Time</h3>
            </div>
            <div class="p-5 space-y-3 text-sm">
                <div class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-slate-700">{{ $event->start_date->format('l, F j, Y') }}</p>
                        <p class="text-slate-500">{{ $event->start_date->format('g:i A') }}
                            @if($event->end_date)
                                – {{ $event->end_date->format('g:i A') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Location --}}
        @if($event->location)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 text-sm">Location</h3>
            </div>
            <div class="p-5 flex items-start gap-2.5 text-sm">
                @if($event->isOnline())
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                @else
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                @endif
                <div>
                    <p class="font-medium text-slate-700">{{ $event->isOnline() ? 'Online' : 'Physical' }}</p>
                    <p class="text-slate-500 break-all">{{ $event->location }}</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Danger zone --}}
        <div class="bg-white rounded-2xl border border-red-100 shadow-sm">
            <div class="px-5 py-4 border-b border-red-50">
                <h3 class="font-bold text-red-700 text-sm">Delete Event</h3>
            </div>
            <div class="p-5">
                <p class="text-xs text-slate-500 mb-3">Permanently remove this event. This cannot be undone.</p>
                <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                      onsubmit="return confirm('Permanently delete this event?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full py-2 text-sm font-semibold text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition-colors">
                        Delete Event
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
