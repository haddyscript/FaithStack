<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $tenant = app('tenant');

        $upcoming = Event::forTenant($tenant->id)
            ->published()
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->get();

        $past = Event::forTenant($tenant->id)
            ->published()
            ->where('start_date', '<', now())
            ->orderByDesc('start_date')
            ->limit(6)
            ->get();

        return view('events.public', compact('upcoming', 'past', 'tenant'));
    }

    public function show(Event $event): View
    {
        $tenant = app('tenant');
        abort_unless($event->tenant_id === $tenant->id && $event->is_published, 404);

        return view('events.detail', compact('event', 'tenant'));
    }
}
