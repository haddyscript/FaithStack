<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Theme;
use Illuminate\View\View;

class PageController extends Controller
{
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

        return $this->renderPage($page, $tenant);
    }

    public function show(string $slug): View
    {
        $tenant = app('tenant');

        $page = Page::forTenant($tenant->id)
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->renderPage($page, $tenant);
    }

    private function renderPage(Page $page, $tenant): View
    {
        // Page-level theme override takes priority, then tenant theme
        $theme = $page->theme ?? $tenant->theme;

        if ($theme && $theme->view_path) {
            return view($theme->view_path . '.layout', [
                'page'    => $page,
                'theme'   => $theme,
                'config'  => $theme->config ?? [],
                'content' => $page->content ?? [],
            ]);
        }

        // Fallback to legacy layout system
        return view("themes.{$tenant->getThemeSlug()}.layouts.app", [
            'page' => $page,
        ]);
    }
}
