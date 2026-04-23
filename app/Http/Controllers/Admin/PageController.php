<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $tenant = app('tenant');
        $pages  = Page::forTenant($tenant->id)->latest()->get();

        return view('admin.pages.index', compact('pages', 'tenant'));
    }

    public function create(): View
    {
        return view('admin.pages.form', [
            'page'   => new Page(),
            'tenant' => app('tenant'),
            'themes' => Theme::orderBy('category')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant    = app('tenant');
        $validated = $this->validatePage($request, $tenant->id);

        $themeId = $this->resolveAppliedTheme($request);

        Page::create([
            ...$validated,
            'tenant_id' => $tenant->id,
            'content'   => $this->buildContent($request),
            ...($themeId !== false ? ['theme_id' => $themeId] : []),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }

    public function edit(Page $page): View
    {
        $this->authorizePage($page);

        return view('admin.pages.form', [
            'page'   => $page,
            'tenant' => app('tenant'),
            'themes' => Theme::orderBy('category')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $this->authorizePage($page);
        $tenant    = app('tenant');
        $validated = $this->validatePage($request, $tenant->id, $page->id);

        $updateData = $validated;

        if ($request->has('sections') || $request->has('footer_enabled')) {
            $updateData['content'] = $this->buildContent($request);
        }

        $appliedTheme = $this->resolveAppliedTheme($request);
        if ($appliedTheme !== false) {
            $updateData['theme_id'] = $appliedTheme;
        }

        $page->update($updateData);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $this->authorizePage($page);
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted.');
    }

    public function preview(Request $request): \Illuminate\Http\Response
    {
        $tenant = app('tenant');

        $page = new Page([
            'tenant_id'    => $tenant->id,
            'title'        => $request->input('title', 'Preview'),
            'slug'         => $request->input('slug', 'preview'),
            'is_published' => true,
            'content'      => $this->buildContent($request),
        ]);

        // Allow the preview panel to override the active theme
        $previewThemeId = $request->input('_preview_theme_id');
        $theme = $previewThemeId
            ? Theme::find($previewThemeId) ?? $tenant->theme
            : $tenant->theme;

        try {
            if ($theme && $theme->view_path) {
                $html = view($theme->view_path . '.layout', [
                    'page'        => $page,
                    'theme'       => $theme,
                    'config'      => $theme->config ?? [],
                    'content'     => $page->content ?? [],
                    'tenant'      => $tenant,
                    'navItems'    => $tenant->navigation,
                    'themeSlug'   => $tenant->getThemeSlug(),
                    'themeConfig' => $theme->config ?? [],
                ])->render();
            } else {
                $html = view('themes.' . $tenant->getThemeSlug() . '.layouts.app', [
                    'page'   => $page,
                    'tenant' => $tenant,
                ])->render();
            }
        } catch (\Throwable $e) {
            $html = '<p style="font-family:sans-serif;padding:2rem;color:#ef4444">Preview error: ' . e($e->getMessage()) . '</p>';
        }

        return response($html)->header('Content-Type', 'text/html');
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function validatePage(Request $request, int $tenantId, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['required', 'string', 'max:255',
                \Illuminate\Validation\Rule::unique('pages')
                    ->where('tenant_id', $tenantId)
                    ->ignore($ignoreId),
            ],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }

    private function buildContent(Request $request): array
    {
        $sections = [];

        foreach ($request->input('sections', []) as $section) {
            if (empty($section['type'])) {
                continue;
            }
            $sections[] = array_filter($section, fn($v) => $v !== null && $v !== '');
        }

        $footerEnabled = $request->boolean('footer_enabled');
        $footerContent = $footerEnabled
            ? $this->sanitizeFooterHtml($request->input('footer_content', ''))
            : '';

        return [
            'sections'       => $sections,
            'footer_enabled' => $footerEnabled,
            'footer_content' => $footerContent,
        ];
    }

    /** Strip scripts and event handlers; allow basic formatting tags. */
    private function sanitizeFooterHtml(string $html): string
    {
        $allowed = '<p><br><strong><em><b><i><u><ul><ol><li><a><h2><h3><h4><h5><span><div><hr><small>';
        $clean   = strip_tags($html, $allowed);
        // Remove on* event handlers and javascript: URIs
        $clean   = preg_replace('/\s+on\w+\s*=\s*(["\'])[^"\']*\1/i', '', $clean);
        $clean   = preg_replace('/javascript\s*:/i', '', $clean);

        return trim($clean);
    }

    private function authorizePage(Page $page): void
    {
        abort_unless($page->tenant_id === app('tenant')->id, 403);
    }

    /**
     * Returns the theme_id to save, or false if no theme was applied via preview.
     * Returns null if the user explicitly cleared the theme override.
     */
    private function resolveAppliedTheme(Request $request): int|null|false
    {
        if (! $request->has('_applied_theme_id')) {
            return false; // not present — don't touch theme_id
        }

        $id = $request->input('_applied_theme_id');
        if (! $id) return null;

        return Theme::where('id', $id)->value('id');
    }
}
