<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = app('tenant');

        $query = Event::forTenant($tenant->id)->orderBy('start_date', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($filter = $request->input('filter')) {
            match ($filter) {
                'upcoming'  => $query->where('start_date', '>=', now()),
                'past'      => $query->where('start_date', '<', now()),
                'published' => $query->where('is_published', true),
                'draft'     => $query->where('is_published', false),
                default     => null,
            };
        }

        $events = $query->paginate(20)->withQueryString();

        $totalCount     = Event::forTenant($tenant->id)->count();
        $upcomingCount  = Event::forTenant($tenant->id)->where('start_date', '>=', now())->count();
        $publishedCount = Event::forTenant($tenant->id)->where('is_published', true)->count();
        $draftCount     = Event::forTenant($tenant->id)->where('is_published', false)->count();

        return view('admin.events.index', compact(
            'events', 'tenant',
            'totalCount', 'upcomingCount', 'publishedCount', 'draftCount'
        ));
    }

    public function create(): View
    {
        return view('admin.events.create', ['event' => new Event()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant    = app('tenant');
        $validated = $this->validateEvent($request);
        $validated['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store("events/{$tenant->id}", 'public');
        }

        Event::create([...$validated, 'tenant_id' => $tenant->id]);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event): View
    {
        $this->authorizeEvent($event);

        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        $this->authorizeEvent($event);

        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);

        $tenant    = app('tenant');
        $validated = $this->validateEvent($request);
        $validated['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store("events/{$tenant->id}", 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:5000'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['nullable', 'date', 'after_or_equal:start_date'],
            'location'      => ['nullable', 'string', 'max:255'],
            'location_type' => ['required', 'in:physical,online'],
            'cta_text'      => ['nullable', 'string', 'max:100'],
            'cta_url'       => ['nullable', 'url', 'max:500'],
            'image'         => ['nullable', 'image', 'max:3072'],
        ]);
    }

    private function authorizeEvent(Event $event): void
    {
        abort_unless($event->tenant_id === app('tenant')->id, 403);
    }
}
