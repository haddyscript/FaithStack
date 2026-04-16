<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Navigation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavigationController extends Controller
{
    public function index(): View
    {
        $tenant  = app('tenant');
        $navItems = Navigation::forTenant($tenant->id)->get();

        return view('admin.navigation.index', compact('navItems', 'tenant'));
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant = app('tenant');

        $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'url'   => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        Navigation::create([
            'tenant_id' => $tenant->id,
            'name'      => $request->name,
            'url'       => $request->url,
            'order'     => $request->order ?? 0,
        ]);

        return redirect()->route('admin.navigation.index')->with('success', 'Menu item added.');
    }

    public function update(Request $request, Navigation $navigation): RedirectResponse
    {
        abort_unless($navigation->tenant_id === app('tenant')->id, 403);

        $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'url'   => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $navigation->update($request->only(['name', 'url', 'order']));

        return redirect()->route('admin.navigation.index')->with('success', 'Menu item updated.');
    }

    public function destroy(Navigation $navigation): RedirectResponse
    {
        abort_unless($navigation->tenant_id === app('tenant')->id, 403);
        $navigation->delete();

        return redirect()->route('admin.navigation.index')->with('success', 'Menu item removed.');
    }
}
