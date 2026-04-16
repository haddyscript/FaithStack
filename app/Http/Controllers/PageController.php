<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Render the homepage (slug = "home" or first published page).
     */
    public function home(): View
    {
        $tenant = app('tenant');

        $page = Page::forTenant($tenant->id)
            ->published()
            ->where('slug', 'home')
            ->first();

        if (! $page) {
            $page = Page::forTenant($tenant->id)->published()->first();
        }

        abort_if(! $page, 404);

        return view("themes.{$tenant->getThemeSlug()}.layouts.app", [
            'page' => $page,
        ]);
    }

    /**
     * Render a page by slug.
     */
    public function show(string $slug): View
    {
        $tenant = app('tenant');

        $page = Page::forTenant($tenant->id)
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view("themes.{$tenant->getThemeSlug()}.layouts.app", [
            'page' => $page,
        ]);
    }
}
