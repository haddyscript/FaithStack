<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Navigation;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavigationController extends Controller
{
    public function index(): View
    {
        $tenant   = app('tenant');
        $navItems = Navigation::forTenant($tenant->id)->get();
        $pages    = Page::where('tenant_id', $tenant->id)->orderBy('title')->get(['id', 'title', 'slug']);

        return view('admin.navigation.index', compact('navItems', 'tenant', 'pages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant = app('tenant');

        $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'url'       => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:navigation,id'],
        ]);

        $maxOrder = Navigation::forTenant($tenant->id)->max('order') ?? -1;

        Navigation::create([
            'tenant_id' => $tenant->id,
            'parent_id' => $request->parent_id ?: null,
            'name'      => $request->name,
            'url'       => $request->url,
            'order'     => $maxOrder + 1,
        ]);

        return redirect()->route('admin.navigation.index')->with('success', 'Menu item added.');
    }

    public function update(Request $request, Navigation $navigation): RedirectResponse
    {
        abort_unless($navigation->tenant_id === app('tenant')->id, 403);

        $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'url'       => ['required', 'string', 'max:255'],
            'order'     => ['nullable', 'integer', 'min:0'],
            'parent_id' => ['nullable', 'exists:navigation,id'],
        ]);

        $navigation->update([
            'name'      => $request->name,
            'url'       => $request->url,
            'parent_id' => $request->parent_id ?: null,
            'order'     => $request->filled('order') ? (int) $request->order : $navigation->order,
        ]);

        return redirect()->route('admin.navigation.index')->with('success', 'Menu item updated.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $tenant = app('tenant');

        $request->validate([
            'items'             => ['required', 'array'],
            'items.*.id'        => ['required', 'integer', 'exists:navigation,id'],
            'items.*.order'     => ['required', 'integer'],
            'items.*.parent_id' => ['nullable', 'integer', 'exists:navigation,id'],
        ]);

        foreach ($request->items as $item) {
            Navigation::where('id', $item['id'])
                ->where('tenant_id', $tenant->id)
                ->update([
                    'order'     => $item['order'],
                    'parent_id' => $item['parent_id'] ?? null,
                ]);
        }

        return response()->json(['ok' => true]);
    }

    public function destroy(Navigation $navigation): RedirectResponse
    {
        abort_unless($navigation->tenant_id === app('tenant')->id, 403);
        $navigation->delete();

        return redirect()->route('admin.navigation.index')->with('success', 'Menu item removed.');
    }
}
