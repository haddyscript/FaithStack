<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomFieldController extends Controller
{
    public function index(): View
    {
        $tenant = app('tenant');
        $fields = CustomField::forTenant($tenant->id)->orderBy('sort_order')->get();

        return view('admin.members.custom-fields', compact('fields', 'tenant'));
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant    = app('tenant');
        $validated = $this->validateField($request);

        $validated['name']       = Str::snake(Str::ascii($validated['label']));
        $validated['sort_order'] = CustomField::forTenant($tenant->id)->max('sort_order') + 1;

        CustomField::create([...$validated, 'tenant_id' => $tenant->id]);

        return redirect()->route('admin.member-fields.index')->with('success', 'Custom field created.');
    }

    public function update(Request $request, CustomField $customField): RedirectResponse
    {
        abort_unless($customField->tenant_id === app('tenant')->id, 403);

        $validated         = $this->validateField($request);
        $validated['name'] = Str::snake(Str::ascii($validated['label']));

        $customField->update($validated);

        return redirect()->route('admin.member-fields.index')->with('success', 'Custom field updated.');
    }

    public function destroy(CustomField $customField): RedirectResponse
    {
        abort_unless($customField->tenant_id === app('tenant')->id, 403);
        $customField->delete();

        return redirect()->route('admin.member-fields.index')->with('success', 'Custom field deleted.');
    }

    private function validateField(Request $request): array
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'type'  => ['required', Rule::in(CustomField::TYPES)],
        ]);

        if ($validated['type'] === 'select') {
            $raw = $request->input('options', '');
            $validated['options'] = collect(explode("\n", $raw))
                ->map(fn ($o) => trim($o))
                ->filter()
                ->values()
                ->all();
        } else {
            $validated['options'] = null;
        }

        return $validated;
    }
}
