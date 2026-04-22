<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(): View
    {
        $tenant = app('tenant');
        $groups = Group::forTenant($tenant->id)->withCount('members')->orderBy('name')->get();
        $tags   = Tag::forTenant($tenant->id)->withCount('members')->orderBy('name')->get();

        return view('admin.groups.index', compact('groups', 'tags', 'tenant'));
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant    = app('tenant');
        $validated = $this->validateGroup($request);

        Group::create([...$validated, 'tenant_id' => $tenant->id]);

        return redirect()->route('admin.groups.index')->with('success', 'Group created.');
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        abort_unless($group->tenant_id === app('tenant')->id, 403);

        $group->update($this->validateGroup($request));

        return redirect()->route('admin.groups.index')->with('success', 'Group updated.');
    }

    public function destroy(Group $group): RedirectResponse
    {
        abort_unless($group->tenant_id === app('tenant')->id, 403);
        $group->delete();

        return redirect()->route('admin.groups.index')->with('success', 'Group deleted.');
    }

    // ─── Tag management (shown on same groups page) ───────────────────────────

    public function storeTag(Request $request): RedirectResponse
    {
        $tenant    = app('tenant');
        $validated = $this->validateTag($request);

        Tag::create([...$validated, 'tenant_id' => $tenant->id]);

        return redirect()->route('admin.groups.index')->with('success', 'Tag created.');
    }

    public function updateTag(Request $request, Tag $tag): RedirectResponse
    {
        abort_unless($tag->tenant_id === app('tenant')->id, 403);

        $tag->update($this->validateTag($request));

        return redirect()->route('admin.groups.index')->with('success', 'Tag updated.');
    }

    public function destroyTag(Tag $tag): RedirectResponse
    {
        abort_unless($tag->tenant_id === app('tenant')->id, 403);
        $tag->delete();

        return redirect()->route('admin.groups.index')->with('success', 'Tag deleted.');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function validateGroup(Request $request): array
    {
        return $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'color'       => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
    }

    private function validateTag(Request $request): array
    {
        return $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
    }
}
