<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Models\Role;
use App\Services\RoleService;

class RoleController extends Controller
{
    public function __construct(private RoleService $service) {}

    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }
    public function create()
    {
        return view('admin.roles.create');
    }
    public function store(StoreRoleRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Role $role, UpdateRoleRequest $request)
    {
        $this->service->update($role, $request->validated());
        return redirect()->route('admin.roles.index');
    }
    public function destroy(Role $role)
    {
        try {
            $this->service->delete($role);
            return redirect()->route('admin.roles.index');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
