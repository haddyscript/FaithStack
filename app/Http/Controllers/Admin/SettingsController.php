<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $tenant = app('tenant');
        $themes = Theme::all();

        return view('admin.settings', compact('tenant', 'themes'));
    }

    public function update(Request $request): RedirectResponse
    {
        $tenant = app('tenant');

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'address'  => ['nullable', 'string', 'max:500'],
            'theme_id' => ['nullable', 'exists:themes,id'],
            'logo'     => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address', 'theme_id']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Branding colors
        $data['branding'] = [
            'sidebar_bg'   => $request->input('branding_sidebar_bg',   '#0f172a'),
            'sidebar_text' => $request->input('branding_sidebar_text',  '#94a3b8'),
            'primary'      => $request->input('branding_primary',       '#6366f1'),
            'accent'       => $request->input('branding_accent',        '#a78bfa'),
        ];

        $tenant->update($data);

        return redirect()->route('admin.settings')->with('success', 'Settings saved.');
    }
}
