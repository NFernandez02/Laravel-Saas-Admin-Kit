<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function __construct(private RoleService $service) {}

    public function index(): View
    {
        $roles = Role::withCount('users')->
        when(request('search'), function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        })
            ->paginate(10)
            ->withQueryString();

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = Permission::all();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        /** @var array{
         * name: string,
         * permissions: list<int>
         * } $data
         */
        $data = $request->validated();
        $this->service->create($data);

        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');
        $permissions = Permission::all();

        return view('admin.roles.edit', compact(['role', 'permissions']));
    }

    public function update(Role $role, UpdateRoleRequest $request): RedirectResponse
    {
        /** @var array{
         * name: string,
         * permissions: list<int>
         * } $data
         */
        $data = $request->validated();
        $this->service->update($role, $data);

        return redirect()->route('admin.roles.index');
    }

    public function destroy(Role $role): RedirectResponse
    {
        try {
            $this->service->delete($role);

            return redirect()->route('admin.roles.index');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
