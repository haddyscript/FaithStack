<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tenant::with('theme')->withCount(['pages', 'donations']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subdomain', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('subscription_status', $status);
        }

        $tenants = $query->latest()->paginate(20)->withQueryString();

        return view('superadmin.tenants.index', compact('tenants'));
    }

    public function create(): View
    {
        $themes = Theme::orderBy('category')->orderBy('name')->get();
        return view('superadmin.tenants.create', compact('themes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'subdomain'             => ['required', 'string', 'max:63', 'alpha_dash', 'unique:tenants,subdomain'],
            'email'                 => ['required', 'email', 'max:255'],
            'phone'                 => ['nullable', 'string', 'max:50'],
            'address'               => ['nullable', 'string', 'max:500'],
            'theme_id'              => ['nullable', 'exists:themes,id'],
            'subscription_status'   => ['required', Rule::in(['trial', 'active', 'expired', 'cancelled'])],
            'subscription_ends_at'  => ['nullable', 'date'],
            // Admin user
            'admin_name'            => ['required', 'string', 'max:255'],
            'admin_email'           => ['required', 'email', 'unique:users,email'],
            'admin_password'        => ['required', 'string', 'min:8'],
        ]);

        $tenant = Tenant::create([
            'name'                 => $data['name'],
            'subdomain'            => strtolower($data['subdomain']),
            'email'                => $data['email'],
            'phone'                => $data['phone'] ?? null,
            'address'              => $data['address'] ?? null,
            'theme_id'             => $data['theme_id'] ?? null,
            'subscription_status'  => $data['subscription_status'],
            'subscription_ends_at' => $data['subscription_ends_at'] ?? null,
        ]);

        User::create([
            'tenant_id' => $tenant->id,
            'name'      => $data['admin_name'],
            'email'     => $data['admin_email'],
            'password'  => Hash::make($data['admin_password']),
            'role'      => 'admin',
        ]);

        return redirect()->route('superadmin.tenants.index')
            ->with('success', "Tenant \"{$tenant->name}\" created successfully.");
    }

    public function edit(Tenant $tenant): View
    {
        $themes = Theme::orderBy('category')->orderBy('name')->get();
        $admins = User::where('tenant_id', $tenant->id)->where('role', 'admin')->get();
        return view('superadmin.tenants.edit', compact('tenant', 'themes', 'admins'));
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $data = $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'subdomain'            => ['required', 'string', 'max:63', 'alpha_dash', Rule::unique('tenants', 'subdomain')->ignore($tenant->id)],
            'email'                => ['required', 'email', 'max:255'],
            'phone'                => ['nullable', 'string', 'max:50'],
            'address'              => ['nullable', 'string', 'max:500'],
            'theme_id'             => ['nullable', 'exists:themes,id'],
            'subscription_status'  => ['required', Rule::in(['trial', 'active', 'expired', 'cancelled'])],
            'subscription_ends_at' => ['nullable', 'date'],
        ]);

        $tenant->update($data);

        return redirect()->route('superadmin.tenants.index')
            ->with('success', "Tenant \"{$tenant->name}\" updated successfully.");
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $name = $tenant->name;
        $tenant->delete();

        return redirect()->route('superadmin.tenants.index')
            ->with('success', "Tenant \"{$name}\" has been deleted.");
    }

    public function toggleSubscription(Request $request, Tenant $tenant): RedirectResponse
    {
        $newStatus = $tenant->subscription_status === 'active' ? 'expired' : 'active';
        $tenant->update(['subscription_status' => $newStatus]);

        return back()->with('success', "Subscription set to \"{$newStatus}\" for {$tenant->name}.");
    }
}
