<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'superadmin';
    }

    protected function prepareForValidation(): void
    {
        // Auto-generate slug from name before validation runs
        if ($this->filled('name')) {
            $this->merge(['slug' => (string) str($this->input('name'))->slug()]);
        }

        // Normalize booleans — unchecked toggles won't be present in the POST body
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_active'   => $this->boolean('is_active'),
        ]);

        // Convert parallel limit arrays into an associative array
        // Form sends: limit_keys[] = ['max_members', 'max_storage_mb']
        //             limit_values[] = ['500', '2048']
        $keys   = $this->input('limit_keys', []);
        $values = $this->input('limit_values', []);
        $limits = [];

        foreach ($keys as $i => $key) {
            $key = trim((string) $key);
            if ($key === '') {
                continue;
            }
            $val = $values[$i] ?? '';
            if ($val !== '') {
                $limits[$key] = (int) $val;
            }
        }

        $this->merge(['limits' => !empty($limits) ? $limits : null]);
    }

    public function rules(): array
    {
        $planId = $this->route('plan')?->id;

        return [
            'name'               => ['required', 'string', 'max:255', Rule::unique('plans', 'name')->ignore($planId)],
            'slug'               => ['required', 'string', 'max:255', Rule::unique('plans', 'slug')->ignore($planId)],
            'description'        => 'required|string|max:1000',
            'price_monthly'      => 'required|numeric|min:0|max:99999.99',
            'trial_days'         => 'nullable|integer|min:0|max:365',
            'is_featured'        => 'boolean',
            'is_active'          => 'boolean',
            'cta_label'          => 'required|string|max:50',
            'badge'              => 'nullable|string|max:100',
            'features'           => 'required|array|min:1',
            'features.*'         => 'required|string|max:255',
            'missing_features'   => 'nullable|array',
            'missing_features.*' => 'nullable|string|max:255',
            'limits'             => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Plan name is required.',
            'name.unique'            => 'A plan with this name already exists.',
            'description.required'   => 'Description is required.',
            'price_monthly.required' => 'Monthly price is required.',
            'price_monthly.numeric'  => 'Price must be a valid number.',
            'features.required'      => 'At least one feature is required.',
            'features.min'           => 'Please add at least one feature.',
            'cta_label.required'     => 'Button label is required.',
        ];
    }
}
