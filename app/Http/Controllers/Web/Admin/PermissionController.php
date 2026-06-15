<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permissions\StorePermissionRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller
{
    public function __construct(private PermissionService $service) {}

    public function index(): View
    {
        $permissions = Permission::withCount('roles')
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        return view('admin.permissions.create');
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        /** @var array{name: string} $data */
        $data = $request->validated();
        $this->service->create($data);

        return redirect()->route('admin.permissions.index');
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Permission $permission, UpdatePermissionRequest $request): RedirectResponse
    {
        /** @var array{name: string} $data */
        $data = $request->validated();
        $this->service->update($permission, $data);

        return redirect()->route('admin.permissions.index');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        try {
            $this->service->delete($permission);

            return redirect()->route('admin.permissions.index');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
