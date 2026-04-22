<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\Group;
use App\Models\Member;
use App\Models\MemberActivity;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = app('tenant');

        $query = Member::forTenant($tenant->id)
            ->with(['groups', 'tags'])
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($groupId = $request->input('group_id')) {
            $query->whereHas('groups', fn ($q) => $q->where('groups.id', $groupId));
        }

        if ($tagId = $request->input('tag_id')) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
        }

        $members = $query->paginate(25)->withQueryString();
        $groups  = Group::forTenant($tenant->id)->orderBy('name')->get();
        $tags    = Tag::forTenant($tenant->id)->orderBy('name')->get();

        return view('admin.members.index', compact('members', 'tenant', 'groups', 'tags'));
    }

    public function create(): View
    {
        $tenant       = app('tenant');
        $groups       = Group::forTenant($tenant->id)->orderBy('name')->get();
        $tags         = Tag::forTenant($tenant->id)->orderBy('name')->get();
        $customFields = CustomField::forTenant($tenant->id)->orderBy('sort_order')->get();

        return view('admin.members.create', compact('tenant', 'groups', 'tags', 'customFields'));
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant    = app('tenant');
        $validated = $this->validateMember($request, $tenant->id);

        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store("members/{$tenant->id}", 'public');
        }

        $member = Member::create([
            ...$validated,
            'tenant_id'   => $tenant->id,
            'photo'       => $photo,
            'custom_data' => $request->input('custom_data', []),
        ]);

        $groupIds = Group::forTenant($tenant->id)->whereIn('id', $request->input('groups', []))->pluck('id');
        $member->groups()->sync($groupIds);

        $tagIds = Tag::forTenant($tenant->id)->whereIn('id', $request->input('tags', []))->pluck('id');
        $member->tags()->sync($tagIds);

        MemberActivity::create([
            'member_id' => $member->id,
            'tenant_id' => $tenant->id,
            'user_id'   => auth()->id(),
            'type'      => 'general',
            'content'   => 'Member profile created.',
        ]);

        return redirect()->route('admin.members.show', $member)->with('success', 'Member added successfully.');
    }

    public function show(Member $member): View
    {
        $this->authorizeMember($member);

        $tenant = app('tenant');
        $member->load(['groups', 'tags', 'activities.user']);

        $groups       = Group::forTenant($tenant->id)->orderBy('name')->get();
        $tags         = Tag::forTenant($tenant->id)->orderBy('name')->get();
        $customFields = CustomField::forTenant($tenant->id)->orderBy('sort_order')->get();

        return view('admin.members.show', compact('member', 'tenant', 'groups', 'tags', 'customFields'));
    }

    public function edit(Member $member): View
    {
        $this->authorizeMember($member);

        $tenant       = app('tenant');
        $groups       = Group::forTenant($tenant->id)->orderBy('name')->get();
        $tags         = Tag::forTenant($tenant->id)->orderBy('name')->get();
        $customFields = CustomField::forTenant($tenant->id)->orderBy('sort_order')->get();

        return view('admin.members.edit', compact('member', 'tenant', 'groups', 'tags', 'customFields'));
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $this->authorizeMember($member);

        $tenant    = app('tenant');
        $validated = $this->validateMember($request, $tenant->id, $member->id);

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store("members/{$tenant->id}", 'public');
        }

        $oldStatus = $member->status;
        $validated['custom_data'] = $request->input('custom_data', []);

        $member->update($validated);

        if ($oldStatus !== $member->fresh()->status) {
            MemberActivity::create([
                'member_id' => $member->id,
                'tenant_id' => $tenant->id,
                'user_id'   => auth()->id(),
                'type'      => 'status_change',
                'content'   => 'Status changed from ' . (Member::STATUSES[$oldStatus]['label'] ?? $oldStatus) . ' to ' . (Member::STATUSES[$member->status]['label'] ?? $member->status) . '.',
                'metadata'  => ['from' => $oldStatus, 'to' => $member->status],
            ]);
        }

        $groupIds = Group::forTenant($tenant->id)->whereIn('id', $request->input('groups', []))->pluck('id');
        $member->groups()->sync($groupIds);

        $tagIds = Tag::forTenant($tenant->id)->whereIn('id', $request->input('tags', []))->pluck('id');
        $member->tags()->sync($tagIds);

        return redirect()->route('admin.members.show', $member)->with('success', 'Member updated.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $this->authorizeMember($member);

        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->route('admin.members.index')->with('success', 'Member deleted.');
    }

    public function addNote(Request $request, Member $member): RedirectResponse
    {
        $this->authorizeMember($member);

        $request->validate(['content' => ['required', 'string', 'max:2000']]);

        MemberActivity::create([
            'member_id' => $member->id,
            'tenant_id' => app('tenant')->id,
            'user_id'   => auth()->id(),
            'type'      => 'note',
            'content'   => $request->input('content'),
        ]);

        return redirect()->route('admin.members.show', $member)->with('success', 'Note added.');
    }

    public function deleteActivity(MemberActivity $activity): RedirectResponse
    {
        abort_unless($activity->tenant_id === app('tenant')->id, 403);
        abort_unless($activity->type === 'note', 403);

        $memberId = $activity->member_id;
        $activity->delete();

        return redirect()->route('admin.members.show', $memberId)->with('success', 'Note deleted.');
    }

    public function syncGroups(Request $request, Member $member): RedirectResponse
    {
        $this->authorizeMember($member);

        $tenant   = app('tenant');
        $groupIds = Group::forTenant($tenant->id)->whereIn('id', $request->input('groups', []))->pluck('id');
        $member->groups()->sync($groupIds);

        return redirect()->route('admin.members.show', $member)->with('success', 'Groups updated.');
    }

    public function toggleTag(Request $request, Member $member, Tag $tag): RedirectResponse
    {
        $this->authorizeMember($member);
        abort_unless($tag->tenant_id === app('tenant')->id, 403);

        $member->tags()->toggle($tag->id);

        return redirect()->route('admin.members.show', $member)->with('success', 'Tag updated.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $tenant  = app('tenant');
        $action  = $request->input('action');
        $ids     = $request->input('member_ids', []);

        $members = Member::forTenant($tenant->id)->whereIn('id', $ids)->get();

        if ($members->isEmpty()) {
            return redirect()->route('admin.members.index')->with('error', 'No members selected.');
        }

        return match ($action) {
            'delete' => $this->bulkDelete($members),
            'assign_group' => $this->bulkAssignGroup($request, $members, $tenant),
            'add_tag' => $this->bulkAddTag($request, $members, $tenant),
            'prepare_message' => $this->bulkPrepareMessage($members),
            default => redirect()->route('admin.members.index'),
        };
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function bulkDelete($members): RedirectResponse
    {
        $count = $members->count();
        foreach ($members as $m) {
            if ($m->photo) Storage::disk('public')->delete($m->photo);
            $m->delete();
        }
        return redirect()->route('admin.members.index')->with('success', "{$count} member(s) deleted.");
    }

    private function bulkAssignGroup(Request $request, $members, $tenant): RedirectResponse
    {
        $request->validate(['group_id' => ['required', 'integer']]);
        $group = Group::forTenant($tenant->id)->findOrFail($request->input('group_id'));
        foreach ($members as $m) {
            $m->groups()->syncWithoutDetaching([$group->id]);
        }
        return redirect()->route('admin.members.index')->with('success', "{$members->count()} member(s) added to '{$group->name}'.");
    }

    private function bulkAddTag(Request $request, $members, $tenant): RedirectResponse
    {
        $request->validate(['tag_id' => ['required', 'integer']]);
        $tag = Tag::forTenant($tenant->id)->findOrFail($request->input('tag_id'));
        foreach ($members as $m) {
            $m->tags()->syncWithoutDetaching([$tag->id]);
        }
        return redirect()->route('admin.members.index')->with('success', "{$members->count()} member(s) tagged '{$tag->name}'.");
    }

    private function bulkPrepareMessage($members): RedirectResponse
    {
        session(['campaign_member_ids' => $members->pluck('id')->toArray()]);
        return redirect()->route('admin.members.index')->with('success', "{$members->count()} member(s) selected for messaging. Ready when communication is enabled.");
    }

    private function validateMember(Request $request, int $tenantId, ?int $ignoreId = null): array
    {
        return $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['nullable', 'email', 'max:255',
                Rule::unique('members')->where('tenant_id', $tenantId)->ignore($ignoreId),
            ],
            'phone'      => ['nullable', 'string', 'max:30'],
            'birthday'   => ['nullable', 'date'],
            'address'    => ['nullable', 'string', 'max:500'],
            'status'     => ['required', Rule::in(array_keys(Member::STATUSES))],
            'photo'      => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function authorizeMember(Member $member): void
    {
        abort_unless($member->tenant_id === app('tenant')->id, 403);
    }
}
