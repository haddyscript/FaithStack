<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StorePlanRequest;
use App\Models\Plan;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PlanController extends Controller
{
    /**
     * Display a listing of all plans.
     */
    public function index(): View
    {
        $plans = Plan::orderBy('price_monthly')->get();

        return view('superadmin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create(): View
    {
        $defaultFeatures = [
            'Core messaging platform',
            'Member directory',
            'Event management',
        ];

        return view('superadmin.plans.form', [
            'plan'              => null,
            'defaultFeatures'   => $defaultFeatures,
            'isEditing'         => false,
        ]);
    }

    /**
     * Store a newly created plan in storage.
     */
    public function store(StorePlanRequest $request): RedirectResponse
    {
        $plan = Plan::create($request->validated());

        return redirect()
            ->route('superadmin.plans.index')
            ->with('success', "Plan '{$plan->name}' created successfully.");
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(Plan $plan): View
    {
        return view('superadmin.plans.form', [
            'plan'              => $plan,
            'defaultFeatures'   => [],
            'isEditing'         => true,
        ]);
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(StorePlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->update($request->validated());

        return redirect()
            ->route('superadmin.plans.index')
            ->with('success', "Plan '{$plan->name}' updated successfully.");
    }

    /**
     * Toggle the active status of a plan.
     */
    public function toggle(Plan $plan): RedirectResponse
    {
        $plan->update(['is_active' => ! $plan->is_active]);

        $status = $plan->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('superadmin.plans.index')
            ->with('success', "Plan '{$plan->name}' {$status}.");
    }

    /**
     * Delete the specified plan.
     */
    public function destroy(Plan $plan): RedirectResponse
    {
        // Check if any tenants are in use with this plan
        if ($plan->tenants()->exists()) {
            return redirect()
                ->route('superadmin.plans.index')
                ->with('error', "Cannot delete plan '{$plan->name}' — it has active subscribers. Please deactivate instead.");
        }

        $planName = $plan->name;
        $plan->delete();

        return redirect()
            ->route('superadmin.plans.index')
            ->with('success', "Plan '{$planName}' deleted successfully.");
    }
}
