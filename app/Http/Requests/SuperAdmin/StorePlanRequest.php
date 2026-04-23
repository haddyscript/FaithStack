<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'superadmin';
    }

    public function rules(): array
    {
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $planId = $this->route('plan')->id ?? null;

            return [
                'name'              => 'required|string|max:255|unique:plans,name,' . $planId,
                'description'       => 'required|string|max:1000',
                'price_monthly'     => 'required|numeric|min:0|max:99999.99',
                'trial_days'        => 'nullable|integer|min:0|max:365',
                'is_featured'       => 'boolean',
                'is_active'         => 'boolean',
                'cta_label'         => 'required|string|max:50',
                'badge'             => 'nullable|string|max:100',
                'features'          => 'required|array|min:1',
                'features.*'        => 'string|max:255',
                'missing_features'  => 'nullable|array',
                'missing_features.*' => 'string|max:255',
                'limits'            => 'nullable|array',
            ];
        }
        return [
            'name'              => 'required|string|max:255|unique:plans,name,',
            'description'       => 'required|string|max:1000',
            'price_monthly'     => 'required|numeric|min:0|max:99999.99',
            'trial_days'        => 'nullable|integer|min:0|max:365',
            'is_featured'       => 'boolean',
            'is_active'         => 'boolean',
            'cta_label'         => 'required|string|max:50',
            'badge'             => 'nullable|string|max:100',
            'features'          => 'required|array|min:1',
            'features.*'        => 'string|max:255',
            'missing_features'  => 'nullable|array',
            'missing_features.*' => 'string|max:255',
            'limits'            => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Plan name is required',
            'name.unique'         => 'A plan with this name already exists',
            'description.required' => 'Description is required',
            'price_monthly.required' => 'Monthly price is required',
            'price_monthly.numeric'  => 'Price must be a valid number',
            'features.required'   => 'At least one feature is required',
            'features.min'        => 'Please add at least one feature',
        ];
    }

    protected function passedValidation(): void
    {
        $data = $this->validated();

        // Generate slug from name
        if (! isset($data['slug'])) {
            $data['slug'] = str($data['name'])->slug();
        }

        $limits = $data['limits'] ?? [];
        $processedLimits = [];

        foreach ($limits as $key => $value) {
            if (strpos($key, 'new_') === 0) {
                continue;
            }

            if (str_ends_with($key, '_value')) {
                continue;
            }

            if (! empty($value) && ! is_null($value)) {
                $valueKey = $key . '_value';

                if (isset($limits[$valueKey]) && ! empty($limits[$valueKey])) {
                    $processedLimits[$value] = (int) $limits[$valueKey];
                }
            }
        }

        $this->merge([
            'slug' => $data['slug'],
            'limits' => ! empty($processedLimits) ? $processedLimits : null,
        ]);
    }
}
