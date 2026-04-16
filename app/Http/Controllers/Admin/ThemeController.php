<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function index(): View
    {
        $tenant = app('tenant');
        $themes = Theme::orderBy('category')->orderBy('name')->get()->groupBy('category');

        return view('admin.themes.index', compact('tenant', 'themes'));
    }

    public function activate(Request $request): RedirectResponse
    {
        $tenant = app('tenant');

        $request->validate([
            'theme_id' => ['required', 'exists:themes,id'],
        ]);

        $tenant->update(['theme_id' => $request->theme_id]);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme activated successfully.');
    }
}
